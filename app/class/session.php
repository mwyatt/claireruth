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


	/**
	 * identifies the array key e.g.
	 * $_SESSION[$keyName]
	 * @var string
	 */
	public $keyName = '';


	/**
	 * stores the keyname data
	 * @var array|bool|string
	 */
	public $data;


	/**
	 * always initiates with the session, database and config
	 * @param object $database 
	 * @param object $config   
	 */
	public function __construct($database, $config, $keyName = '') {
		$this->database = $database;
		$this->config = $config;
		$this->setKeyName($keyName);
	}


	/**
	 * boots up the session
	 * @return object 
	 */
	public function start() {
		session_name('example');
		session_start();
		return $this;
	}


	/**
	 * sets the keyName property
	 */
	public function setKeyName($keyName)
	{
		if ($keyName) {
			$this->keyName = $keyName;
		} else {
			$className = get_class($this);
			$className = str_replace('Session_', '', $className);
			$this->keyName = strtolower($className);
		}
		return $this;
	}


	/**
	 * gets the keyName property
	 */
	public function getKeyName()
	{
		return $this->keyName;
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


	/**
	 * Get data array or by key
	 * @param  string $key 
	 * @return value|bool       depending upon success
	 */
	public function getData($key = '')
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
			// $this->getUnset('user');
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
