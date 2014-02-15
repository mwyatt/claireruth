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
	 * @param  array $molds 
	 * @return bool       
	 */
	public function create($molds = array())
	{
        $sth = $this->database->dbh->prepare('
            insert into ' . $this->getIdentity() . ' (
            	title
            	, description
        	)
            values (?, ?)
        ');
        foreach ($molds as $mold) {
			$this->tryExecute(__METHOD__, $sth, array(
				$mold->title
				, $mold->description
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
		$statement[] = 'order by time_published desc';
		if (array_key_exists('limit', $properties)) {
			$statement[] = $this->getSqlLimit($properties['limit']);
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
		if (array_key_exists('limit', $properties)) {
			foreach ($properties['limit'] as $key => $value) {
				$sth->bindValue(':' . $key, (int) $value, PDO::PARAM_INT);
			}
		}

		// execute
		$this->tryExecute(__METHOD__, $sth);
		return $this->setData($sth->fetchAll(PDO::FETCH_CLASS, $this->getMoldName()));
	}


	/**
	 * gets all tags or by specific content id
	 * @param  array $contentIds 
	 * @return array content_id => array of tags   
	 */
	public function readContentId($contentIds = array()) {	
		$parsedData = array();
		$sth = $this->database->dbh->prepare("	
			select
				tag.id
				, tag.title
				, tag.description
			from content_meta
                left join tag on tag.id = content_meta.value
			where content_meta.content_id = :content_id
                and content_meta.name = 'tag'
		");
		foreach ($contentIds as $contentId) {
			$this->bindValue($sth, ':content_id', $contentId);
			$this->tryExecute($sth, '88667845');
			if ($sth->rowCount()) {
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$row['url'] = $this->buildUrl(array('tag', $row['title']));
					$row['title_friendly'] = ucwords($row['title']);
					$parsedData[$contentId][] = $row;
				}
			}
		}
		return $this->setData($parsedData);
	}


	public function readById($ids = array())
	{
		$sth = $this->database->dbh->prepare("	
			select
				tag.id
				, tag.title
				, tag.description
			from tag
			where tag.id = ?
		");
		foreach ($ids as $id) {
			$this->bindValue($sth, 1, $id);
			$this->tryExecute($sth, '987967867456');
		}
		return $this->setData($sth->fetchAll(PDO::FETCH_ASSOC));
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


	/**
	 * reads a single tag name and returns the content ids
	 * @param  strgin $tagName 
	 * @return int          
	 */
	public function read($tagName = '') {	
		$query = "	
			select
				id
				, title
				, description
			from tag
		";
		if ($tagName) {
			$query .= "
			where
				tag.title like :tag_name
			";
		}
		$sth = $this->database->dbh->prepare($query);
		if ($tagName) {
			$this->bindValue($sth, ':tag_name', $tagName);
		}
		$this->tryExecute($sth, '90213203830');
		return $this->setData($sth->fetchAll(PDO::FETCH_ASSOC));
	}


	/**
	 * deletes tags based on id/ids
	 * @param  array  $ids 
	 * @return int      
	 */
	public function deleteById($ids = array()) {
		$sth = $this->database->dbh->prepare("	
			delete from tag
			where id = ? 
		");
		foreach ($ids as $id) {
			$this->bindValue($sth, 1, $id);
			$this->tryExecute($sth, 'tag.deleteById');
		}
		return $sth->rowCount();
	}
}
