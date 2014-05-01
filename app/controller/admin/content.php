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
		echo '<pre>';
		print_r($_POST);
		echo '</pre>';
		exit;
		
		$viewAdminContent = new view_admin_content($this->database, $this->config);
		$modelLog = new model_log($this->database, $this->config);
		$modelContent = new model_content($this->database, $this->config);
		$sessionFeedback = new session_feedback($this->database, $this->config);
		$cache = new cache($this->database, $this->config);

		// get content status always
		$this->view->setObject('content_status', $modelContent->getStatus());

		// create draft entry and redirect to edit page
		if ($this->config->getUrl(3) == 'new') {
			$viewAdminContent->create();
		}

		// update
		if (array_key_exists('update', $_POST)) {
			$viewAdminContent->update();
		}

		// archive
		if (array_key_exists('archive', $_GET)) {
			$viewAdminContent->archive();
		}

		// delete
		if (array_key_exists('delete', $_GET)) {
			$viewAdminContent->delete();
		}

		// any post or get event
		if (
			array_key_exists('create', $_POST)
			|| array_key_exists('update', $_POST)
			|| array_key_exists('delete', $_GET)
			|| array_key_exists('archive', $_GET)
		) {
			$cacheKey = 'ceil-content-' . $this->config->getUrl(2);
			$cache->delete($cacheKey);
			$cache->create($cacheKey);
		}

		// edit
		if (array_key_exists('edit', $_GET)) {
			$this->view
				->setObject('content', $viewAdminContent->edit())
				->renderTemplate('admin/content/update');
		}
	}


	public function index() {
		$this->view->renderTemplate('admin/dashboard');
	}


	public function page() {
		$this->post();
	}


	public function post() {
		$content = new model_content($this->database, $this->config);
		$content->read(array(
			'where' => array('type' => $this->config->getUrl(2)),
			'order_by' => 'time_published desc'
		));
		$this->view
			->setObject('contents', $content)
			->renderTemplate('admin/content/list');
	}
}
