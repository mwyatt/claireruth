<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Tag extends Model
{	


	/**
	 * gets all tags or by specific content id
	 * @param  integer $contentId 
	 * @return array             
	 */
	public function readByContentId($contentIds = array()) {	
		$sth = $this->database->dbh->prepare("	
			select
				tag.id
				, tag.title
				, tag.description
			from content_meta
                left join tag on tag.id = content_meta.value
			where content_meta.content_id = ?
                and content_meta.name = 'tag'
		");
		foreach ($contentIds as $contentId) {
			$this->bindValue($sth, '?', $contentId);
			$sth = $this->tryExecute($sth, '88667845');
			if ($sth->rowCount()) {
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$row['url'] = $this->buildUrl(array('tag', $row['name']));
					$row['name_friendly'] = ucwords($row['name']);
					$parsedData[$contentId][] = $row;
				}
			}
		}
		echo '<pre>';
		print_r($parsedData);
		echo '</pre>';
		exit;
		
		return $this->setData($parsedData);
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
		$rows = array();
		$matches = array();
		$words = explode(' ', $query);
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, tag_id as name
			from content_tag
			where
				content_tag.tag_id like ?
			group by content_tag.tag_id
			order by content_tag.tag_id desc
		");
		foreach ($words as $word) {
			$sth->execute(array(
				'%' . $word . '%'
			));
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$row['url'] = $this->buildUrl(array('tag', $row['name']));
				$row['name_friendly'] = ucwords($row['name']);
				$rows[$row['id']] = $row;
			}
		}
		$this->data = $rows;
		return count($this->getData());
	}


	/**
	 * reads all content ids for a certain tag
	 * @param  string $query tag name
	 * @return array        contentids
	 */
	public function readContent($query = '') {	
		$contentIds = array();
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
			from content_tag
			where
				content_tag.tag_id like ?
		");
		foreach ($words as $word) {
			$sth->execute(array(
				'%' . $word . '%'
			));
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$contentIds[] = $row['content_id'];
			}
		}
		$this->data = $contentIds = array_unique($contentIds);
		return count($this->getData());
	}


	/**
	 * reads a single tag name and returns the content ids
	 * @param  strgin $tagName 
	 * @return int          
	 */
	public function readSingle($tagName) {	
		$contentIds = array();
		$tagName = str_replace('-', ' ', $tagName);
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, tag_id as name
			from content_tag
			where
				content_tag.tag_id like ?
		");
		$sth->execute(array($tagName));
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$contentIds[] = $row['content_id'];
		}
		$this->setData(array_unique($contentIds));
		return $sth->rowCount();
	}
}
