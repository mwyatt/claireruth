<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Maincontent_Tag extends Model
{	


	/**
	 * gets all tags or by specific content id
	 * @param  integer $contentId 
	 * @return array             
	 */
	public function read($contentIds = array()) {	
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, tag_id as name
			from main_content_tag
			where main_content_tag.content_id = ?
			group by main_content_tag.tag_id
			order by main_content_tag.tag_id desc
		");
		foreach ($contentIds as $contentId) {
			$sth->execute(array($contentId));	
			if ($sth->rowCount()) {
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$row['guid'] = $this->getGuid('tag', $row['name']);
					$this->data[$contentId][] = $row;
				}
			}
		}
		return $this->data;
	}


	/**
	 * gets unique tags based on a search query
	 * @param  string $query 
	 * @return int        total results
	 */
	public function readUniqueLike($query = '') {	
		if (! $query) {
			return;
		}
		$matches = array();
		$words = explode(' ', $query);
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, tag_id as name
			from main_content_tag
			where
				main_content_tag.tag_id like ?
			group by main_content_tag.tag_id
			order by main_content_tag.tag_id desc
		");
		foreach ($words as $word) {
			$sth->execute(array(
				'%' . $word . '%'
			));
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$row['guid'] = $this->getGuid('tag', $row['name']);
				$rows[$row['id']] = $row;
			}
		}
		$this->data = $rows;
		return count($this->getData());
	}
}
