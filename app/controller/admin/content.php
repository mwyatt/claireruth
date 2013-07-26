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


	public function initialise() {
		$userAction = new model_mainuser_action($this->database, $this->config);
		$content = new Model_Admin_Maincontent($this->database, $this->config);
		$media = new Model_Mainmedia($this->database, $this->config);
		if (array_key_exists('form_create', $_POST)) {
			if ($id = $content->create()) {
				$userAction->create($this->session->get('user', 'id'), 'create', ucfirst($_POST['type']) . ' / ' . $_POST['title']);
				$media->uploadAttach($id);
				$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/?edit=' . $id);
			} else {
				$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/');
			}
		}
		if (array_key_exists('form_update', $_POST)) {
			if ($content->update()) {
				$userAction->create($this->session->get('user', 'id'), 'update', ucfirst($_POST['type']) . ' / ' . $_POST['title']);
				$media->uploadAttach($_GET['edit']);
			}
			$this->route('current');
		}
		if (array_key_exists('edit', $_GET)) {
			$content->readById($_GET['edit']);
			if (array_key_exists('media', $content->data)) {
				$media->readById($content->data['media']);
				$this->view->setObject($media);
			}
			$this->view
				->setObject($content)
				->loadTemplate('admin/content/create-update');
		}
		if (array_key_exists('delete', $_GET)) {
			$content->deleteById($_GET['delete']);
			$userAction->create($this->session->get('user', 'id'), 'delete', 'main_content ' . $_GET['delete']);
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
		$content = new Model_Admin_Maincontent($this->database, $this->config);
		$content->readByType($this->config->getUrl(2));
		$this->view
			->setObject($content)
			->loadTemplate('admin/content/list');
	}


	public function minutes() {
		$content = new Model_Admin_Maincontent($this->database, $this->config);
		$content->readByType($this->config->getUrl(2));
		$this->view
			->setObject($content)
			->loadTemplate('admin/content/list');
	}


	public function cup() {
		$content = new Model_Admin_Maincontent($this->database, $this->config);
		$content->readByType($this->config->getUrl(2));
		$this->view
			->setObject($content)
			->loadTemplate('admin/content/list');
	}


	public function press() {
		$content = new Model_Admin_Maincontent($this->database, $this->config);
		$content->readByType($this->config->getUrl(2));
		$this->view
			->setObject($content)
			->loadTemplate('admin/content/list');
	}

	
}
	