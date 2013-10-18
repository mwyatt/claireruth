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
	 * Get all Users and pair with Meta Data
	 */
	public function read() {	

		$sth = $this->database->dbh->query("
			SELECT
				id
				, email
				, level
				, name
				, value
			FROM
				user
			LEFT JOIN
				user_meta
			ON
				user.id = user_meta.user_id
		");
		
		// Process Result Rows
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$this->setRow($row['id'], 'email', $row['email']);
			$this->setRow($row['id'], 'level', $row['level']);
			$this->setRow($row['id'], $row['name'], $row['value']);
		}		
	}	

	
	/**
	 * using the id the session is refreshed with fresh updated data
	 * @param  string $id 
	 */
	public function refreshUserSession($id) {	
		$sth = $this->database->dbh->prepare("	
			select
				user.id
				, user.email
				, user.first_name
				, user.last_name
				, user.date_registered
				, user.level
			from user				
			where user.id = ?
		");		
		$sth->execute(array($id));
		$session = new Session();
		$session->set('user', $sth->fetch(PDO::FETCH_ASSOC));
		$this->setUserExpire();
	}


	/**
	 * using the id the session is refreshed with fresh updated data
	 * @param  string $id 
	 */
	public function readByEmail($email) {	
		$sth = $this->database->dbh->prepare("	
			select
				user.id
				, user.email
				, user.first_name
				, user.last_name
				, user.password
				, user.date_registered
				, user.level
			from user				
			where user.email = ?
		");		
		$sth->execute(array($email));
		$this->setData($sth->fetch(PDO::FETCH_ASSOC));
	}

	/**
	 * login user
	 * @return bool 
	 */
	public function login($email, $password) {	
		$this->readByEmail($email);
		if ($row = $this->getData()) {
			if (crypt($password, $row['password']) == $row['password']) {
				unset($row['password']);
				$session = new Session();
				$session->set('user', $row);
				$this->setUserExpire();
				return true;
			}
		}
		return false;
	}
	
	
	public function passwordRecover($emailAddress) {
		$sth = $this->database->dbh->prepare("	
			select
				user.email
			from user				
			where user.email = ?
		");		
		$sth->execute(array($emailAddress));
		if ($sth->rowCount()) {
			$session = new session();
			$session->set('password_recovery', array(
				'code' => $this->config->generateRandomString()
				, 'email' => $emailAddress
				, 'expire' => time() + 300
			));
			$mail = new mail($this->database, $this->config);
			$mail->send($emailAddress, 'Password recovery', 'password-recovery');
			return true;
		}
		return false;
	}


	public function passwordReset($password) {
		$session = new session();
		if (! strlen($password) > 3) {
			return false;
		}
		$sth = $this->database->dbh->prepare("	
			update user set
				user.password = ?
			where
				user.email = ?
		");	
		$sth->execute(array(
			crypt($password)
			, $session->get('password_recovery', 'email')
		));
		if ($sth->rowCount()) {
			$session = new session();
			$session->getUnset('password_recovery');
			return true;
		}
		return false;
	}


	/**
	 * check the session variable for logged in user
	 */
	public function isLogged() {	
		$session = new Session();
		if ($session->get('user', 'expire') && $session->get('user', 'expire') < time()) {
			$this->logout();
			$this->session->set('feedback', 'Logged out due to inactivity.');
			return false;
		}
		return $session->get('user');
	}
	
	
	public function logout() {	
		$session = new Session();
		$session->getUnset('user');
	}
	
	
	public function setSession() {	
		$session = new Session();
		$session->getUnset('user');
		$session->set('user', $this->getData());
	}
	
	public function get($one = null, $two = null, $three = null) {	
		$session = new Session();
		if ($one) {
			return $session->get('user', $one);
		}
		return $session->get('user');
	}


	public function getPermission($level) {
		$accessTo = array();
		if ($level == 10) {
			return false;
		}
		if ($level == 1) {
			$accessTo[] = 'player';
		}
		if ($level == 2) {
			$accessTo[] = 'minutes';
			$accessTo[] = 'cup';
			$accessTo[] = 'gallery';
		}
		if ($level == 3) {
			$accessTo[] = 'press';
			$accessTo[] = 'cup';
		}
		if ($level == 4) {
			$accessTo[] = 'player';
			$accessTo[] = 'team';
			$accessTo[] = 'fixture';
		}
		return $accessTo;
	}


	public function permission() {
		// if (array_key_exists('form_login', $_POST)) {
		// 	if ($this->get('email') == 'martin.wyatt@gmail.com') {
		// 		$this->config->getObject('route')->home('admin/');
		// 	}
		// 	if ($this->get('email') == 'Realbluesman@tiscali.co.uk') {
		// 		$this->config->getObject('route')->home('admin/league/fixture/fulfill/');
		// 	}
		// 	if ($this->get('email') == 'hepworth_neil@hotmail.com') {
		// 		$this->config->getObject('route')->home('admin/content/press/');
		// 	}
		// 	if ($this->get('email') == 'gsaggers6@aol.com') {
		// 		$this->config->getObject('route')->home('admin/league/player/');
		// 	}
		// 	if ($this->get('email') == 'henryrawcliffe@sky.com') {
		// 		$this->config->getObject('route')->home('admin/content/minutes/');
		// 	}
		// 	$this->config->getObject('route')->home('admin/');
		// }
		// $feedback = 'Access denied. Please contact the administrator if you require access <a href="mailto:martin.wyatt@gmail.com">martin.wyatt@gmail.com</a>';
		// if ($this->get('email') == 'Realbluesman@tiscali.co.uk') {
		// 	if ($this->config->getUrl(1) != 'league') {
		// 		$this->session->set('feedback', $feedback);
		// 		$this->config->getObject('route')->home('admin/league/');
		// 	}
		// }
		// if ($this->get('email') == 'hepworth_neil@hotmail.com') {
		// 	if ($this->config->getUrl(2) != 'press') {
		// 		$this->session->set('feedback', $feedback);
		// 		$this->config->getObject('route')->home('admin/content/press/');
		// 	}
		// }
		// if ($this->get('email') == 'gsaggers6@aol.com') {
		// 	if ($this->config->getUrl(2) != 'player') {
		// 		$this->session->set('feedback', $feedback);
		// 		$this->config->getObject('route')->home('admin/league/player/');
		// 	}
		// }
		// if ($this->get('email') == 'henryrawcliffe@sky.com') {
		// 	if ($this->config->getUrl(2) != 'minutes') {
		// 		$this->session->set('feedback', $feedback);
		// 		$this->config->getObject('route')->home('admin/content/minutes/');
		// 	}
		// }
	}


	public function readById($id)
	{	
		$sth = $this->database->dbh->prepare("	
			select
				user.id
				, user.email
				, user.first_name
				, user.last_name
				, unix_timestamp(user.date_registered) as date_registered
				, user.level
			from user				
			where user.id = ?
		");		
		$sth->execute(array($id));
		if ($this->data = $sth->fetch(PDO::FETCH_ASSOC)) {
			return true;
		}
		return false;
	}


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
