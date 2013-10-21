<?php

/**
 * admin
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Controller_Admin_Content extends Controller
{


	/**
	 * handles crud for all content
	 */
	public function initialise() {
		$userAction = new model($this->database, $this->config, 'user_action');
		$content = new model_content($this->database, $this->config, 'content');
		$user = new model_user($this->database, $this->config);

		// create
		if (array_key_exists('form_create', $_POST)) {
			if ($content->create(array(
				'title' => $_POST['title']
				, 'html' => (array_key_exists('html', $_POST) ? $_POST['html'] : '')
				, 'type' => $_POST['type']
				, 'date_published' => time()
				, 'status' => (array_key_exists('status', $_POST) ? $_POST['status'] : 'hidden')
				, 'user_id' => $user->get('id')
			))) {
				$lastInsertId = $this->database->dbh->lastInsertId();
				$content->addAttachment($lastInsertId);
				$content->createTotal();
				$userAction->create(array(
					'description' => ucfirst($_POST['type']) . ' / ' . $_POST['title']
					, 'user_id' => $this->session->get('user', 'id')
					, 'action' => 'create'
				));
				$this->session->set('feedback', ucfirst($_POST['type']) . ' "' . $_POST['title'] . '" created. <a href="' . $this->config->getUrl('back') . '">Back to list</a>');
				$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/?edit=' . $lastInsertId);
			} else {
				$this->session->set('feedback', 'Problem while creating ' . ucfirst($_POST['type']));
				$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/');
			}
		}

		// update
		if (array_key_exists('form_update', $_POST)) {
			if ($content->update(
				array(
					'title' => (array_key_exists('title', $_POST) ? $_POST['title'] : '')
					, 'html' => (array_key_exists('html', $_POST) ? $_POST['html'] : '')
					, 'status' => (array_key_exists('status', $_POST) ? $_POST['status'] : 'hidden')
				)
				, array('id' => $_GET['edit'])
			)) {
				$content->addAttachment($_GET['edit']);
				$userAction->create(array(
					'description' => ucfirst($_POST['type']) . ' / ' . $_POST['title']
					, 'user_id' => $this->session->get('user', 'id')
					, 'action' => 'update'
				));
				$this->session->set('feedback', 'Content updated. <a href="' . $this->config->getUrl('current_noquery') . '">Back to list</a>');
				$content->createTotal();
			} else {
				$this->session->set('feedback', 'Problem updating ' . $_POST['type'] . ', ' . $_POST['title']);
			}
			$this->route('current');
		}

		// edit
		if (array_key_exists('edit', $_GET)) {
			if ($content->read($this->config->getUrl(2), false, array($_GET['edit']))) {
				$content = $content->getData();
				$content = current($content);
				$this->view
					->setObject('model_content', $content)
					->loadTemplate('admin/content/create-update');
			} else {
				$this->route('current_noquery');
			}
		}

		// archive
		if (array_key_exists('archive', $_GET)) {
			if ($content->update(
				array(
					'status' => 'archive'
				)
				, array('id' => $_GET['archive'])
			)) {
				// $contentMany = new model_content_meta($this->database, $this->config, 'content_tag');
				// $contentMany->delete(array('content_id', $_GET['delete']));
				// $contentMany->setTableName('content_media');
				// $contentMany->delete(array('content_id', $_GET['delete']));
				$content->createTotal();
				$this->session->set('feedback', 'Content archived successfully');
				$userAction->create(array(
					'description' => 'content ' . $_GET['archive']
					, 'user_id' => $this->session->get('user', 'id')
					, 'action' => 'archive'
				));
			} else {
				$this->session->set('feedback', 'Problem archiving content');
			}
			$this->route('current_noquery');
		}

		// new
		if ($this->config->getUrl(3) == 'new') {
			$this->view->loadTemplate('admin/content/create-update');
		}
	}


	public function index() {
		$this->view->loadTemplate('admin/dashboard');
	}


	public function page() {
		$content = new model_content($this->database, $this->config);
		$content->read($this->config->getUrl(2));
		$this->view
			->setObject($content)
			->loadTemplate('admin/content/list');
	}


	public function post() {
		$content = new model_content($this->database, $this->config);
		$content->read($this->config->getUrl(2));
		$this->view
			->setObject($content)
			->loadTemplate('admin/content/list');
	}
}
	