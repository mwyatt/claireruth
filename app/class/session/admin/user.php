<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session_Admin_User extends Session
{


	/**
	 * builds the session data
	 * @param  int $userId 
	 */
	public function login($userId)
	{
		$this->setExpire();
		$this->setDataKey('id', $userId);
	}


	/**
	 * checks to see if the session is set
	 * and refreshes the expiry
	 * @todo could make expire a built in session feature
	 * @return bool 
	 */
	public function isLogged()
	{

		// provide feedback as to what has happened
		if (! $this->refreshExpire()) {
			$sessionFeedback = new session_feedback($this->database, $this->config);
			$sessionFeedback->set('You have been logged out due to inactivity');
			return;
		}

		// logged in
		if ($this->getData()) {
			return true;
		}
	}


	/**
	 * refreshes the expiry time
	 * @return bool 
	 */
	public function refreshExpire()
	{

		// set expire again if not expired
		if ($this->getData('expire') > time()) {
			return $this->setExpire();
		}

		// delete session it has expired!
		$this->delete();
	}


	/**
	 * sets the expire time, 1 hour after last check!
	 */
	public function setExpire()
	{
		return $this->setDataKey('expire', time() + $this->getTime('hour'));
	}
}
