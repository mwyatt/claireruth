<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
 
class Config
{


	/**
	 * see class database
	 * @var object
	 */
	public $database;

	
	/**
	 * see class config
	 * set as false when constructing itself
	 * @var object
	 */
	public $config;


	/**
	 * identifies the the instance of some classes
	 * for example
	 * 		$_SESSION[$keyName]
	 * 		or table name
	 * 	usually parsed from the class title
	 * @var string
	 */
	public $identity = '';


	/**
	 * stores data relating to the class
	 * @var array
	 */
	public $data;


	/**
	 * stores objects like nuts!
	 * @var array
	 */
	public $objects = array();


	/**
	 * stores options data
	 * at the moment this is used as a kind of global config
	 * @var array
	 */
	public $options;


	/**
	 * a variety of url structures are stored here
	 * @var array
	 */
	public $url = array();


	/**
	 * always initiates with the database and config objects
	 * the identity is built mainly automatically
	 * @param object $database 
	 * @param object $config   
	 * @param string $identity   sets the identity dynamically or manually   
	 */
	public function __construct($database = false, $config = false, $identity = '') {
		$this->database = $database;
		$this->config = $config;

		// sets the table name for use with generic methods
		$this->setIdentity($identity);
	}

	
	/**
	 * sets all options taken from the options table
	 * @param array $options 
	 */
	public function setOptions($options) {
		$this->options = $options;
		return $this;
	}


	/**
	 * returns full options array
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}


	/**
	 * returns a specific option
	 * @param  string $key 
	 * @return int|string|bool      
	 */
	public function getOption($key) {
		if (array_key_exists($key, $this->options)) {
			return $this->options[$key];
		}
	}


	/**
	 * returns an object if it has been registered
	 * @param  string $objectTitle 
	 * @return object|bool              
	 */
	public function getObject($objectTitle) {
		$objectTitle = strtolower($objectTitle);
		if (array_key_exists($objectTitle, $this->objects)) {
			return $this->objects[$objectTitle];
		}
		return false;
	}
	
	
	/**
	 * possible to submit:
	 * 'key' => object
	 * 'key' => array
	 * object
	 * @param string|object|array  $keyName       can represent a few data type
	 * @param object|array $objectOrArray often the object to set
	 */
	public function setObject($keyName, $objectOrArray = false) {

		// looking for 'string' => object/array
		if ((gettype($keyName) == 'string') && $objectOrArray) {
			$preparedKeyName = strtolower($keyName);

			// array
			if (is_array($objectOrArray)) {
				$this->objects[$preparedKeyName] = $objectOrArray;
				return $this;
			}

			// object
			if (method_exists($objectOrArray, 'getData')) {
				$objectOrArray = $objectOrArray->getData();
			}
			$this->objects[$preparedKeyName] = $objectOrArray;
			return $this;
		}

		// simple object
		if (is_object($keyName)) {
			$classTitle = get_class($keyName);
			if (method_exists($keyName, 'getData')) {
				$keyName = $keyName->getData();
			}
			$this->objects[strtolower($classTitle)] = $keyName;
			return $this;
		}

		// chain
		return $this;
	}
	
	
	/**
	 * returns url key or path scheme
	 */		
	public function getUrl($key = false) {	
		if (gettype($key) == 'integer') {
			if (array_key_exists('path', $this->url)) {
				if (array_key_exists($key, $this->url['path'])) {
					return $this->url['path'][$key];
				}
			}
			return false;
		}
		if (gettype($key) == 'string') {
			if (array_key_exists($key, $this->url))
				return $this->url[$key];
			return false;				
		}		
		return $this->url;
	}
	
	
	/**
	 * builds various url structures
	 * base
	 * admin
	 * path
	 * current
	 * current_noquery
	 * back (depreciated)
	 * @todo  could be compressed further
	 * @return object
	 */
	public function buildUrl() {

		// server var must be avaliable
		if (! $_SERVER) {
			return;
		}

		// base vars
		$urlParts = array();
		$serverHost = $_SERVER['HTTP_HOST'];
		$serverRequest = $_SERVER['REQUEST_URI'];
		$serverScript = $_SERVER['SCRIPT_NAME'];
		$scheme = 'http://';
		// $schemes = str_replace('p', 's', $scheme);

		// init url
		$url = $scheme . $serverHost . str_replace('.', '', $serverRequest);
		$url = strtolower($url);
		$urlParts = parse_url($url);

		// find out and build path array, 0, 1, 2
		if (array_key_exists('path', $urlParts)) {
			$scriptName = explode('/', strtolower($serverScript));
			array_pop($scriptName); 
			$scriptName = array_filter($scriptName); 
			$scriptName = array_values($scriptName);			
			$urlParts['path'] = explode('/', $urlParts['path']);
			$urlParts['path'] = array_filter($urlParts['path']);
			$urlParts['path'] = array_values($urlParts['path']);
			foreach (array_intersect($scriptName, $urlParts['path']) as $key => $value) {
				unset($urlParts['path'][$key]);
			}
			$urlParts['path'] = array_values($urlParts['path']);		
		}		

		// build base url
		$scriptName = explode('/', strtolower($serverScript));
		array_pop($scriptName);
		$scriptName = array_filter($scriptName); 
		$scriptName = array_values($scriptName);
		$url = $scheme . $urlParts['host'] . '/';
		foreach ($scriptName as $section) {
			$url .= $section . '/';
		}
		$urlParts['base'] = $url;

		// admin
		$urlParts['admin'] = $urlParts['base'] . 'admin/';

		// media
		$urlParts['media'] = $urlParts['base'] . 'media/';

		// current_noquery
		$url = $urlParts['base'];
		foreach ($urlParts['path'] as $segment) {
			$url .= $segment . '/';
		}
		$urlParts['current_noquery'] =  $url;

		// current
		$urlParts['current'] = $scheme . $serverHost . $serverRequest;

		// previous url
		// may be obsolete when the history session function is created
		$url = $urlParts['base'];
		$segments = $urlParts['path'];
		array_pop($segments);
		foreach ($segments as $segment) {
			$url .= $segment . '/';
		}
		$urlParts['back'] = $url;

		// set the url
		$this->setUrl($urlParts);
		return $this;
	}	


