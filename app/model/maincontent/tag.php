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
	 * @todo build guid so that tags can be navigated to             
	 */
	public function read($contentId = 0) {	
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, name
			from main_content_tag
			" . ($contentId ? ' where main_content.id = :content_id ' : '') . "
			group by main_content_tag.name
			order by main_content_tag.name desc
		");
		if ($contentId) {
			$sth->bindValue(':content_id', $id, PDO::PARAM_STR);
		}
		$sth->execute(array(
			':id' => $id
		));	
		return $results = $sth->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	 * facilitates the assignment of each tag as an array to the
	 * maincontent row which is passed through, this enables
	 * each method of the maincontent to create the array
	 * @param  array $row 
	 * @return array      
	 */
	public function assign($row) {
		$tags = array();
		if (array_key_exists('tag_name', $row) && $row['tag_name']) {
			$tags[] = array(
				'id' => $row['tag_id']
				, 'name' => $row['tag_name']
				, 'guid' => $this->getGuid('tag', $row['tag_name'])
			) ;
		}
		return $tags;
	}


	public function readUniqueLike($query = '') {	
		if (! $query) {
			return;
		}
		$matches = array();
		$query = htmlspecialchars($query);
		$words = explode(' ', $query);
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, name
			from main_content_tag
			where
				main_content_tag.name like ?
			group by main_content_tag.name
			order by main_content_tag.name desc
		");
		foreach ($words as $word) {
			$sth->execute(array(
				'%' . $word . '%'
			));
			while ($match = $sth->fetch(PDO::FETCH_ASSOC)) {
				$matches[$match['id']] = $match;
			}
		}
		$this->data = $matches;
		return count($this->getData());
	}
}