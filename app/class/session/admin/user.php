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
	 * stores user id
	 * @param  object $mold 
	 * @return null       
	 */
	public function login($mold)
	{
		$this->setExpire();
		$this->setDataKey('id', $mold->id);
	}


	/**
	 * checks to see if the session is set
	 * and refreshes the expiry
	 * @todo could make expire a built in session feature
	 * @return bool 
	 */
	public function isLogged()
	{

		// not logged in!
		if (! $this->getData()) {
			return false;
		}

		// provide feedback as to what has happened
		if (! $this->refreshExpire()) {
			$sessionFeedback = new session_feedback($this);
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
