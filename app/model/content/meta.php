<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Content_Meta extends Model
{	


	public function create($molds = array())
	{
        $sth = $this->database->dbh->prepare("
            insert into content_meta (
                content_id
                , name
                , value
            )
            values (
                ?
                , ?
                , ?
            )
        ");
        foreach ($molds as $mold) {
	        $sth->execute(array(
	            $mold->content_id
	            , $mold->name
	            , $mold->value
	        ));                
        }
        return $sth->rowCount();
	}


	public function read($properties = array())
	{
		$contentIds = array();
		$sth = $this->database->dbh->prepare("	
			select
				content_id
			from content_meta
			where value = :value
			" . (array_key_exists('name', $properties) ? ' and name = :name ' : '') . "
		");
		if (array_key_exists('name', $properties)) {
			$sth->bindValue(':name', $properties['name'], PDO::PARAM_STR);
		}
		$sth->bindValue(':value', $properties['value'], PDO::PARAM_STR);
		$this->tryExecute($sth, 'model_content_meta->read');
		while ($mold = $sth->fetch(PDO::FETCH_CLASS, 'Mold_Content_Meta')) {
			$contentIds[] = $mold->content_id;
		}
		return $this->setData(array_unique($contentIds));
	}


	public function update($id, $mold)
	{
		# code...
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
