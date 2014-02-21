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

	
	public $fields = array(
		'id'
		, 'email'
		, 'password'
		, 'first_name'
		, 'last_name'
		, 'time_registered'
		, 'level'
	);


	/**
	 * check password against stored row
	 * @param  string $password 
	 * @return bool           
	 */
	public function validatePassword($password = '') {
		$mold = $this->getDataFirst();
		if (crypt($password, $mold->password) == $mold->password) {
			return true;
		}
	}
}
