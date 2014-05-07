<?php

/**
 * Controller
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
 
class Controller extends Config
{


	/**
	 * view object which will allow the controller to move onto the view stage
	 * @var object
	 */
	public $view;


	/**
	 * set of url segments used
	 * @var array
	 */
	public $urlNames = array();


	/**
	 * record of the current url position
	 * starts at -1 to load the initial controller first
	 * corresponds to url segments
	 * @var integer
	 */
	public $urlKey = -1;


	/**
	 * list of methods which cant be called by urls
	 * @todo could be stripped while generating url?
	 * @var array
	 */
	public $illegalMethods = array(
		'__construct'
		, 'load'
		, 'loadMethod'
	);


	/**
	 * adds 1 to the url key each time
	 * @param object $controller 
	 * @param object $database   
	 * @param object $config     
	 */
	public function __construct($system) {

		// construct in default fashion
		parent::__construct($system);

		// copy passed controllers view to this new controller
		// copy url key
		if (property_exists($system, 'view')) {
			$this->view = $system->view;
			$this->setUrlKey($system->getUrlKey());
		} else {
			$this->view = new View($this);
		}
	}


	/**
	 * initialises and attempts to load next class
	 * @return bool 
	 */
	public function loadClass() {

		// does the next class exist?
		if (! class_exists($className = $this->getClassName())) {
	
			// debugging
			if ($this->isDebug($this)) {
				echo 'controller -> ' . $this->getClassName() . ' does not exist';
				echo '<hr>';
			}
			return;
		}

		// instantiate class
		$controller = new $className($this);

		// initialise each class
		$controller->initialise();
				
		// debugging
		if ($this->isDebug($this)) {
			echo 'initialised -> ' . $controller->getClassName();
			echo '<hr>';
		}

		// move pointer forwards
		$controller->incrementUrlKey();

		// debugging
		if ($this->isDebug($this)) {
			echo 'attempting load -> ' . $controller->getClassName();
			echo '<hr>';
		}

		// initialise next class only if not renedering
		if (! $controller->view->getRender() && $controller->loadClass()) {
			return true;
		}

		// load method if not rendering
		if (! $controller->view->getRender()) {
			$controller->loadMethod();
		}

		// render the data
		$controller->view->render();

		// successfull load of final controller
		return true;
	}


	public function loadMethod() {

		// set method name
		$methodName = $this->config->getUrl($this->getUrlKey());

		// check the method is legal, if not always route away
		if (in_array($methodName, $this->illegalMethods)) {
			$this->route('base');
		}
		
		// launch method
		if (method_exists($this, $methodName)) {

			// debugging
			if ($this->isDebug($this)) {
				echo 'method -> ' . $methodName;
				echo '<hr>';
			}

			// launch method
			return $this->$methodName();
		}

		// try to launch index
		if (method_exists($this, 'index')) {
			
			// debugging
			if ($this->isDebug($this)) {
				echo 'method -> index';
				echo '<hr>';
			}

			// launch index
			return $this->index();
		}
	}


	/**
	 * gets the class_name_like_this from iterating over
	 * the url segments
	 * @return bool|string
	 */
	public function getClassName()
	{
		$classWords = array('controller');
		for ($index = 0; $index <= $this->getUrlKey(); $index++) { 
			if (! $urlSegment = $this->config->getUrl($index)) {
				return;
			}
			$classWords[] = $urlSegment;
		}
		return implode('_', $classWords);
	}


	/**
	 * sets the current url key
	 * @param int $key 
	 */
	public function setUrlKey($key)
	{
		$this->urlKey = $key;
	}


	/**
	 * adds 1 to the url key ready for the next controller
	 * @return null 
	 */					
	public function incrementUrlKey()
	{
		$currentUrlKey = $this->getUrlKey();
		$this->setUrlKey($currentUrlKey + 1);
	}


	/**
	 * @return int 
	 */
	public function getUrlKey()
	{
		return $this->urlKey;
	}


	public function initialise() {
		if ($this->isComingSoon()) {
			if ($this->config->getUrl(0)) {
				$this->route('base');
			}
			$this->view->getTemplate('coming-soon');
		}

		// main navigation
		$viewHeader = new view_header($this);
		$this->view->setObject('mainMenu', $viewHeader->getMainMenu());
	}


	public function index() {
		$cache = new cache($this);

		// latest 3 posts
		if ($cache->read('home-latest-posts')) {
			$this->view->setObject('contents', $cache->getData());
		} else {
			$modelContent = new model_content($this);
			$modelContent->read(array(
				'where' => array(
					'type' => 'post'
				),
				'limit' => array(0, 3),
				'order_by' => 'time_published desc'
			));
			$modelContent->bindMeta('media');
			$modelContent->bindMeta('tag');
			$this->view->setObject('contents', $modelContent->getData());
			$cache->create($modelContent->getData());
		}
		$this->view->getTemplate('home');
	}


	public function search() {
		if (! array_key_exists('query', $_GET)) {
			$this->route('base');
		}
		$query = $_GET['query'];
		$query = htmlspecialchars($query);
		if (! $query) {
			$this->route('base');
		}
		$modelContent = new model_content($this);
		$modelContent->readSearch($query);
		$this->view
			->setObject('query', $query)
			->setObject('contents', $modelContent)
			->getTemplate('search');
	}


	public function page() {
		if (! $this->config->getUrl(1)) {
			$this->route('base');
		}
		$modelContent = new model_content($this);
		if (! $modelContent->read(array(
			'where' => array(
				'slug' => $this->config->getUrl(1),
				'type' => 'page'
			)
		))) {
			echo '<pre>';
			print_r('variable');
			echo '</pre>';
			exit;
			
			$this->route('base');
		}
		$this->view
			->setMeta(array(		
				'title' => $modelContent->getData('title')
			))
			->setObject('contents', $modelContent)
			->renderTemplate('content-single');
	}


	public function sitemapxml() {
		header('Content-Type: application/xml');
		$content = new model_content($this);
		$player = new model_ttplayer($this);
		$team = new model_ttteam($this);
		$fixture = new model_ttfixture($this);
		$division = new model_ttdivision($this);
		$this->view
			->setObject('model_ttfixture', $fixture->readFilled()->getData())
			->setObject('model_ttdivision', $division->read()->getData())
			->setObject('model_ttteam', $team->read()->getData())
			->setObject('model_ttplayer', $player->read()->getData())
			->setObject('model_content_cup', $content->readByType('cup')->getData())
			->setObject('model_content_minutes', $content->readByType('minutes')->getData())
			->setObject('model_content_page', $content->readByType('page')->getData())
			->setObject('model_content_press', $content->readByType('press')->getData())
			->loadJustTemplate('sitemap');
	}
}
