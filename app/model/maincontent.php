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
class Model_Maincontent extends Model
{	


	/**
	 * reads any and all content stored in this table
	 * a number of custom parameters can be used to
	 * bring in differing result sets
	 * @param  string $type  the type of content
	 * @param  string $limit the amount of content required
	 * @return null        data property will be set
	 */
	public function read($where = '', $limit = 0, $id = 0) {	
		$sth = $this->database->dbh->prepare("	
			select
				main_content.id
				, main_content.title
				, main_content.html
				, main_content.type
				, main_content.date_published
				, main_content.status
				, main_content.user_id
				, main_content_tag.id as tag_id
				, main_content_tag.tag_id as tag_name
				, main_media.id as media_id
				, main_media.date_published as media_date_published
				, main_media.path as media_path
				, main_media.title as media_title
				, concat(main_user.first_name, ' ', main_user.last_name) as user_name
			from main_content
			left join main_user on main_user.id = main_content.user_id
            left join main_content_tag on main_content_tag.content_id = main_content.id
            left join main_content_media on main_content_media.content_id = main_content.id
            left join main_media on main_media.id = main_content_media.media_id
            where main_content.id != ''
			" . ($this->config->getUrl(0) == 'admin' ? '' : ' and main_content.status = \'visible\'') . "
			" . ($where ? ' and main_content.type = :type ' : '') . "
			" . ($id ? ' and main_content.id = :id ' : '') . "

			order by main_content.date_published desc
			" . ($limit ? ' limit 0, :limit ' : '') . "
		");
		if ($id) {
			$sth->bindValue(':id', $id, PDO::PARAM_STR);
		}
		if ($where) {
			$sth->bindValue(':type', $where, PDO::PARAM_STR);
		}
		if ($limit) {
			$sth->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
		}
		$sth->execute();				
		$mainmedia = new model_mainmedia($this->database, $this->config);
		foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
			if (! array_key_exists($row['id'], $this->data)) {
				$row['guid'] = $this->getGuid('post', $row['title'], $row['id']);
				$this->data[$row['id']] = $row;
			}
			if (array_key_exists('tag_name', $this->data[$row['id']]) && $row['tag_name']) {
				$this->data[$row['id']]['tag'][$row['tag_id']] = array(
					'id' => $row['tag_id']
					, 'name' => $row['tag_name']
					, 'guid' => $this->getGuid('tag', $row['tag_name'])
				) ;
			}
			if (array_key_exists('media_id', $this->data[$row['id']]) && $row['media_id']) {
				$this->data[$row['id']]['media'][$row['media_id']] = array(
					'id' => $row['media_id']
					, 'title' => $row['media_title']
					, 'date_published' => $row['media_date_published']
					, 'path' => $this->getGuid('media', $mainmedia->dir . $row['media_path'])
					, 'thumb_150' => $this->getGuid('thumb', $this->config->getUrl('base') . $mainmedia->dir . $row['media_path'] . '&w=150&h=120')
					, 'thumb_350' => $this->getGuid('thumb', $this->config->getUrl('base') . $mainmedia->dir . $row['media_path'] . '&w=350&h=220')
					, 'thumb_760' => $this->getGuid('thumb', $this->config->getUrl('base') . $mainmedia->dir . $row['media_path'] . '&w=760&h=540')
				) ;
			}
		}
		if ($id) {
			$this->data = current($this->data);
		}
		return $sth->rowCount();		
	}	


	public function readByType($type, $limit = 0) {	
		$sth = $this->database->dbh->prepare("	
			select
				main_content.id
				, main_content.title
				, main_content.html
				, main_content.date_published
				, main_content.status
				, main_content.type
			from main_content
			left join main_user on main_user.id = main_content.user_id
			where main_content.type = :type and main_content.status = 'visible'
			order by main_content.date_published desc
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
				main_content.id
				, main_content.title
				, main_content.html
				, main_content.date_published
				, main_content.status
				, main_content.type
			from main_content
			left join main_user on main_user.id = main_content.user_id
			where main_content.title like ? and main_content.status = 'visible'
			order by main_content.date_published desc
		");
		$sth->execute(array('%' . current($title) . '%'));	
		$this->data = $this->setMeta($sth->fetchAll(PDO::FETCH_ASSOC));
		return $this->data = current($this->data);
	}	


	public function readById($id) {	
		$sth = $this->database->dbh->prepare("	
			select
				main_content.id
				, main_content.title
				, main_content.html
				, main_content.date_published
				, main_content.status
				, main_content.type
			from main_content
			left join main_user on main_user.id = main_content.user_id
			where main_content.id = :id and main_content.status = 'visible'
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
				main_content.id
				, main_content.title
				, main_content.title_slug
				, main_content.html
				, main_content.date_published
				, main_content.guid
				, main_content.status
				, main_content.type
			from main_content
			left join main_user on main_user.id = main_content.user_id
			where
				main_content.title_slug = :title_slug
				and
				main_content.type = 'page'
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