	/**
	 * sets the url array
	 * @param string $value 
	 */
	public function setUrl($value = '')
	{
		$this->url = $value;
	}


	/**
	 * attempting to satisfy the quick and sussinct retrival of
	 * array elements, more readable than arraykey checks!
	 * @param  array|string $one an array can be passed
	 * @param  string $two key name
	 * @return array|value       could be array or data!
	 */
	public function get($one = false, $two = false) {	
		if (is_array($one) && $two && array_key_exists($two, $one)) {
			return $one[$two];
		}
		if (! array_key_exists($one, $this->data)) {
			return;
		}
		if ($two) {
			if (! is_array($this->data[$one])) {
				return;
			}
			if (array_key_exists($two, $this->data[$one])) {
				return $this->data[$one][$two];
			} else {
				return;
			}
		}
		if ($one) {
			return $this->data[$one];
		}
	}	


	/**
	 * full list of class methods without excluded keywords
	 * @param  string $className 
	 * @return array|bool            list of methods
	 */
	// public function getClassMethods($className) {
	// 	if (! class_exists($className)) {
	// 		return false;
	// 	}

	// 	// will return unique set of methods

	// 	echo '<pre>';
	// 	print_r(
	// 		array_diff(get_class_methods($className), get_class_methods('controller'), array('initialise', 'index'))
	// 	);
	// 	echo '</pre>';
	// 	exit;

	// 	foreach (get_class_methods($className) as $method) {
	// 		if (! in_array($method, $exclusions)) {
	// 			$methods[] = $method;
	// 		}
	// 	}
	// 	return $methods;
	// }


	/**
	 * bats back a random string, good for unique codes
	 * @param  integer $length how big is the code?
	 * @return string          
	 */
	public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
	

	/**
	 * performs explode() on a string with the given delimiter
	 * and trims all whitespace for the elements
	 */
	public function explodeTrim($str, $delimiter = ',') { 
	    if ( is_string($delimiter) ) { 
	        $str = trim(preg_replace('|\\s*(?:' . preg_quote($delimiter) . ')\\s*|', $delimiter, $str)); 
	        return explode($delimiter, $str); 
	    } 
	    return $str; 
	} 


	/**
	 * simple return of identity
	 * @return string 
	 */
	public function getIdentity()
	{
		return $this->identity;
	}


	/**
	 * sets the identity property
	 */
	public function setIdentity($identity)
	{
		if ($identity) {
			$this->identity = $identity;
		} else {
			$className = get_class($this);
			$className = explode('_', $className);
			array_shift($className);

			// hopefully catching classes like 'Session'
			// and 'Model'
			if (! $className) {
				return $this->identity = '';
			}
			$className = implode('_', $className);
			$this->identity = strtolower($className);
		}
		return $this;
	}


	/**
	 * Set data array
	 */
	public function setData($value = false)
	{		
		return $this->data = $value;
	}


	/**
	 * Get data array or by key
	 * @param  string $key 
	 * @return value|bool       depending upon success
	 */
	public function getData($key = false)
	{		
		if ($key) {
			if (array_key_exists($key, $this->data)) {
				return $this->data[$key];
			} else {
				return false;
			}
		}
		return $this->data;
	}	


	/**
	 * attempts to return the data stored
	 * returns false on failure
	 * must be an array to use current
	 * @todo duplicate array key finding, how to combine?
	 * @return array|bool 
	 */
	public function getDataFirst($key = false)
	{
		if ($data = $this->getData()) {
			if (! is_array($data)) {
				return false;
			}
			if ($key) {
				$data = current($data);
				if (array_key_exists($key, $data)) {
					return $data[$key];
				} else {
					return false;
				}
			}
			return current($data);
		}
		return false;
	}
}
