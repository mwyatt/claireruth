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

		// start session if not already
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}

		// initial setup of session data
		$this->setupData();

		// follow through to core constructor
		parent::__construct($database, $config);
	}


	/**
	 * initialises the class or child classes to have the session data
	 * stored within
	 */
	public function setupData()
	{
		if (array_key_exists($this->getIdentity(), $_SESSION)) {
			echo '<pre>';
			print_r($_SESSION[$this->getIdentity()]);
			echo '</pre>';
			exit;
			
			$this->setData($_SESSION[$this->getIdentity()]);
		}
	}


	/**
	 * sets the session variable and the data property
	 * @param boolean $value [description]
	 */
	public function setData($value = false)
	{
		$_SESSION[$this->getIdentity()] = $value;
		parent::setData($value);
	}


	/**
	 * gets array or sub array, returns and destroys session data
	 * @param  string  $key    
	 * @param  boolean $subKey will be string when used
	 * @return anything          
	 */
	// public function getUnset($key, $subKey = false) {
	// 	if (array_key_exists($key, $_SESSION)) {
	// 		if (! $subKey) {
	// 			$value = $_SESSION[$key];
	// 			unset($_SESSION[$key]);
	// 			return $value;
	// 		}
	// 		if (array_key_exists($subKey, $_SESSION[$key])) {
	// 			$value = $_SESSION[$key][$subKey];
	// 			unset($_SESSION[$key][$subKey]);
	// 			return $value;
	// 		}
	// 	}
	// 	return false;
	// }


	// public function set($key, $keyTwo, $keyThree = false) {
	// 	if ($keyThree) {
	// 		$_SESSION[$key][$keyTwo] = $keyThree;
	// 		return true;
	// 	}
	// 	if ($_SESSION[$key] = $keyTwo)
	// 		return true;
	// 	else
	// 		return false;
	// }


	// public function setIncrement($key, $value) {
	// 	$_SESSION[$key][] = $value;
	// 	return $this;
	// }


	// public function getData() {		
	// 	return $_SESSION;
	// }	
}
