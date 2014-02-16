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
	 * comprehensive list of database fields for use when building queries
	 * @var array
	 */
	public $fields = array(
		'id'
		, 'name'
		, 'value'
	);

	public $fieldsNonWriteable = array(
		'id'
	);


	/**
	 * @param  array $molds 
	 * @return bool       
	 */
	public function create($molds = array())
	{

		// statement
		$statement = array();
		$statement[] = 'insert into';
		$statement[] = $this->getIdentity();
		$statement[] = '(' . $this->getSqlFieldsWriteable() . ')';
		$statement[] = 'values';
		$statement[] = '(' . $this->getSqlPositionalPlaceholders() . ')';

		// prepare
		$sth = $this->database->dbh->prepare(implode(' ', $statement));

		// execute
        foreach ($molds as $mold) {
			$this->tryExecute(__METHOD__, $sth, $this->getSthExecuteData($mold));
        }

		// return
        return $sth->rowCount();
	}	


	public function read($properties = array())
	{

		// build
		$statement = array();
		$statement[] = $this->getSqlSelect();
		if (array_key_exists('where', $properties)) {
			$statement[] = $this->getSqlWhere($properties['where']);
		}
		$statement = implode(' ', $statement);

		// prepare
		$sth = $this->database->dbh->prepare($statement);

		// bind
		if (array_key_exists('where', $properties)) {
			foreach ($properties['where'] as $key => $value) {
				$this->bindValue($sth, $key, $value);
			}
		}

		// execute
		$this->tryExecute(__METHOD__, $sth);
		return $this->setData($sth->fetchAll(PDO::FETCH_CLASS, $this->getMoldName()));
	}


	public function update($id, $mold)
	{
		# code...
	}


	/**
	 * @param  array  $ids 
	 * @return bool      
	 */
	public function delete($ids = array())
	{
		$sth = $this->database->dbh->prepare('
			delete from ' . $this->getIdentity() . ' 
			where id = :id
		');
		foreach ($ids as $id) {
			$sth->bindValue(':id', $id);
			$this->tryExecute(__METHOD__, $sth);
		}
		return $sth->rowCount();
	}


	/**
	 * builds a generic select statement and returns
	 * select (column, column) from (table_name)
	 * @return string 
	 */
	public function getSqlSelect()
	{
		$statement = array();
		$statement[] = 'select';
		$statement[] = $this->getSqlFields();
		$statement[] = 'from';
		$statement[] = $this->getIdentity();
		return implode(' ', $statement);
	}


	/**
	 * implodes list of sql fields
	 * column, column, column
	 * @return string 
	 */
	public function getSqlFields()
	{
		return implode(', ', $this->fields);
	}


	/**
	 * implodes list of sql fields excluding fields like 'id'
	 * column, column, column
	 * @return string 
	 */ 
	public function getSqlFieldsWriteable($append = '')
	{
		$writeable = array();
		foreach ($this->fields as $field) {
			if (in_array($field, $this->fieldsNonWriteable)) {
				continue;
			}
			$writeable[] = $field . $append;
		}
		return implode(', ', $writeable);
	}


	/**
	 * @return string ?, ?, ? of all writable fields
	 */
	public function getSqlPositionalPlaceholders()
	{
		$placeholders = array();
		foreach ($this->fields as $field) {
			if (in_array($field, $this->fieldsNonWriteable)) {
				continue;
			}
			$placeholders[] = '?';
		}
		return implode(', ', $placeholders);
	}


	/**
	 * uses a mold to build sth execute data
	 * if 'time' involved assume that time needs to be inserted, could be
	 * a bad idea
	 * @param  object $mold instance of mold
	 * @return array       
	 */
	public function getSthExecuteData($mold)
	{
		$excecuteData = array();
		foreach ($this->fields as $field) {
			if (in_array($field, $this->fieldsNonWriteable)) {
				continue;
			}
			$excecuteData[] = $mold->$field;
		}
		return $excecuteData;
	}


	/**
	 * builds sql where string using and
	 * @param  array  $where accepts ('column' => 'value') format
	 * @return string        
	 */
	public function getSqlWhere($where = array())
	{
		$statement = array();
		$statement[] = 'where 1 = 1';
		foreach ($where as $key => $value) {
			$statement[] = 'and ' . $key . ' = :' . $key;
		}
		return implode(' ', $statement);
	}


	/**
	 * builds sql limit using array
	 * @param  array  $limit accepts ('key' => 'value', 'key' => 'value')
	 * @return string        
	 */
	public function getSqlLimit($limit = array())
	{
		$statement = array();
		$limits = array();
		$statement[] = 'limit';
		foreach ($limit as $key => $value) {
			$limits[] = ':' . $key;
		}
		$statement[] = implode(', ', $limits);
		return implode(' ', $statement);
	}


	/**
	 * builds an array of ids from the data property
	 * @return array 
	 */
	public function getDataIds()
	{
		$datas = $this->getData();
		if (! is_array($datas)) {
			return array();
		}
		$ids = array();
		foreach ($datas as $data) {
			$ids[] = $data->id;
		}
		return $ids;
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
	public function tryExecute($errorCode, $sth, $sthData = array())
	{
		try {
			if ($sthData) {
				$sth->execute($sthData);
			} else {
				$sth->execute();
			}
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


	public function getMoldName()
	{
		return 'mold_' . $this->getIdentity();
	}
}
