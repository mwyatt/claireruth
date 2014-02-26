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
	public function __construct($controller = false, $database = false, $config = false) {

		// construct in default fashion
		parent::__construct($database, $config);

		// copy passed controllers view to this new controller
		// copy url key
		if ($controller) {
			$this->view = $controller->view;
			$this->setUrlKey($controller->getUrlKey());
		}

		// first controller needs a blank view
		if (! $controller) {
			$this->view = new View($this->database, $this->config);
		}
	}


	/**
	 * initialises and attempts to load next class
	 * @return bool 
	 */
	public function loadClass() {

		// initialise each class
		$this->initialise();
		
		// debugging
		if ($this->isDebug($this)) {
			echo 'initialised class -> ' . $this->getClassName();
			echo '<hr>';
			echo 'loading class -> ' . $this->getClassName();
			echo '<hr>';
		}

		// does the next class exist?
		if (! class_exists($nextClassName = $this->getClassName())) {
	
			// debugging
			if ($this->isDebug($this)) {
				echo 'class -> ' . $this->getClassName() . ' does not exist';
				echo '<hr>';
			}
			return;
		}


		// move pointer forwards
		$this->incrementUrlKey();

		// instantiate class
		$controller = new $nextClassName($this, $this->database, $this->config);

		// attempt to load next class
		if ($controller->loadClass()) {
			return;
		}

		// debugging
		if ($this->isDebug($this)) {
			echo 'next controller load failed -> launching method';
			echo '<hr>';
		}

		// load method
		$this->loadMethod();

		// print view data
		echo '<pre>';
		print_r($this->view);
		echo '</pre>';
		exit;
		
	}


	public function loadMethod() {

		// debugging
		if ($this->isDebug($this)) {
			echo 'class load failed, now loading method from -> ' . $this->getClassName();
			echo '<hr>';
		}

		// set method name
		$methodName = $this->config->getUrl($this->getUrlKey());

		// check the method is legal, if not always route home
		if (in_array($methodName, $this->illegalMethods)) {
			$this->route('base');
		}
		
		// launch method
		if (method_exists($this, $methodName)) {

			// debugging
			if ($this->isDebug($this)) {
				echo 'loading method -> ' . $this->getClassName() . ' -> ' . $methodName;
				echo '<hr>';
			}

			// launch method
			return $this->$methodName();
		}

		// try to launch index
		if (method_exists($this, 'index')) {
			
			// debugging
			if ($this->isDebug($this)) {
				echo 'loading index -> ' . $this->getClassName();
				echo '<hr>';
			}

			// launch index
			return $this->index();
		}
	}


	/**
	 * moves the script to another url, could be full or
	 * looking for a scheme in the url array
	 * @param  string  $scheme see class 'Config'
	 * @param  string $path   extension of the base action
	 */
	public function route($schemeOrFullPath = '', $extension = false) {		
		if ($this->config->getUrl($schemeOrFullPath)) {
			$url = $this->config->getUrl($schemeOrFullPath);
		} else {
			$url = $schemeOrFullPath;
		}
		header("Location: " . $url . $extension);
		exit;
	}


	/**
	 * gets the class_name_like_this from iterating over
	 * the url segments
	 * @return string 
	 */
	public function getClassName()
	{
		$className = 'controller_';
		for ($index = 0; $index <= $this->getUrlKey(); $index++) { 
			$urlSegment = $this->config->getUrl($index);

			// if second class and segment does not exist,
			// create false class name
			if ($index && ! $urlSegment) {
				$urlSegment = 'end';
			}
			$className .= $urlSegment . '_';
		}
		return $className = rtrim($className, '_');
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
			$this->view->loadTemplate('coming-soon');
		}
		if (array_key_exists('search', $_GET)) {
			$this->search($_GET['search']);
		}
	}


	public function index() {
		$modelContent = new model_content($this->database, $this->config);
		$modelContent->read(array(
			'where' => array(
				'type' => 'post'
			),
			'limit' => array(0, 3)
		));
		$this->view
			->setObject('contents', $modelContent)
			->loadTemplate('home');
	}


	public function search($query) {
		$query = htmlspecialchars($query);
		if (! $query) {
			$this->route('base');
		}
		$search = new model_search($this->database, $this->config);
		$search->read($query);
		$this->view
			->setObject('search_query', $query)
			->setObject($search)
			->loadTemplate('search');
	}


	public function page() {
		if ($this->config->getUrl(1)) {
			$page = new model_content($this->database, $this->config);
			if (! $page->readByTitle(array($this->config->getUrl(1)))) {
				$this->view->loadTemplate('404');
			}
			$this->view
				->setMeta(array(		
					'title' => $page->getData('title')
				))
				->setObject($page)
				->loadTemplate('content-single');
		}
		$this->route('base');
	}


	public function sitemapxml() {
		header('Content-Type: application/xml');
		$content = new model_content($this->database, $this->config);
		$player = new model_ttplayer($this->database, $this->config);
		$team = new model_ttteam($this->database, $this->config);
		$fixture = new model_ttfixture($this->database, $this->config);
		$division = new model_ttdivision($this->database, $this->config);
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
