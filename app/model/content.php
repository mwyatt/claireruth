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


	// public function create() {	
	// 	$user = new Model_user($this->database, $this->config);
	// 	$sth = $this->database->dbh->prepare("
	// 		insert into content (
	// 			title
	// 			, html
	// 			, type
	// 			, date_published
	// 			, status
	// 			, user_id
	// 		)
	// 		values (
	// 			:title
	// 			, :html
	// 			, :type
	// 			, :date_published
	// 			, :status
	// 			, :user_id
	// 		)
	// 	");				
	// 	$sth->execute(array(
	// 		':title' => $_POST['title']
	// 		, ':html' => (array_key_exists('html', $_POST) ? $_POST['html'] : '')
	// 		, ':type' => $_POST['type']
	// 		, ':date_published' => time()
	// 		, ':status' => ($this->isChecked('status') ? 'visible' : 'hidden')
	// 		, ':user_id' => $user->get('id')
	// 	));		
	// 	$lastId = $this->database->dbh->lastInsertId();
	// 	$this->addAttachment($lastId);
	// 	if ($sth->rowCount()) {
	// 		$this->session->set('feedback', ucfirst($_POST['type']) . ' "' . $_POST['title'] . '" created. <a href="' . $this->config->getUrl('back') . '">Back to list</a>');
	// 		$this->createTotal();
	// 		return $lastId;
	// 	}
	// 	$this->session->set('feedback', 'Problem while creating ' . ucfirst($_POST['type']));
	// 	return false;
	// }
			

	public function addAttachment($contentId) {

		// tag
		$content = new model_content_many($this->database, $this->config, 'content_tag');
		$content->delete(array('content_id', $contentId));
		if (array_key_exists('tag', $_POST)) {
			foreach ($_POST['tag'] as $tag) {
				$colVals[] = array($contentId, $tag);
			}
			$content->create($colVals);
		}

		// media
		$content->setTableName('content_media');
		$content->delete(array('content_id', $contentId));
		if (array_key_exists('tag', $_POST)) {
			foreach ($_POST['media'] as $media) {
				$colVals[] = array($contentId, $media);
			}
			$content->create($colVals);
		}
	}


	// public function update() {
	// 	$user = new Model_user($this->database, $this->config);
	// 	$this->addAttachment($_GET['edit']);
		
	// 	// the content
	// 	$sth = $this->database->dbh->prepare("
	// 		select 
	// 			title
	// 			, html
	// 			, type
	// 			, date_published
	// 			, status
	// 			, user_id
	// 		from content
	// 		where id = ?
	// 	");				
	// 	$sth->execute(array(
	// 		$_GET['edit']
	// 	));		
	// 	$row = $sth->fetch(PDO::FETCH_ASSOC);
	// 	$sth = $this->database->dbh->prepare("
	// 		update content set
	// 			title = ?
	// 			, html = ?
	// 			, status = ?
	// 		where
	// 			id = ?
	// 	");				
	// 	$sth->execute(array(
	// 		(array_key_exists('title', $_POST) ? $_POST['title'] : '')
	// 		, (array_key_exists('html', $_POST) ? $_POST['html'] : '')
	// 		, ($this->isChecked('status') ? 'visible' : 'hidden')
	// 		, (array_key_exists('edit', $_GET) ? $_GET['edit'] : '')
	// 	));		
	// 	$this->session->set('feedback', ucfirst($row['type']) . ' "' . $row['title'] . '" updated. <a href="' . $this->config->getUrl('current_noquery') . '">Back to list</a>');
	// 	$this->createTotal();
	// 	return true;
	// }


	public function deleteById($id) {
		$sth = $this->database->dbh->prepare("
			select 
				title
				, html
				, type
				, date_published
				, status
				, user_id
			from content
			where id = ?
		");	
		$sth->execute(array(
			$id
		));		
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = $this->database->dbh->prepare("
			delete from content
			where id = ? 
		");				
		$sth->execute(array(
			$id
		));		
		
		// tag
		$mainContentTag = new model_content_tag($this->database, $this->config);
		$mainContentTag->deleteByContentId($contentId);

		// media
		$mainContentMedia = new model_content_media($this->database, $this->config);
		$mainContentMedia->deleteByContentId($contentId);
		$this->session->set('feedback', ucfirst($row['type']) . ' "' . $row['title'] . '" deleted');
		$this->createTotal();
		return true;
	}


	/**
	 * @return array   full set of month-year -> ids
	 */
	public function readByMonth($monthYear = false)
	{
		$sth = $this->database->dbh->query("	
			select
				content.id
				, content.date_published
			from content
			where
				content.type = 'post'
			order by
				content.date_published desc
		");
		$singleSetOfIds = array();
		$fullMonthTree = array();
		foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
			if ($monthYear) {
				if (strtolower(date('F-Y', $row['date_published'])) == $monthYear) {
					$singleSetOfIds[strtolower(date('F-Y', $row['date_published']))][] = $row['id'];
				}
			} else {
				$fullMonthTree[strtolower(date('F-Y', $row['date_published']))][] = $row['id'];
				
			}
		}
		if ($singleSetOfIds) {		
			$this->read('post', false, current($singleSetOfIds));
			return $this->getData();
		}
		if ($fullMonthTree) {		
			return $fullMonthTree;
		}
		return false;
	}


	/**
	 * sets the total rowcount in options table
	 * @return bool 
	 */
	public function createTotal()
	{
		$sth = $this->database->dbh->query("	
			select
				content.id
			from content
			where
				content.status = 'visible'
		");
		$modelOptions = new Model_options($this->database, $this->config);
		return $modelOptions->create(array('model_content_rowcount' => $sth->rowCount()));
	}
}
