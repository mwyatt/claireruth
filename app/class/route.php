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
	 * plan for any incoming url, helps decide which controller to load
	 * $object->foo/bar = controller_foo_bar
	 * @var object
	 */
	public $map;


	/**
	 * compare the current url path to a site specific map of routes
	 * if one is found it is loaded up. once its processes are complete
	 * it is then rendered
	 */
	public function load()
	{

		// cache key variables
		$map = $this->getMap();
		$url = $this->getUrl();
		$path = $url->getPathString();

		// find a matching map property
		$current = 'controller_index';
		foreach ($this->getMap() as $mapPath => $class) {
			if (strpos($path, $mapPath) !== 0) {
				continue;
			}
			$current = $class;
		}

// echo '<pre>';
// print_r($current);
// echo '</pre>';
// exit;

		// does the class exist?
		if (! class_exists($current)) {
			exit('class ' . $current . ' does not exist in the routing map');
		}

		// boot class
		$controller = new $current($this);
		$controller->setView(new view($this));
		$controller->initialise();

		// render the data
		$controller->view->render();
	}
	

	public function setMap($value)
	{
		$this->map = $value;
	}


	public function getMap()
	{
		return $this->map;
	}


	public function loadMap()
	{
		$json = new Json();
		$json->read('route-' . $this->config->data->site /*better way to do that?*/);
		$this->setMap($json->getData());
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
}
