<?php

/**
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
		// session_name('example');
		session_start();
		return $this;
	}


	/**
	 * sets the data property with custom data
	 * or the session array
	 * or a blank session key
	 * @param string|array|bool $data 
	 */
	public function setData($data = false)
	{
		if ($data) {
			return $this->data = $data;
		}
		if ($this->getKeyName()) {
			if (array_key_exists($this->getKeyName(), $_SESSION)) {
				$this->data = $_SESSION[$this->getKeyName()];
			} else {
				$this->data = $_SESSION[$this->getKeyName()] = false;
			}
		}
	}


	public function getData()
	{		
		if (array_key_exists($this->identity, $_SESSION)) {
			return $_SESSION[$this->identity];
		}
		return $_SESSION[$this->identity] = false;
	}	


	/**
	 * gets array or sub array, returns and destroys session data
	 * @param  string  $key    
	 * @param  boolean $subKey will be string when used
	 * @return anything          
	 */
	public function getUnset($key, $subKey = false) {
		if (array_key_exists($key, $_SESSION)) {
			if (! $subKey) {
				$value = $_SESSION[$key];
				unset($_SESSION[$key]);
				return $value;
			}
			if (array_key_exists($subKey, $_SESSION[$key])) {
				$value = $_SESSION[$key][$subKey];
				unset($_SESSION[$key][$subKey]);
				return $value;
			}
		}
		return false;
	}


	public function set($key, $keyTwo, $keyThree = false) {
		if ($keyThree) {
			$_SESSION[$key][$keyTwo] = $keyThree;
			return true;
		}
		if ($_SESSION[$key] = $keyTwo)
			return true;
		else
			return false;
	}


	public function setIncrement($key, $value) {
		$_SESSION[$key][] = $value;
		return $this;
	}


	public function getData() {		
		return $_SESSION;
	}	
}
