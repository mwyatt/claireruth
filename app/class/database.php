<?php

/**
 * Database
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Database
{


	public $dbh;

	
	public $credentials;


	/**
	 * connects to the database
	 */
	public function __construct($credentials) {
		$this->credentials = $credentials;
		$this->connect();
	}
	

	/**
	 * returns the static credentials
	 * @return [type] [description]
	 */
	public function getCredentials() {
		return $this->credentials;
	}
	
	
	public function connect() {
		$credentials = $this->getCredentials();
		try {

			// Set Data Source Name
			$dataSourceName = 'mysql:host='.$credentials['host']
				.';dbname='.$credentials['basename'];
			
			// Connect
			$this->dbh = new PDO(
				$dataSourceName,
				$credentials['username'],
				$credentials['password']
			);	
		
			// Set Error Mode
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $error) {
			echo '<h1>Unable to Connect to Database</h1>';
			exit;
		}	
	}
}
