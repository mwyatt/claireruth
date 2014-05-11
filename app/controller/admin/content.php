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
		$viewAdminContent = new view_admin_content($this);
		$modelLog = new model_log($this);
		$modelContent = new model_content($this);
		$sessionFeedback = new session_feedback($this);
		$cache = new cache($this);

		// get content status always
		$this->view->setObject('content_status', $modelContent->getStatus());

		// any post or get event
		if (
			array_key_exists('create', $_POST)
			|| array_key_exists('update', $_POST)
			|| array_key_exists('delete', $_GET)
			|| array_key_exists('archive', $_GET)
		) {
			$cache->delete('home-latest-posts');
			$cacheKey = 'ceil-content-' . $this->url->getPathPart(2);
			$cache->read($cacheKey);
			$cache->delete($cacheKey);
			$modelContent->read(array(
				'where' => array(
					'type' => $this->url->getPathPart(2),
					'status' => 'visible'
				)
			));
			$cache->create(count($modelContent->getData()));
		}

		// create draft entry and redirect to edit page
		if ($this->url->getPathPart(3) == 'new') {
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
		$content = new model_content($this);
		$content->read(array(
			'where' => array('type' => $this->url->getPathPart(2)),
			'order_by' => 'time_published desc'
		));
		$this->view
			->setObject('contents', $content)
			->renderTemplate('admin/content/list');
	}
}
