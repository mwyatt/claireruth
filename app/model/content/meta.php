<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Content_Meta extends Model
{	


	public $fields = array(
		'id'
		, 'content_id'
		, 'name'
		, 'value'
	);


	/**
	 * @param  array $molds 
	 * @return bool       
	 */
	public function create($molds = array())
	{
        $sth = $this->database->dbh->prepare('
            insert into ' . $this->getIdentity() . ' (
            	content_id
            	, name
            	, value
        	)
            values (?, ?, ?)
        ');
        foreach ($molds as $mold) {
			$this->tryExecute(__METHOD__, $sth, array(
				$mold->content_id
				, $mold->name
				, $mold->value
	        ));
        }
        return $sth->rowCount();
	}	


	/**
	 * @param  array  $properties type, limit, ids
	 * @return bool             
	 */
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
		$sth = $this->database->dbh->prepare('
			update ' . $this->getIdentity() . ' set
				content_id = ?
				, name = ?
				, value = ?
			where id = ?
		'); 
		$this->tryExecute(__METHOD__, $sth, array(
			$mold->content_id
			, $mold->name
			, $mold->value
			, $id
		));
        return $sth->rowCount();
	}


	public function delete($properties = array())
	{
        $sth = $this->database->dbh->prepare("
        	delete
        		from content_meta
        	where id != 0
			" . (array_key_exists('content_id', $properties) ? ' and content_id = :content_id ' : '') . "
			" . (array_key_exists('name', $properties) ? ' and name = :name ' : '') . "
			" . (array_key_exists('value', $properties) ? ' and value = :value ' : '') . "
        ");             
        if (array_key_exists('content_id', $properties)) {
        	$sth->bindValue(':content_id', $properties['content_id'], PDO::PARAM_INT);
        }
        if (array_key_exists('name', $properties)) {
        	$sth->bindValue(':name', $properties['name'], PDO::PARAM_STR);
        }
        if (array_key_exists('value', $properties)) {
        	$sth->bindValue(':value', $properties['value'], PDO::PARAM_INT);
        }
		$this->tryExecute($sth, 'model_content_meta->delete');
        return $sth->rowCount();
	}
}
