<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 

class Model extends Config
{


	/**
	 * generic lazy read!
	 * @param  string $select the full select statement, could be easier!
	 * @param  array  $where  label, value
	 * @param  array  $ids    2, 53, 12
	 * @param  array  $limit  0, 200
	 * @return bool         
	 */
	public function read($select = "", $where = array(), $ids = array(), $limit = array()) {

		// build query
		$query = "
			select
				{$select}
			from
				{$this->getIdentity()}
			where
				{$this->getIdentity()}.id != 0
		";

		// build query - where
		foreach ($where as $colName => $value) {
			$query .= " and {$this->getIdentity()}.$colName = :$colName ";
		}

		// build query - ids
		if ($ids) {
			$query .= " and {$this->getIdentity()}.id = :id ";
		}

		// build query - limit
		if ($limit) {
			$query .= " limit :limit_start, :limit_end ";
		}

		// prepare
		$sth = $this->database->dbh->prepare($query);		

		// binding
		foreach ($where as $colName => $value) {
			$this->bindValue($sth, ':' . $colName, $value);
		}
		if ($limit) {
			$this->bindValue($sth, ':limit_start', (int) reset($limit));
			$this->bindValue($sth, ':limit_end', (int) next($limit));
		}	

		// execution
		if ($ids) {
			foreach ($ids as $id) {
				$this->bindValue($sth, ':id', $id);
				$sth = $this->tryExecute($sth, '12312345');
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$results[] = $row;
				}
			}
		} else {
			$sth = $this->tryExecute($sth, '38219381');				
			$results = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		return $this->setData($results);
	}	


	/**
	 * attempts to execute, if problem found error code is shown
	 * @param  object $sth       
	 * @param  string $errorCode 
	 * @return object           
	 */
	public function tryExecute($sth, $errorCode = '')
	{
		try {
			$sth->execute();
		} catch (Exception $e) {
			echo 'error' . $errorCode;
			exit;
		}
		return $sth;
	}

	
	/**
	 * builds and creates create query
	 * @param  array  $cols colname => value
	 * @return int            yay or nay
	 */
	public function create($colValues = array(), $secondary = array(), $tertiary = array(), $quantenary = array())
	{
		$valList = '';
		foreach ($colValues as $col => $val) {
			$cols[] = $col;
			$vals[] = $val;
			$valList .= ', ?';
		}
		$colList = implode(', ', $cols);
		$valList = ltrim($valList, ', ');
		$sth = $this->database->dbh->prepare("
			insert into {$this->getIdentity()} (
				$colList
			) values (
				$valList
			)
		");		
		$this->bindValues($sth, $vals);
		try {
			$sth->execute();
		} catch (Exception $e) {
			echo 'Database create error.';
			exit;
		}
		return $sth->rowCount();
	}


	/**
	 * builds and creates update query
	 *
	 * example
	*	 * 		$modelnew->update(
	*			array(
	*				'description' => 'hello'
	*				, 'user_id' => 20
	*				, 'time' => time()
	*				, 'action' => 'example update'
	*			)
	*			, array('id' => 2)
	*			);
	 *
	 * 
	 * @param  array  $colValues colname => value
	 * @param  array  $where where => value
	 * @return int            yay or nay
	 * @todo improve where to accept multiples
	 * @todo the return value is not ideal
	 */	
	public function update($colValues = array(), $where = array())
	{
		$query = "
			update {$this->getIdentity()} set
				$colList
			where
				$whereCol = ?
		";
		$colList = '';
		foreach ($colValues as $col => $val) {
			$vals[] = $val;
			$colList .= ', ' . $col . ' = ?';
		}
		$whereCol = key($where);
		$vals[] = current($where);
		$colList = ltrim($colList, ', ');
		$sth = $this->database->dbh->prepare($query);		
		$this->bindValues($sth, $vals);
		try {
			$sth->execute();
		} catch (Exception $e) {
			echo 'Database update error.';
			exit;
		}
		return true;
	}

	
	/**
	 * builds and creates delete query
	 * example usage: $content->delete(array('id' => $_GET['delete']))
	 * @param  array  $where where => value
	 * @return int            yay or nay
	 */
	public function delete($where = array())
	{
		$colName = key($where);
		$whereVal = array(current($where));
		$sth = $this->database->dbh->prepare("
			delete from 
				{$this->getIdentity()}
			where
				$colName = ?
		");				
		$this->bindValues($sth, $whereVal);
		try {
			$sth->execute();
		} catch (Exception $e) {
			echo 'Database delete error.';
			exit;
		}
		return $sth->rowCount();
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
	 * upgraded get url method, allows unlimited segments
	 * friendly helps out with slashes and making things safe
	 * @param  array   $segments      each/segment/
	 * @return string                 the url
	 */
	public function buildUrl($segments = array(), $friendly = true) {
		$finalUrl = $this->config->getUrl('base');
		foreach ($segments as $segment) {
			if ($friendly) {
				$segment = $this->urlFriendly($segment);
			}
			$finalUrl .= $segment . ($friendly ? '/' : '');
		}
		return $finalUrl;
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


	/**
	 * binds values with unnamed placeholders, 1 2 3 instead of 0 1 2
	 * @param  object $sth    the statement to bind to
	 * @param  array $values basic array with values
	 * @return bool | null         returns false if something goes wrong
	 */
	public function bindValues($sth, $values)
	{
	    if (! is_object($sth) || ! ($sth instanceof PDOStatement)) {
	    	return;
	    }
        foreach($values as $key => $value) {
        	$correctedKey = $key + 1;
        	$this->bindValue($sth, $correctedKey, $value);
        }
	}


	/**
	 * binds a single value and guesses the type
	 * @param  object $sth   
	 * @param  int|string $key   
	 * @param  all $value 
	 */
	public function bindValue($sth, $key, $value)
	{
		if (is_int($value)) {
		    $sth->bindValue($key, $value, PDO::PARAM_INT);
		} elseif (is_bool($value)) {
		    $sth->bindValue($key, $value, PDO::PARAM_BOOL);
		} elseif (is_null($value)) {
		    $sth->bindValue($key, $value, PDO::PARAM_NULL);
		} elseif (is_string($value)) {
		    $sth->bindValue($key, $value, PDO::PARAM_STR);
		}
	}
}
