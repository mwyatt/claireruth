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
	 * @var boolean
	 */
	public $debug = 1;


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
	 * @var integer
	 */
	public $urlKey = 0;


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
	 * extends default construct to add a view object
	 * @param object $database 
	 * @param object $config   
	 */
	public function __construct($controller = false, $database = false, $config = false) {
		parent::__construct($database, $config);

		// if config object
		if (is_object($controller) && method_exists($controller, 'setUrlKey')) {
			$this->setUrlKey($controller->getUrlKey());
		}

		// setup view
		if (is_object($controller) && property_exists($controller, 'view')) {
			$this->view = $controller->view;
		} else {
			$this->view = new View($database, $config);
		}
	}


	/**
	 * looks at segments and makes a class name
	 * e.g. controller_front
	 * if it exists it creates it and loads the first method
	 * e.g. post
	 * it will refer to the current url position..
	 * @param  object $controller the previous controller
	 */
	public function load() {
		$className = $this->getClassName();

		// validity of class
		if (! class_exists($className)) {
			return;
		}

		// launch class and method
		$controller = new $className($this, $this->database, $this->config);
		$controller->loadMethod();
	}


	/**
	 * loads up controller method
	 * 		initialise
	 * 		methodName || index
	 */
	public function loadMethod() {
		$methodName = $this->config->getUrl($this->getUrlKey());
		$currentUrlKey = $this->getUrlKey();

		if ($this->debug) {
			echo 'loadingclass - ' . $this->getClassName() . "<br>";
			echo 'loadingMethod - ' . $methodName . "<br>";
		}

		// always initialise each segment if required
		if (method_exists($this, 'initialise')) {
			$this->initialise();

			if ($this->debug) {
				echo 'initialising - ' . $this->getClassName() . "<br>";
			}
		}

		// check the method is illegal
		if (in_array($methodName, $this->illegalMethods)) {
			return;
		}

		// boot method || index
		if (method_exists($this, $methodName)) {
			$this->incrementUrlKey();

			if ($this->debug) {
				echo 'loadingmethod - ' . $methodName . "<br>";
			}

			$this->$methodName();
		} elseif (method_exists($this, 'index')) {

			if ($this->debug) {
				echo 'loadingindex - ' . $methodName . "<br>";
			}

			$this->index();
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
			$className .= $this->config->getUrl($index) . '_';
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
		// $menu = new model_menu($this->database, $this->config);
		// $this->view->setObject($menu);
		if (array_key_exists('search', $_GET)) {
			$this->search($_GET['search']);
		}
	}


	public function index() {
		$mainContent = new model_content($this->database, $this->config);
		$mainContent->read('post', array(0, 3));
		$this->view
			->setObject($mainContent)
			->loadTemplate('home');
	}


	public function admin()
	{
		$this->load();
	}


	public function ajax() {
		$this->load();
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


	public function month() {
		$this->load();
	}


	public function tag() {
		$this->load();
	}


	public function post() {
		$this->load();
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
				->loadTemplate('page');
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
