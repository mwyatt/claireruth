<?php

/**
 * Manage User Data, Authentication
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_User extends Model
{


	/**
	 * check the password
	 * @param  boolean $password [description]
	 * @return [type]            [description]
	 */
	public function validatePassword($email = false, $password = false) {	
		if ($this->read("user.id, user.password", array('email' => $email))) {
			if (crypt($password, $this->getDataFirst('password')) == $this->getDataFirst('password')) {
				return true;
			}
		}
	}


	// /**
	//  * using the id the session is refreshed with fresh updated data
	//  * @param  string $id 
	//  */
	// public function refreshUserSession($id) {	
	// 	$sth = $this->database->dbh->prepare("	
	// 		select
	// 			user.id
	// 			, user.email
	// 			, user.first_name
	// 			, user.last_name
	// 			, user.date_registered
	// 			, user.level
	// 		from user				
	// 		where user.id = ?
	// 	");		
	// 	$sth->execute(array($id));
	// 	$session = new Session();
	// 	$session->set('user', $sth->fetch(PDO::FETCH_ASSOC));
	// 	$this->setUserExpire();
	// }


	// /**
	//  * using the id the session is refreshed with fresh updated data
	//  * @param  string $id 
	//  */
	// public function readByEmail($email) {	
	// 	$sth = $this->database->dbh->prepare("	
	// 		select
	// 			user.id
	// 			, user.email
	// 			, user.first_name
	// 			, user.last_name
	// 			, user.password
	// 			, user.date_registered
	// 			, user.level
	// 		from user				
	// 		where user.email = ?
	// 	");		
	// 	$sth->execute(array($email));
	// 	$this->setData($sth->fetch(PDO::FETCH_ASSOC));
	// }



	
	
	// public function passwordRecover($emailAddress) {
	// 	$sth = $this->database->dbh->prepare("	
	// 		select
	// 			user.email
	// 		from user				
	// 		where user.email = ?
	// 	");		
	// 	$sth->execute(array($emailAddress));
	// 	if ($sth->rowCount()) {
	// 		$session = new session();
	// 		$session->set('password_recovery', array(
	// 			'code' => $this->config->generateRandomString()
	// 			, 'email' => $emailAddress
	// 			, 'expire' => time() + 300
	// 		));
	// 		$mail = new mail($this->database, $this->config);
	// 		$mail->send($emailAddress, 'Password recovery', 'password-recovery');
	// 		return true;
	// 	}
	// 	return false;
	// }


	// public function passwordReset($password) {
	// 	$session = new session();
	// 	if (! strlen($password) > 3) {
	// 		return false;
	// 	}
	// 	$sth = $this->database->dbh->prepare("	
	// 		update user set
	// 			user.password = ?
	// 		where
	// 			user.email = ?
	// 	");	
	// 	$sth->execute(array(
	// 		crypt($password)
	// 		, $session->get('password_recovery', 'email')
	// 	));
	// 	if ($sth->rowCount()) {
	// 		$session = new session();
	// 		$session->getUnset('password_recovery');
	// 		return true;
	// 	}
	// 	return false;
	// }


	/**
	 * check the session variable for logged in user
	 */
	// public function isLogged() {	
	// 	$session = new Session();
	// 	if ($session->get('user', 'expire') && $session->get('user', 'expire') < time()) {
	// 		$this->logout();
	// 		$this->session->set('feedback', 'Logged out due to inactivity.');
	// 		return false;
	// 	}
	// 	return $session->get('user');
	// }
	
	
	// public function logout() {	
	// 	$session = new Session();
	// 	$session->getUnset('user');
	// }
	
	
	// public function setSession() {	
	// 	$session = new Session();
	// 	$session->getUnset('user');
	// 	$session->set('user', $this->getData());
	// }
	
	// public function get($one = null, $two = null, $three = null) {	
	// 	$session = new Session();
	// 	if ($one) {
	// 		return $session->get('user', $one);
	// 	}
	// 	return $session->get('user');
	// }


	public function updateById($id) {
		$sth = $this->database->dbh->prepare("
			update user set
				user.email = ?
				, user.first_name = ?
				, user.last_name = ?
			where
				id = ?
		");				
		$sth->execute(array(
			$_POST['email']
			, $_POST['first_name']
			, $_POST['last_name']
			, $id
		));		
		if (array_key_exists('password', $_POST) && $_POST['password']) {
			$sth = $this->database->dbh->prepare("
				update user set
					user.password = ?
				where
					id = ?
			");				
			$sth->execute(array(
				crypt($_POST['password'])
				, $id
			));		
		}
		$this->refreshUserSession($id);
		$this->session->set('feedback', 'Your profile was updated.');
		return $sth->rowCount();
	}
	
}
