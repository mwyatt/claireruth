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
	 * boots up the session
	 * @return object 
	 */
	public function start() {
		session_start();
		return $this;
	}


	/**
	 * sets the session variable and the data property
	 * @param boolean $value [description]
	 */
	public function setData($value = false)
	{
		$_SESSION[$this->getKeyName()] = $value;
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
