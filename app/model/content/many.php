<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Content_Many extends Model
{	


	/**
	 * the column name will be completed on construct and used
	 * within methods
	 * @var string
	 */
	public $colName = '_id';


	/**
	 * gets all items or by specific content id
	 * @param  integer $contentId 
	 * @return array             
	 */
	public function read($contentId = 0) {	
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, $this->colName
			from $this->tableName
			" . ($contentId ? ' where content.id = :content_id ' : '') . "
			group by $this->tableName.name
			order by $this->tableName.name desc
		");
		if ($contentId) {
			$sth->bindValue(':content_id', $id, PDO::PARAM_STR);
		}
		$sth->execute(array(
			':id' => $id
		));	
		return $results = $sth->fetchAll(PDO::FETCH_ASSOC);
	}
}