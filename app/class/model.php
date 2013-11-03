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
	public function lazyRead($select = "", $where = array(), $ids = array(), $limit = array()) {

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
				$this->tryExecute($sth, '12312345');
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$results[] = $row;
				}
			}
		} else {
			$this->tryExecute($sth, '38219381');				
			$results = $sth->fetchAll(PDO::FETCH_ASSOC);
		}

		// uses the parserows function to add any urls or post processing
		if (method_exists($this, 'parseRows')) {
			$results = $this->parseRows($results);
		}

		// returns the set data function result
		return $this->setData($results);
	}	

	
	/**
	 * builds and creates create query
	 * @param  array  $columns colname => value
	 * @return int            yay or nay
	 */
	public function lazyCreate($colValues = array(), $secondary = array(), $tertiary = array(), $quantenary = array())
	{
		$valList = '';
		$columns = array();
		$values = array();
		foreach ($colValues as $col => $val) {
			$columns[] = $col;
			$values[] = $val;
			$valList .= ', ?';
		}
		$colList = implode(', ', $columns);
		$valList = ltrim($valList, ', ');
		$sth = $this->database->dbh->prepare("
			insert into {$this->getIdentity()} (
				$colList
			) values (
				$valList
			)
		");		
		$this->bindValues($sth, $values);
		$this->tryExecute($sth, '676767212');
		return $sth->rowCount();
	}


	/**
	 * builds and creates update query
	 * @param  array  $colValues colname => value
	 * @param  array  $where where => value
	 * @return int            yay or nay
	 * @todo the return value is not ideal
	 */	
	public function update($colValues = array(), $where = array())
	{

		// build collist
		$colList = '';
		$values = array();
		foreach ($colValues as $col => $val) {
			$values[] = $val;
			$colList .= ', ' . $col . ' = ?';
		}
		$colList = ltrim($colList, ', ');

		// build query
		$query = "
			update {$this->getIdentity()} set
				$colList
			where
				{$this->getIdentity()}.id != 0
		";

		// build query - where
		foreach ($where as $colName => $value) {
			$query .= " and {$this->getIdentity()}.$colName = ? ";
			$values[] = $value;
		}

		// prepare and bind
		$sth = $this->database->dbh->prepare($query);		
		$this->bindValues($sth, $values);

		// return failure or sth object (success)
		$this->tryExecute($sth, '45654645645');
		return $sth->rowCount();
	}

	
	/**
	 * builds and creates delete query
	 * example usage: $content->delete(array('id' => $_GET['delete']))
	 * @param  array  $where where => value
	 * @return int            yay or nay
	 */
	public function lazyDelete($where = array())
	{
		$values = array();
		$query = "
			delete from 
				{$this->getIdentity()}
			where
				{$this->getIdentity()}.id != 0
		";

		// build query - where
		foreach ($where as $colName => $value) {
			$query .= " and {$this->getIdentity()}.$colName = ? ";
			$values[] = $value;
		}

		// prepare
		$sth = $this->database->dbh->prepare($query);				

		// bind
		$this->bindValues($sth, $values);
		$this->tryExecute($sth, '45654645645');
		return $sth->rowCount();
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
			$this->config->getObject('error')->handle('database', $errorCode, 'model.php', 'na');
			return false;
		}
		return $sth;
	}


	/**
	 * returns the latest insert id from the database
	 * @return int|bool 
	 */
	public function getLastInsertId()
	{
		return $this->database->dbh->lastInsertId();
	}


	/**
	 * example which will accept rows and add a url for example
	 * @param  array $rows 
	 * @return array       the parsed one
	 */
	public function parseRows($rows)
	{
		$parsedRows = array();
		foreach ($rows as $key => $row) {
			$parsedRows[$key] = $row;
		}
		return $parsedRows;
	}


	public function parseRow($row)
	{
		// manipulate the row here and return
		return $row;
	}
}
