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
class Model_Admin_Maincontent extends Model
{


	public function readByType($type) {	
		$sth = $this->database->dbh->prepare("	
			select
				main_content.id
				, main_content.title
				, main_content.html
				, main_content.date_published
				, main_content.status
				, main_content.type
				, main_content_meta.name as meta_name
				, main_content_meta.value as meta_value
			from main_content
			left join main_content_meta on main_content_meta.content_id = main_content.id
			left join main_user on main_user.id = main_content.user_id
			where main_content.type = :type
			order by main_content.date_published desc
		");
		$sth->execute(array(
			':type' => $type
		));	
		return $this->data = $this->setMeta($sth->fetchAll(PDO::FETCH_ASSOC));
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
				, main_content_meta.name as meta_name
				, main_content_meta.value as meta_value
			from main_content
			left join main_content_meta on main_content_meta.content_id = main_content.id
			left join main_user on main_user.id = main_content.user_id
			where main_content.id = :id
		");
		$sth->execute(array(
			':id' => $id
		));	
		$result = $this->setMeta($sth->fetchAll(PDO::FETCH_ASSOC));
		$result = current($result);
		return $this->data = $result;
	}


	public function create() {	
		$user = new Model_Mainuser($this->database, $this->config);
		if (! $this->validatePost(array('title'))) {
			$this->session->set('feedback', 'All required fields must be filled');
			return false;
		}
		$sth = $this->database->dbh->prepare("
			insert into main_content (
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
		if ($sth->rowCount()) {
			$this->session->set('feedback', ucfirst($_POST['type']) . ' "' . $_POST['title'] . '" created. <a href="' . $this->config->getUrl('back') . '">Back to list</a>');
			return $lastId;
		}
		$this->session->set('feedback', 'Problem while creating ' . ucfirst($_POST['type']));
		return false;
	}
			
				
	public function update() {
		$user = new Model_Mainuser($this->database, $this->config);
		$sth = $this->database->dbh->prepare("
			select 
				title
				, html
				, type
				, date_published
				, status
				, user_id
			from main_content
			where id = ?
		");				
		$sth->execute(array(
			$_GET['edit']
		));		
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = $this->database->dbh->prepare("
			update main_content set
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
			from main_content
			where id = ?
		");	
		$sth->execute(array(
			$id
		));		
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = $this->database->dbh->prepare("
			delete from main_content
			where id = ? 
		");				
		$sth->execute(array(
			$id
		));		
		$sth = $this->database->dbh->prepare("
			delete from main_content_meta
			where content_id = ? 
		");				
		$sth->execute(array(
			$id
		));		
		$this->session->set('feedback', ucfirst($row['type']) . ' "' . $row['title'] . '" deleted');
		return true;
	}


}