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
abstract class Model extends Config
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
	 * depreciated?
	 * @var array
	 */
	// public $dataRow;

	
	/**
	 * always initiates with the session, database and config
	 * @param object $database 
	 * @param object $config   
	 */
	public function __construct($database, $config) {
		$this->session = new Session();
		$this->database = $database;
		$this->config = $config;
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
	 * sets one result row at a time
	 * @param object $sth 
	 */
	public function setDataStatement($sth)
	{		
	
		// no rows
		if (! $sth->rowCount()) 
			return false;

		// some rows
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

			// handle possible meta_name
			if (array_key_exists('meta_name', $row)) {

				// set meta else set full row
				if (array_key_exists($row['id'], $this->data)) {
					$this->data[$row['id']][$row['meta_name']] = $row['meta_value'];
				} else {
					$this->data[$row['id']] = $row;
					$this->data[$row['id']][$row['meta_name']] = $row['meta_value'];
				}

				unset($this->data[$row['id']]['meta_name']);
				unset($this->data[$row['id']]['meta_value']);

			} else {

				$this->data[$row['id']] = $row;

			}

		}

		// correct array keys
		if (count($this->data) > 1) {
			$this->data = array_values($this->data);
		} else {
			$this->data = reset($this->data);
		}

		return true;
		
	}
	
	
	/**
	 * Set data array
	 */
	public function setData($value)
	{		
		$this->data = $value;
	}
	
	
	/**
	 * use sth to parse rows combining meta data and store in $data
	 */
	public function parseRows($sth) {
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			foreach ($row as $key => $value) {
				$this->data[$row['id']][$key] = $value;
			}
			if (array_key_exists('meta_name', $row)) {
				$this->data[$row['id']][$row['meta_name']] = $row['meta_value'];
			}
			if (array_key_exists($name = 'meta_name', $this->data[$row['id']])) {
				unset($this->data[$row['id']][$name]);
			}
			if (array_key_exists($name = 'meta_value', $this->data[$row['id']])) {
				unset($this->data[$row['id']][$name]);
			}
		}
		if (count($this->data) == 1) {
			$this->data = $this->data[key($this->data)];
		}
	}	
	

	/**
	 * checks through form fields for invalid or null data
	 * @param  array $_POST 
	 * @param  array $keys  
	 * @return bool        if all is valid
	 */
	public function validatePost($keys) {
		$validity = true;
		foreach ($keys as $key) {
			if (array_key_exists($key, $_POST)) {
				if (empty($_POST[$key])) {
					$validity = false;
				}
			}
		}
		return $validity;
	}	


	/**
	 * checks the validity of an integer
	 * @param  int $value 
	 * @return bool        
	 */
	public function validateInt($value) {
		$value = intval($value);
		if (gettype($value) == 'integer') {
			return true;
		}
		return false;
	}


	/**
	 * possibly belongs in the config class?
	 * @return string
	 */
	public function getUploadDir() {
		return $this->getUrl('base') . 'img/upload/';
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
	 * @param  string $type 
	 * @param  string $name 
	 * @param  string $id   
	 * @return string        the url
	 */
	public function getGuid($type = false, $name = false, $id = false) {
		if ($type == 'timthumb') {
			return $this->config->getUrl('base') . 'timthumb/?src=' . $this->config->getUrl('base') . $name;
		}
		if ($type == 'media') {
			return $this->config->getUrl('base') . $id . $name;
		}
		$url = $this->config->getUrl('base') . $type . '/' . $this->urlFriendly($name) . '-' . $id . '/';
		if (! $id) {
			$url = str_replace('-/', '/', $url);
		}
		return $url;
	}


	/**
	 * handy for checking if a checkbox has been ticked
	 * @param  string  $key 
	 * @return boolean      
	 */
	public function isChecked($key) {
		return (array_key_exists($key, $_POST) ? true : false);
	}


	/**
	 * sets all meta keys, how clever!
	 * at the moment requires id, title, meta_key and meta_value
	 * @todo  horrible idea
	 * @param array $results 
	 */
	public function setMeta($results) {		
		$parsedResults = array();
		foreach ($results as $result) {
			if (array_key_exists('meta_name', $result)) {
				if (array_key_exists($result['id'], $parsedResults)) {
					if (array_key_exists($result['meta_name'], $parsedResults[$result['id']])) {
						if (is_array($parsedResults[$result['id']][$result['meta_name']])) {
							$parsedResults[$result['id']][$result['meta_name']][] = $result['meta_value'];
						} else {
							$existingValue = $parsedResults[$result['id']][$result['meta_name']];
							unset($parsedResults[$result['id']][$result['meta_name']]);
							$parsedResults[$result['id']][$result['meta_name']][] = $existingValue;
							$parsedResults[$result['id']][$result['meta_name']][] = $result['meta_value'];
						}
					} else {
						$parsedResults[$result['id']][$result['meta_name']] = $result['meta_value'];
					}
				} else {
					$parsedResults[$result['id']] = $result;
					$parsedResults[$result['id']][$result['meta_name']] = $result['meta_value'];
				}
				unset($parsedResults[$result['id']]['meta_name']);
				unset($parsedResults[$result['id']]['meta_value']);
			} else {
				$parsedResults[$result['id']] = $result;
			}
			if (array_key_exists('title', $result)) {
				$parsedResults[$result['id']]['slug'] = $this->urlFriendly($parsedResults[$result['id']]['title']);
				if (array_key_exists('type', $parsedResults[$result['id']])) {
					$parsedResults[$result['id']]['guid'] = $this->getGuid($parsedResults[$result['id']]['type'], $parsedResults[$result['id']]['title'], $parsedResults[$result['id']]['id']);
				}
			}
		}
		foreach ($parsedResults as $key => $parsed) {
			$parsedResults[$key] = array_filter($parsed);
		}
		return array_filter($parsedResults);
	}

	
}