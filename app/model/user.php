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
		if ($this->lazyRead("user.id, user.password", array('email' => $email))) {
			if (crypt($password, $this->getDataFirst('password')) == $this->getDataFirst('password')) {
				return true;
			}
		}
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
		return $sth->rowCount();
	}
}
