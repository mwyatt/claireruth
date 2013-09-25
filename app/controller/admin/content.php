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

/*
$model = new model($this->database, $this->config, 'user_action');
if ($model->create(
	array(
		'description' => array(
			'value' => 'example2'
			, 'required' => true
		)
		, 'user_id' => 'example2'
		, 'time' => 'example2'
		, 'action' => 'example2'
	)
)) {
	echo 'success!';
}


 */
	public function initialise() {
		$userAction = new model($this->database, $this->config, 'user_action');
		$content = new model_content($this->database, $this->config, 'content');
		if (array_key_exists('form_create', $_POST)) {
			if ($content->create(array(
				':title' => $_POST['title']
				, ':html' => (array_key_exists('html', $_POST) ? $_POST['html'] : '')
				, ':type' => $_POST['type']
				, ':date_published' => time()
				, ':status' => ($this->isChecked('status') ? 'visible' : 'hidden')
				, ':user_id' => $user->get('id')
			))) {
				$content->addAttachment($this->database->dbh->lastInsertId());
				$this->session->set('feedback', ucfirst($_POST['type']) . ' "' . $_POST['title'] . '" created. <a href="' . $this->config->getUrl('back') . '">Back to list</a>');
				$content->createTotal();
				$userAction->create(array(
					'description' => ucfirst($_POST['type']) . ' / ' . $_POST['title']
					, 'user_id' => $this->session->get('user', 'id')
					, 'action' => 'create'
				));
				$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/?edit=' . $id);
			} else {
				$this->session->set('feedback', 'Problem while creating ' . ucfirst($_POST['type']));
				$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/');
			}
		}
		if (array_key_exists('form_update', $_POST)) {
			if ($content->update(
				array(
					(array_key_exists('title', $_POST) ? $_POST['title'] : '')
					, (array_key_exists('html', $_POST) ? $_POST['html'] : '')
					, ($this->isChecked('status') ? 'visible' : 'hidden')
					, (array_key_exists('edit', $_GET) ? $_GET['edit'] : '')
				)
				, array('id', $_GET['edit'])
			)) {
				$this->addAttachment($_GET['edit']);
				$userAction->create(array(
					'description' => ucfirst($_POST['type']) . ' / ' . $_POST['title']
					, 'user_id' => $this->session->get('user', 'id')
					, 'action' => 'update'
				));
				$this->session->set('feedback', 'Content updated. <a href="' . $this->config->getUrl('current_noquery') . '">Back to list</a>');
				$this->createTotal();
			} else {
				$this->session->set('feedback', 'Problem updating ' . $_POST['type'] . ', ' . $_POST['title']);
			}
			$this->route('current');
		}
		if (array_key_exists('edit', $_GET)) {
			$content->read('post', 0, $_GET['edit']);
			$this->view
				->setObject($content)
				->loadTemplate('admin/content/create-update');
		}
		if (array_key_exists('delete', $_GET)) {
			if ($content->delete(array('id', $_GET['edit']))) {
				$this->session->set('feedback', 'Content deleted successfully');

				$userAction->create(array(
					'description' => 'content ' . $_GET['delete']
					, 'user_id' => $this->session->get('user', 'id')
					, 'action' => 'delete'
				));
			} else {
				$this->session->set('feedback', 'Problem deleting content');
			}
			$this->route('current_noquery');
		}
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
	