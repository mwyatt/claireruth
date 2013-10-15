<?php

/**
 * session object creates a layer between the $_SESSION variable to
 * help with management of it
 *
 * the session data is only modified when setting the data
 * when getting data simply use getData
 *
 * how to set the data once you have finished with the class? desctuct?
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session extends Config
{


	/**
	 * extends the normal constructor to set the session data
	 */
	public function __construct($database = false, $config = false, $identity = '') {

		// follow through to core constructor
		parent::__construct($database, $config);

		// initial setup of session data
		$this->refreshData();
	}


	/**
	 * initialises the class or child classes to have the session data
	 * stored within
	 */
	public function refreshData($value = false)
	{
		if (array_key_exists($this->getIdentity(), $_SESSION)) {
			$this->setData($_SESSION[$this->getIdentity()]);
		}
	}


	/**
	 * sets the session variable with information and updates
	 * the data packet
	 * @param string $key   
	 * @param any $value 
	 */
	public function setData($key, $value)
	{
		$_SESSION[$this->getIdentity()][$key] = $value;
		$this->refreshData();
	}


	public function getUnset($key) {
		if (array_key_exists($key, $_SESSION[$this->getIdentity()])) {
			$data = $_SESSION[$key];
			unset($_SESSION[$key]);
			return $data;
		}
		return $data;
	}


	public function delete($key = false) {
		if (! $key) {
			unset($_SESSION[$this->getIdentity()]);
		}
	}
}
