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
	 * needs to be set when getting objects
	 * @var array
	 */
	public $data = array();


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
	 * marks the app as coming soon and covers with a splash screen
	 * ?preview=true will set session to override this
	 * @var boolean
	 */
	public $comingSoon = false;


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
		if ((gettype($keyName) == 'string')) {

			// array
			if (is_array($objectOrArray)) {
				$this->objects[$keyName] = $objectOrArray;
				return $this;
			}

			// object
			if (method_exists($objectOrArray, 'getData')) {
				$objectOrArray = $objectOrArray->getData();
			}
			$this->objects[$keyName] = $objectOrArray;
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


	public function getUrlCeil()
	{
		$url = $this->config->getUrl('path');
		end($url);
		$urlKey = key($url);
		return ($urlKey ? $urlKey : 0);
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
	public function initiateUrl() {

		// base vars
		$urlParts = array();
		$serverHost = $_SERVER['HTTP_HOST'];
		$serverRequest = $_SERVER['REQUEST_URI'];
		$serverScript = $_SERVER['SCRIPT_NAME'];
		$scheme = 'http://';

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
	 * sets the identity property manually
	 * or get the class name and turn_into_this format
	 */
	public function setIdentity($identity)
	{
		if ($identity) {
			$this->identity = $identity;
			return $this;
		}
		$className = get_class($this);
		$className = explode('_', $className);
		array_shift($className);

		// catching classes like 'Session' and 'Model'
		if (! $className) {
			return $this->identity = '';
		}
		$className = implode('_', $className);
		$this->identity = strtolower($className);
		return $this;
	}


	/**
	 * Set data array
	 */
	public function setData($value = false)
	{		
		return $this->data = $value;
	}


	public function getData($key = '')
	{		
		if ($key) {
			if (array_key_exists($key, $this->data)) {
				return $this->data[$key];
			}
			return;
		}
		return $this->data;
	}	


	/**
	 * retrieves the first row of data, if there is any
	 * @return object, array, bool       
	 */
	public function getDataFirst()
	{
		$data = $this->getData();
		if (! $data) {
			return;
		}
		return reset($data);
	}


	/**
	 * friendly url building
	 * @param  string $value 
	 * @return string        one you can be friends with
	 */
	public function urlFriendly($value = null)
	{
	
		// everything to lower and no spaces begin or end
		$value = strtolower(trim($value));
		
		// adding - for spaces and union characters
		$find = array(' ', '&', '\r\n', '\n', '+',',');
		$value = str_replace ($find, '-', $value);
		
		//delete and replace rest of special chars
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$value = preg_replace ($find, $repl, $value);
		
		//return the friendly str
		return $value; 	
	}


	/**
	 * upgraded get url method, allows unlimited segments
	 * friendly helps out with slashes and making things safe
	 * @param  array   $segments      each/segment/
	 * @return string                 the url
	 */
	public function buildUrl($segments = array(), $friendly = true) {
		$finalUrl = $this->config->getUrl('base');
		foreach ($segments as $segment) {
			if ($friendly) {
				$segment = $this->urlFriendly($segment);
			}
			$finalUrl .= $segment . ($friendly ? '/' : '');
		}
		return $finalUrl;
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


	/**
	 * debug mode is set
	 * @return boolean true if debug equals this class name
	 */
	public function isDebug($theObject)
	{
		if (array_key_exists('debug', $_GET) && strtolower($_GET['debug']) == strtolower(get_class($theObject))) {
			return true;
		}
	}


	/**
	 * converts a delimiter seperated string to camelCase
	 * @param  string $delimiter the seperator for the original string
	 * @param  string $value     
	 * @return string            
	 */	
	public function delimiterToCamel($value, $delimiter = '_')
	{

		// return passed value if no underscores found
		if (strpos($value, $delimiter) === false) {
			return $value;
		}

		// initiate for concatenation
		$newValue = '';

		// mashes it together with each word as uppercase first
		foreach (explode($delimiter, $value) as $value) {
			$newValue .= ucfirst($value);
		}

		// always returns a camelcase
		return lcfirst($newValue);
	}


	public function isComingSoon()
	{
		if (! $this->comingSoon) {
			return;
		}
		$sessionPreview = new session_preview($this->database, $this->config);
		if (array_key_exists('preview', $_GET)) {
			$sessionPreview->setData(true);
		}
		return ! $sessionPreview->getData();
	}


	public function phpSettings()
	{

		// enable errors, which a custom error handler is set for later
		ini_set('display_errors',true);	

		// set time zone to uk 
		ini_set('date.timezone', "Europe/London");

		// keep processing after user disconnect (for cron)
		ignore_user_abort(true);

		return $this;
	}


	public function convertArrayToObject($array)
	{
		$object = new StdClass();
		foreach ($array as $key => $value) {
			$object->$key = $value;
		}
		return $object;
	}
}
