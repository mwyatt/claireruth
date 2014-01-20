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
	public $debug = 0;


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
		parent::__construct($database, $config);
		if ($controller) {
			$this->view = $controller->view;
			$this->setUrlKey($controller->getUrlKey());
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
	public function loadClass() {
		if ($this->isDebug($this)) {
			echo 'urlkey - ' . $this->getUrlKey() . "<br>";
			echo 'loadingclass - ' . $this->getClassName() . "<br>";
		}

		$className = $this->getClassName();

		// validity of class
		if (! class_exists($className)) {
			return;
		}

		// launch class and method
		$controller = new $className($this, $this->database, $this->config);
		return $controller->loadMethod();
	}


	/**
	 * loads up controller method
	 * 		initialise
	 * 		methodName || index
	 */
	public function loadMethod() {

		// 1. always initialise the class
		if (method_exists($this, 'initialise')) {
			if ($this->isDebug($this)) {
				echo 'initialising - ' . $this->getClassName() . "<br>";
			}
			$this->initialise();
		}

		// get static method name
		$methodName = $this->config->getUrl($this->getUrlKey());

		// check the method is illegal
		if (in_array($methodName, $this->illegalMethods)) {
			if ($this->isDebug($this)) {
				echo 'illegal method - ' . $methodName . "<br>";
			}
			return;
		}

		// move forwards
		$this->incrementUrlKey();
		
		// 2. next class exists
		if ($this->loadClass()) {
			return;
		}

		// get static method name again
		$methodName = $this->config->getUrl($this->getUrlKey());

		// 3. boot method
		if (method_exists($this, $methodName)) {
			if ($this->isDebug($this)) {
				echo 'loadingmethod - ' . $methodName . "<br>";
			}
			$this->$methodName();
			return;
		}

		// 4. boot index
		if (method_exists($this, 'index')) {
			if ($this->isDebug($this)) {
				echo 'loadingindex - ' . $this->getClassName() . "<br>";
			}
			$this->index();
			return;
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
