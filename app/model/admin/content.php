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
class Model_Admin_Content extends Model
{


	public function create() {	
		$user = new Model_user($this->database, $this->config);
		$sth = $this->database->dbh->prepare("
			insert into content (
				title
				, html
				, type
				, date_published
				, status
				, user_id
			)
			values (
				:title
				, :html
				, :type
				, :date_published
				, :status
				, :user_id
			)
		");				
		$sth->execute(array(
			':title' => $_POST['title']
			, ':html' => (array_key_exists('html', $_POST) ? $_POST['html'] : '')
			, ':type' => $_POST['type']
			, ':date_published' => time()
			, ':status' => ($this->isChecked('status') ? 'visible' : 'hidden')
			, ':user_id' => $user->get('id')
		));		
		$lastId = $this->database->dbh->lastInsertId();
		$this->addAttachment($lastId);
		if ($sth->rowCount()) {
			$this->session->set('feedback', ucfirst($_POST['type']) . ' "' . $_POST['title'] . '" created. <a href="' . $this->config->getUrl('back') . '">Back to list</a>');
			$this->createTotal();
			return $lastId;
		}
		$this->session->set('feedback', 'Problem while creating ' . ucfirst($_POST['type']));
		return false;
	}
			

	public function addAttachment($contentId) {

		// tag
		$maincontentmany = new model_content_many($this->database, $this->config, 'tag');
		$maincontentmany->deleteByContentId($contentId);
		if (array_key_exists('tag', $_POST)) {
			$maincontentmany->create($contentId, $_POST['tag']);
		}

		// media
		$maincontentmany = new model_content_many($this->database, $this->config, 'media');
		$maincontentmany->deleteByContentId($contentId);
		if (array_key_exists('media', $_POST)) {
			$maincontentmany->create($contentId, $_POST['media']);
		}
	}


	public function update() {
		$user = new Model_user($this->database, $this->config);
		$this->addAttachment($_GET['edit']);
		
		// the content
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
			$_GET['edit']
		));		
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = $this->database->dbh->prepare("
			update content set
				title = ?
				, html = ?
				, status = ?
			where
				id = ?
		");				
		$sth->execute(array(
			(array_key_exists('title', $_POST) ? $_POST['title'] : '')
			, (array_key_exists('html', $_POST) ? $_POST['html'] : '')
			, ($this->isChecked('status') ? 'visible' : 'hidden')
			, (array_key_exists('edit', $_GET) ? $_GET['edit'] : '')
		));		
		$this->session->set('feedback', ucfirst($row['type']) . ' "' . $row['title'] . '" updated. <a href="' . $this->config->getUrl('current_noquery') . '">Back to list</a>');
		$this->createTotal();
		return true;
	}


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