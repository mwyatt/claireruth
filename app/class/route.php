<?php

/**
 * base functionality for all controllers
 * 
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Route extends System
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
			$this->setView($system->getView());
			$this->setUrlKey($system->getUrlKey());
		} else {
			$this->setView(new view($system));
		}
	}


	/**
	 * @param object $value 
	 */
	public function setView($value)
	{
		$this->view = $value;
	}


	/**
	 * get view
	 * @return object 
	 */
	public function getView()
	{		
		return $this->view;
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
		$methodName = $this->url->getPathPart($this->getUrlKey());

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
		$classWords = array(
			'controller',
			'site',
			$this->config->getDataSite()
		);
		for ($index = 0; $index <= $this->getUrlKey(); $index++) { 
			if (! $urlSegment = $this->url->getPathPart($index)) {
				return;
			}
			$classWords[] = $urlSegment;
		}
		echo '<pre>';
		print_r(implode('_', $classWords));
		echo '</pre>';
		exit;
		
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
}
