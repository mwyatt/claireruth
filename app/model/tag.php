<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Tag extends Model
{	


	public $fields = array(
		'id'
		, 'description'
		, 'title'
	);


	/**
	 * gets unique tags based on a search query
	 * @param  string $query 
	 * @return int        total results
	 */
	public function readSearch($query = '') {	
		if (! $query) {
			return;
		}
		$rows = array();
		$matches = array();
		$words = explode(' ', $query);
		$sth = $this->database->dbh->prepare("	
			select
				id
				, description
				, title
			from tag
			where
				tag.title like ?
			group by tag.title
			order by tag.title desc
		");
		foreach ($words as $word) {
			$sth->execute(array(
				'%' . $word . '%'
			));
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$row['url'] = $this->buildUrl(array('tag', $row['title']));
				$row['title_friendly'] = ucwords($row['title']);
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
				, description
				, title
			from tag
			where
				tag.title like ?
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
}
