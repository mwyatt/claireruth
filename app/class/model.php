<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Model extends Data
{


	/**
	 * identifies the the instance of some classes
	 * for example
	 * 		$_SESSION[$keyName]
	 * 		or table name
	 * 	usually parsed from the class title
	 * @var string
	 */
	public $identity = '';



	/**
	 * universal storage property, used for many things
	 * @var array
	 */
	public $data;


	/**
	 * comprehensive list of database fields for use when building queries
	 * @var array
	 */
	public $fields = array(
		'id'
		, 'name'
		, 'value'
	);


	/**
	 * used to reduce fields to writable ones
	 * for update, create
	 * @var array
	 */
	public $fieldsNonWriteable = array(
		'id'
	);


	public function __construct($system = false) {

		// construct in default fashion
		parent::__construct($system);

		// set the identity for use on crud
		$this->setIdentity();
	}


	/**
	 * @param  array $molds 
	 * @return array 	of insert ids
	 */
	public function create($molds = array())
	{

		// statement
		$statement = array();
		$lastInsertIds = array();
		$statement[] = 'insert into';
		$statement[] = $this->getIdentity();
		$statement[] = '(' . $this->getSqlFieldsWriteable() . ')';
		$statement[] = 'values';
		$statement[] = '(' . $this->getSqlPositionalPlaceholders() . ')';

		// prepare
		$sth = $this->database->dbh->prepare(implode(' ', $statement));

		// execute
        foreach ($molds as $mold) {
			$this->tryExecute(__METHOD__, $sth, $this->getSthExecutePositional($mold));
			if ($sth->rowCount()) {
				$lastInsertIds[] = intval($this->database->dbh->lastInsertId());
			}
        }

		// return
		return $lastInsertIds;
	}	


	public function read($properties = array())
	{

		// build
		$statement = array();
		$statement[] = $this->getSqlSelect();
		if (array_key_exists('where', $properties)) {
			$statement[] = $this->getSqlWhere($properties['where']);
		}
		if (array_key_exists('order_by', $properties)) {
			$statement[] = 'order by ' . $properties['order_by'];
		}
		if (array_key_exists('limit', $properties)) {
			$statement[] = $this->getSqlLimit($properties['limit']);
		}
		$statement = implode(' ', $statement);

		// prepare
		$sth = $this->database->dbh->prepare($statement);

		// bind
		if (array_key_exists('where', $properties)) {
			foreach ($properties['where'] as $key => $value) {
				$this->bindValue($sth, 'where_' . $key, $value);
			}
		}
		if (array_key_exists('limit', $properties)) {
			foreach ($properties['limit'] as $key => $value) {
				$sth->bindValue(':limit_' . $key, (int) $value, PDO::PARAM_INT);
			}
		}

		// execute
		$this->tryExecute(__METHOD__, $sth);
		return $this->setData($sth->fetchAll(PDO::FETCH_CLASS, $this->getMoldName()));
	}


	/**
	 * uses the passes properties to build named prepared statement
	 * @todo how to return a value which can mark success?
	 * @param  array  $molds 
	 * @param  string $by    defines the column to update by
	 * @return int        
	 */
	public function update($mold, $properties = array())
	{

		// statement
		$statement = array();
		$statement[] = 'update';
		$statement[] = $this->getIdentity();
		$statement[] = 'set';

		// must be writable columns
		$named = array();
		foreach ($mold as $key => $value) {
			if (! $value || in_array($key, $this->fieldsNonWriteable)) {
				continue;
			}
			$named[] = $key . ' = :' . $key;
		}
		$statement[] = implode(', ', $named);
		if (array_key_exists('where', $properties)) {
			$statement[] = $this->getSqlWhere($properties['where']);
		}

		// prepare
		$sth = $this->database->dbh->prepare(implode(' ', $statement));

		// bind
		if (array_key_exists('where', $properties)) {
			foreach ($properties['where'] as $key => $value) {
				$this->bindValue($sth, 'where_' . $key, $value);
			}
		}
		foreach ($this->getSthExecuteNamed($mold) as $key => $value) {
			$this->bindValue($sth, $key, $value);
		}

		// execute
		$this->tryExecute(__METHOD__, $sth);

		// return
        return $sth->rowCount();
	}


	/**
	 * uses where property to build delete statement
	 * @param  array  $properties 
	 * @return int             
	 */
	public function delete($properties = array())
	{

		// build
		$statement = array();
		$statement[] = 'delete from';
		$statement[] = $this->getIdentity();
		if (array_key_exists('where', $properties)) {
			$statement[] = $this->getSqlWhere($properties['where']);
		}

		// prepare
		$sth = $this->database->dbh->prepare(implode(' ', $statement));

		// bind
		if (array_key_exists('where', $properties)) {
			foreach ($properties['where'] as $key => $value) {
				$this->bindValue($sth, 'where_' . $key, $value);
			}
		}

		// execute
		$this->tryExecute(__METHOD__, $sth);
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


	public function isFieldNonWritable($field)
	{
		return in_array($field, $this->fieldsNonWriteable);
	}


	/**
	 * uses a mold to build sth execute data
	 * if 'time' involved assume that time needs to be inserted, could be
	 * a bad idea
	 * @param  object $mold instance of mold
	 * @return array       
	 */
	public function getSthExecutePositional($mold)
	{
		$excecuteData = array();
		foreach ($this->fields as $field) {
			if ($this->isFieldNonWritable($field)) {
				continue;
			}
			$excecuteData[] = $mold->$field;
		}
		return $excecuteData;
	}


	public function getSthExecuteNamed($mold)
	{
		$excecuteData = array();
		foreach ($mold as $key => $value) {
			if ($this->isFieldNonWritable($key) || ! $value) {
				continue;
			}
			$excecuteData[':' . $key] = $value;
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
		foreach ($where as $key => $value) {
			$statement[] = ($statement ? 'and' : 'where');

			// array becomes in (1, 2, 3)
			if (is_array($value)) {
				$statement[] = $key . ' in (' . implode(', ', $value) . ')';
				continue;
			}

			// normal key = val
			$statement[] = $key . ' = :where_' . $key;
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
			$limits[] = ':limit_' . $key;
		}
		$statement[] = implode(', ', $limits);
		return implode(' ', $statement);
	}


	/**
	 * builds an array of {property} from the data property
	 * @param  string $property 
	 * @return array           
	 */
	public function getDataProperty($property)
	{
		if (! $this->getData()) {
			return;
		}
		$collection = array();
		foreach ($this->getData() as $mold) {
			$collection[] = $mold->$property;
		}
		return $collection;
	}


	/**
	 * arranges this->data by a specified property
	 * @param  string $property 
	 * @return array           
	 */
	public function arrangeByProperty($property)
	{
		if (! $this->getData()) {
			return;
		}
		$newOrder = array();
		foreach ($this->getData() as $mold) {
			$newOrder[$mold->$property] = $mold;
		}
		return $this->setData($newOrder);
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
			echo '<pre>';
			print_r($sthData);
			echo '</pre>';
			exit('error trying to execute statement');
			// $this->config->getObject('error')->handle('database', $errorCode, 'model.php', 'na');
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
	

	/**
	 * currently a copy of the config method, but needs to go
	 * through the config?
	 * @param  string $key 
	 * @return any
	 */
	public function getOption($key) {
		if (array_key_exists($key, $this->config->options)) {
			return $this->config->options[$key];
		}
	}


	/**
	 * simple return of identity
	 * @return string 
	 */
	public function getIdentity()
	{
		return $this->identity;
	}


	/**
	 * sets the identity property manually
	 * or get the class name and turn_into_this format
	 */
	public function setIdentity()
	{
		$className = get_class($this);
		$className = explode('_', $className);
		array_shift($className);

		// catching classes like 'Session' and 'Model'
		if (! $className) {
			return $this->identity = '';
		}
		$className = implode('_', $className);
		$this->identity = strtolower($className);
		return $this;
	}


	/**
	 * @param mixed $value 
	 */
	public function setData($value)
	{		
		return $this->data = $value;
	}


	/**
	 * get
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function getData($key = '')
	{		
		if ($key) {
			if (array_key_exists($key, $this->data)) {
				return $this->data[$key];
			}
			return;
		}
		return $this->data;
	}	


	/**
	 * retrieves the first row of data, if there is any
	 * @return object, array, bool       
	 */
	public function getDataFirst()
	{
		$data = $this->getData();
		if (! $data) {
			return;
		}
		return reset($data);
	}
}
