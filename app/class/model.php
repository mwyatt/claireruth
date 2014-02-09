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
	 * [create description]
	 * @param  array  $properties values => array()
	 * @return bool             
	 */
	public function create($properties = array())
	{
		# code...
	}


	public function read($properties = array())
	{
		# code...
	}


	public function update($properties = array())
	{
		# code...
	}


	public function delete($properties = array())
	{
		# code...
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
