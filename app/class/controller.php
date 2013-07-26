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
	 * the core path for the controllers
	 * @var string
	 */
	public $path = 'app/controller/';


	/**
	 * cache object which will allow the controller to push out cached files
	 * speeding up some model intensive pages
	 * @var object
	 */
	public $cache;


	/**
	 * view object which will allow the controller to move onto the view stage
	 * @var object
	 */
	public $view;


	/**
	 * prepends the constant
	 */
	public function __construct() {
		$this->path = BASE_PATH . $this->path;
	}


	/**
	 * loads up controller method, otherwise use default method 'index'
	 * @param  string $method 
	 * @return null         
	 */
	protected function loadMethod($method) {
		$words = explode('-', $method);
		$method = '';
		foreach ($words as $word) {
			$method .= ucfirst($word);
		}
		$method = lcfirst($method);
		if ($method == '__construct') {
			return $this->index();
		}
		if (method_exists($this, $method)) {
			return $this->$method();
		}
		if (method_exists($this, 'index')) {
			return $this->index();
		}
		return false;
	}


	/**
	 * loads a controller based on the properties given
	 * @param  array $names    the url segments to find the controller
	 * @param  string $method   url portion to isse next command
	 * @param  object $view     the previous view to retain stored object data
	 * @param  object $database 
	 * @param  object $config   
	 * @return null           the controller is loaded into this scope
	 */
	public function load($names, $method, $view, $database, $config)	{
		$path = $this->path;
		$this->session = new Session();
		$this->cache = new Cache(false);
		$this->database = $database;
		$this->config = $config;
		$this->view = $view;
		if (! $view) {
			$this->view = new View($this->database, $this->config);
		}
		if (method_exists($this, 'initialise')) {
			$this->initialise();
		}
		$controllerName = 'Controller_';
		if (is_array($names)) {
			foreach ($names as $name) {
				$path .= strtolower($name) . '/';
				$controllerName .= ucfirst($name) . '_';
			}
		}
		$controllerName = rtrim($controllerName, '_');
		$path = rtrim($path, '/') . '.php';
		// echo $controllerName;
		if (is_file($path)) {
			$controller = new $controllerName();
			$controller->load(false, false, $this->view, $this->database, $this->config);
			$controller->loadMethod($method);
			return true;
		}
		return false;
	}


	/**
	 * moves the script to another url, possibly replaces class 'Route'
	 * @param  string  $scheme see class 'Config'
	 * @param  string $path   extension of the base action
	 * @return null          
	 */
	protected function route($scheme, $path = false) {		
		header("Location: " . $this->config->getUrl($scheme) . $path);
		exit;
	}


	/**
	 * handy for pulling ids from various urls, e.g. martin-wyatt-22
	 * @param  string $segment url segment
	 * @return string          the id
	 */
	protected function getId($segment) {
		$segments = explode('-', $segment);
		return end($segments);
	}


}
