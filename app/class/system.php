<?php

/**
 * boilerplate for all objects in the app. responsible for how each object
 * should be implemented
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class System
{


	/**
	 * @var object
	 */
	public $database;


	/**
	 * @var object
	 */
	public $view;

	
	/**
	 * @var object
	 */
	public $config;


	/**
	 * @var object
	 */
	public $url;

	
	/**
	 * common storage property, used for many things
	 * @var array
	 */
	public $data = array();


	/**
	 * used to attach objects to the class instance
	 * @var array
	 */
	public $objects = array();


	/**
	 * get database
	 * @return object 
	 */
	public function getDatabase()
	{		
		return $this->Database;
	}


	/**
	 * set database
	 * @param object $value 
	 */
	public function setDatabase($value)
	{		
		return $this->database = $value;
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
	 * set view
	 * @param object $value 
	 */
	public function setView($value)
	{		
		return $this->view = $value;
	}


	/**
	 * get url
	 * @return object 
	 */
	public function getUrl()
	{		
		return $this->url;
	}


	/**
	 * set url
	 * @param object $value 
	 */
	public function setUrl($value)
	{		
		return $this->url = $value;
	}


	/**
	 * get data
	 * @param  string $key 
	 * @return array      
	 */
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
	 * set data
	 */
	public function setData($value = false)
	{		
		return $this->data = $value;
	}


	/**
	 * common settings for a php app
	 * @return object chain
	 */
	public function setPhpSettings()
	{

		// enable errors, which a custom error handler is set for later
		ini_set('display_errors', true);	

		// set time zone to uk 
		ini_set('date.timezone', "Europe/London");

		// keep processing after user disconnect (for cron)
		ignore_user_abort(true);

		return $this;
	}


	/**
	 * check multiple array keys exist in an array
	 * @param  array $keys  
	 * @param  array $array 
	 * @return bool        
	 */
	public function arrayKeyExists($keys, $array)
	{
		foreach ($keys as $key) {
			if (array_key_exists($key, $array)) {
				continue;
			}
			return;
		}
		return true;
	}
}
