<?php

/**
 * Template for all other Models
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Model extends Config
{


	/**
	 * see class database
	 * @var object
	 */
	public $database;

	
	/**
	 * see class config
	 * @var object
	 */
	public $config;


	/**
	 * see class session
	 * @var object
	 */
	public $session;


	/**
	 * returned data from sql requests
	 * @var array
	 */
	public $data = array();


	/**
	 * if class is used generically then the table
	 * name will be used to identify which table
	 * to hit with methods
	 * @var string
	 */
	public $tableName;

	
	/**
	 * always initiates with the session, database and config
	 * @param object $database 
	 * @param object $config   
	 */
	public function __construct($database, $config, $tableName = '') {
		$this->session = new Session();
		$this->database = $database;
		$this->config = $config;

		// sets the table name for use with generic methods
		if ($tableName) {
			$this->tableName = $tableName;
		}
	}


	/**
	 * builds and creates update query
	 * @param  array  $colValues colname => value
	 * @param  array  $where where => value
	 * @return int            yay or nay
	 */	
	public function update($colValues = array(), $where = array())
	{
		$sth = $this->database->dbh->prepare("
			update {$this->getTableName()} set
				$colList
			where
				? = ?
		");				
		foreach ($colValues as $col => $val) {
			$cols[] = $col;
			$vals[] = $val;
			$colList .= ', ' . $col . ' = ?';
		}
		$vals[] = key($where);
		$vals[] = current($where);
		$colList = ltrim($colList, ', ');
		$sth->execute($vals); 
		return $sth->rowCount();
	}

	
	/**
	 * builds and creates delete query
	 * @param  array  $where where => value
	 * @return int            yay or nay
	 */
	public function delete($where = array())
	{
		$sth = $this->database->dbh->prepare("
			delete from {$this->getTableName()} (
			where ? = ?
		");				
		$vals[] = key($where);
		$vals[] = current($where);
		$sth->execute($vals);
		return $sth->rowCount();
	}


	/**
	 * builds and creates create query
	 * @param  array  $colValues colname => value
	 * @return int            yay or nay
	 */
	public function create($colValues = array())
	{
		$sth = $this->database->dbh->prepare("
			insert into {$this->getTableName()} (
				$colList
			) values (
				$valList
			)
		");				
		$valList = '';
		foreach ($colValues as $col => $val) {
			$cols[] = $col;
			$vals[] = $val;
			$valList .= ', ?';
		}
		$colList = implode(', ', $cols);
		$valList = ltrim($valList, ', ');
		$sth->execute($vals);
		return $sth->rowCount();
	}


	/**
	 * simple return of table name
	 * @return string 
	 */
	public function getTableName()
	{
		return $this->tableName;
	}


	/**
	 * Get data array or by key
	 * @param  string $key 
	 * @return value|bool       depending upon success
	 */
	public function getData($key = false)
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
	 * Set data array
	 */
	public function setData($value)
	{		
		$this->data = $value;
	}


	/**
	 * possibly belongs in the config glass?
	 * @param  string $value 
	 * @return string        one you can be friends with
	 */
	public function urlFriendly($value = null)
	{
	
		// everything to lower and no spaces begin or end
		$value = strtolower(trim($value));
		
		// adding - for spaces and union characters
		$find = array(' ', '&', '\r\n', '\n', '+',',');
		$value = str_replace ($find, '-', $value);
		
		//delete and replace rest of special chars
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$value = preg_replace ($find, $repl, $value);
		
		//return the friendly str
		return $value; 	
	}


	/**
	 * constructs a friendly guid using 3 components
	 * special urls will only use type and name
	 * @param  string $type 
	 * @param  string $name 
	 * @param  string $id   (optional)
	 * @return string        the url
	 */
	public function getGuid($type = false, $name = false, $id = false) {

		// tim
		if ($type == 'thumb') {
			return $this->config->getUrl('base') . 'thumb/?src=' . $name;
		}
		
		// media
		if ($type == 'media') {
			return $this->config->getUrl('base') . $name;
		}

		// normal
		$url = $this->config->getUrl('base') . $type . '/' . $this->urlFriendly($name) . '-' . $id . '/';

		// if lacking id then strip away dash at end
		if (! $id) {
			$url = str_replace('-/', '/', $url);
		}
		return $url;
	}


	/**
	 * handy for checking if a checkbox has been ticked
	 * @param  string  $key 
	 * @return boolean      
	 * @todo remove this if possible, use validate_whatever
	 */
	public function isChecked($key) {
		return (array_key_exists($key, $_POST) ? true : false);
	}	
}
