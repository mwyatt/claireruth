<?php

/**
 * Responsible for Various content types (Projects, Posts and Pages)
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Content extends Model
{	


	/**
	 * reads any and all content stored in this table
	 * a number of custom parameters can be used to
	 * bring in differing result sets
	 * @param  string $type  the type of content
	 * @param  string $limit the amount of content required
	 * @return null        data property will be set
	 */
	public function read($where = '', $limit = array(), $ids = array()) {	
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.html
				, content.type
				, content.date_published
				, content.status
				, content.user_id
				, concat(user.first_name, ' ', user.last_name) as user_name
			from content
			left join user on user.id = content.user_id
            where content.id != ''
			" . ($this->config->getUrl(0) == 'admin' ? '' : ' and content.status = \'visible\'') . "
			" . ($where ? ' and content.type = :type ' : '') . "
			" . ($ids ? ' and content.id = :id ' : '') . "
			group by content.id
			order by content.date_published desc
			" . ($limit ? ' limit :limit_start, :limit_end ' : '') . "
		");
		if ($where) {
			$sth->bindValue(':type', $where, PDO::PARAM_STR);
		}
		if ($limit) {
			$sth->bindValue(':limit_start', (int) current($limit), PDO::PARAM_INT);
			$sth->bindValue(':limit_end', (int) next($limit), PDO::PARAM_INT);
		}
		if ($ids) {
			foreach ($ids as $id) {
				$sth->bindValue(':id', $id, PDO::PARAM_STR);
				$sth->execute();				
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$contents[] = $row;
				}
			}
		} else {
			$sth->execute();				
			$contents = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		$contentIds = array();
		foreach ($contents as $content) {
			$contentIds[] = $content['id'];
		}
		$mainMedia = new model_media($this->database, $this->config);
		$mainContentTag = new model_content_tag($this->database, $this->config);
		$medias = $mainMedia->read($contentIds);
		$tags = $mainContentTag->read($contentIds);

		// generate guid, append media or tags where applicable
		foreach ($contents as $content) {
			$content['guid'] = $this->getGuid($content['type'], $content['title'], $content['id']);
			$this->data[$content['id']] = $content;
			if (array_key_exists($content['id'], $tags)) {
				$this->data[$content['id']]['tag'] = $tags[$content['id']];
			}
			if (array_key_exists($content['id'], $medias)) {
				$this->data[$content['id']]['media'] = $medias[$content['id']];
			}
		}
		return $sth->rowCount();		
	}	


	public function readByType($type, $limit = 0) {	
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.html
				, content.date_published
				, content.status
				, content.type
			from content
			left join user on user.id = content.user_id
			where content.type = :type and content.status = 'visible'
			order by content.date_published desc
			" . ($limit ? ' limit :limit ' : '') . "
		");
		$sth->bindValue(':type', $type, PDO::PARAM_STR);
		if ($limit) {
			$sth->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
		}
		$sth->execute();
		$this->data = $this->setMeta($sth->fetchAll(PDO::FETCH_ASSOC));
		return $this;
	}	


	public function readByTitle($title) {
		$title = str_replace('-', ' ', $title)	;
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.html
				, content.date_published
				, content.status
				, content.type
			from content
			left join user on user.id = content.user_id
			where content.title like ? and content.status = 'visible'
			order by content.date_published desc
		");
		$sth->execute(array('%' . current($title) . '%'));	
		$this->data = $this->setMeta($sth->fetchAll(PDO::FETCH_ASSOC));
		return $this->data = current($this->data);
	}	


	public function readById($id) {	
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.html
				, content.date_published
				, content.status
				, content.type
			from content
			left join user on user.id = content.user_id
			where content.id = :id and content.status = 'visible'
		");
		$sth->execute(array(
			':id' => $id
		));	
		$result = $this->setMeta($sth->fetchAll(PDO::FETCH_ASSOC));
		$result = current($result);
		return $this->data = $result;
	}


	public function readByTitleSlug($titleSlug) {
		$sth = $this->database->dbh->prepare("	
			select
				content.id
				, content.title
				, content.title_slug
				, content.html
				, content.date_published
				, content.guid
				, content.status
				, content.type
			from content
			left join user on user.id = content.user_id
			where
				content.title_slug = :title_slug
				and
				content.type = 'page'
		");
		$sth->execute(array(
			':title_slug' => $titleSlug
		));	
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			if (! array_key_exists($row['id'], $this->data)) {
				$this->data[$row['id']] = $row;
			}
			if (array_key_exists('meta_name', $row)) {
				$this->data[$row['id']][$row['meta_name']] = $row['meta_value'];
				unset($this->data[$row['id']]['meta_name']);
				unset($this->data[$row['id']]['meta_value']);
			}
		}
		$this->data = current($this->data);
		return $sth->rowCount();
	}
}
