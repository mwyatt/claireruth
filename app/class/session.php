<?php

/**
 * Session
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session extends Config
{


	public function start() {
		session_start();
		return $this;
	}


	/**
	 * master get function for interacting with $_SESSION
	 * @param  string|array  $one      
	 * @param  string $two   
	 * @param  string $three 
	 * @return array|string|int            
	 */
	public function get($one = null, $two = null, $three = null) {	
		if (is_array($one)) {
			if (array_key_exists($two, $one)) {
				return $one[$two];
			}
			return;
		}
		if (array_key_exists($one, $_SESSION)) {
			if (is_array($_SESSION[$one]) && array_key_exists($two, $_SESSION[$one])) {
				if (is_array($_SESSION[$one][$two]) && array_key_exists($three, $_SESSION[$one][$two])) {
					return $_SESSION[$one][$two][$three];
				}
				return $_SESSION[$one][$two];
			}
			return $_SESSION[$one];
		}
		if (! $one && ! $two && ! $three) {
			return $_SESSION;
		}
		return;
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


	public function getPreviousUrl($current) {
		if (! array_key_exists('history', $_SESSION)) {
			$_SESSION['history'][0] = $current;
			$_SESSION['history'][1] = false;
			return;
		} else {
			if ($_SESSION['history'][0]) {
				$_SESSION['history'][1] = $_SESSION['history'][0];
			}
			$_SESSION['history'][0] = $current;
			if ($_SESSION['history'][1]) {
				return $_SESSION['history'][1];
			} else {
				return;
			}
		}
	}


	/**
	 * expires any session variables which require timing, these are
	 * set elsewhere
	 */
	public function refreshExpire() {
		if ($this->get('user', 'expire') && $this->get('user', 'expire') < time()) {
			$this->getUnset('user');
		} else {
			if ($this->get('user')) {
				$this->set('user', 'expire', time() + 600);
			}
		}
		if ($this->get('password_recovery', 'expire') && $this->get('password_recovery', 'expire') < time()) {
			$this->getUnset('password_recovery');
		}
		return $this;
	}


	public function getData() {		
		return $_SESSION = $_SESSION;
	}	


}
