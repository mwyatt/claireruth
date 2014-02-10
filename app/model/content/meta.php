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
		# code...
	}


	public function update($id, $mold)
	{
		# code...
	}


	public function delete($ids = array())
	{
		# code...
	}


	/**
	 * returns all the content ids that the tag is assigned to
	 * @param  string $tagName 
	 * @return bool          
	 */
	public function readByValue($colName, $colValue)
	{
		$contentIds = array();
		$sth = $this->database->dbh->prepare("	
			select
				content_id
			from content_meta
			where content_meta.value = :$colName
				and content_meta.name = 'tag'
		");
		$this->bindValue($sth, ':' . $colName, $colValue);
		$this->tryExecute($sth, '098765432');
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$contentIds[] = $row['content_id'];
		}
		return $this->setData(array_unique($contentIds));
	}


	/**
	 * deletes a row or set of rows based on the name and value
	 * combined
	 * @param  string $colName  
	 * @param  int|string|bool $colValue 
	 * @return int           
	 */
	public function deleteByValue($colName, $colValue)
	{
		$sth = $this->database->dbh->prepare("	
			delete
				from content_meta
			where content_meta.name = ?
				and content_meta.value = ?
		");
		$this->bindValues($sth, array($colName, $colValue));
		$this->tryExecute($sth, '12315514344124');
		return $sth->rowCount();
	}


	/**
	 * deletes all rows with a matching content id
	 * @param  int $contentId 
	 * @return int            
	 */
	public function deleteByContentId($contentId)
	{
		$sth = $this->database->dbh->prepare("	
			delete
				from content_meta
			where content_meta.content_id = ?
		");
		$this->bindValues($sth, array($contentId));
		$this->tryExecute($sth, '123663423');
		return $sth->rowCount();
	}


	public function deleteByContentIdAndName($contentId, $name)
	{
		$sth = $this->database->dbh->prepare("	
			delete
				from content_meta
			where content_meta.content_id = ?
				and content_meta.name = ?
		");
		$this->bindValues($sth, array($contentId, $name));
		$this->tryExecute($sth, '123698765463423');
		return $sth->rowCount();
	}


	/**
	 * create all required rows
	 * @param  string $contentId   
	 * @param  string $colName   
	 * @param  array $colValues 
	 * @return int            
	 */
	public function create($contentId, $colName, $colValues)
	{}
	

	public function delete($contentId, $colName, $colValues)
	{
        $sth = $this->database->dbh->prepare("
        	delete
        		from content_meta
        	where content_meta.content_id = ?
        		and content_meta.name = ?
        		and content_meta.value = ?
        ");             

        // execute all delete on each value
        foreach ($colValues as $value) {
			$this->bindValues($sth, array(
	        	$contentId
	        	, $colName
	        	, $value
	        ));
			$this->tryExecute($sth, '09876');
        }
        return $sth->rowCount();
	}
}
