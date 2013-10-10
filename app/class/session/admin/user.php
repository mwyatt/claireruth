<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session_Admin_User extends Session
{


	public function isLogged()
	{
		return $this->getData();
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
}
