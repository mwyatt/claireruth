<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Content_Meta extends Model
{	


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
		$sth = $this->tryExecute($sth, '098765432');
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$contentIds[] = $row['content_id'];
		}
		return $this->setData(array_unique($contentIds));
	}
}
