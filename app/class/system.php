<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class System
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
	 * a variety of url structures are stored here
	 * @var array
	 */
	public $url = array();

	
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
	 * marks the app as coming soon and covers with a splash screen
	 * ?preview=true will set session to override this
	 * @var boolean
	 */
	public $comingSoon = false;


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
}
