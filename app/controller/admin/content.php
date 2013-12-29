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
		$content = new model_content($this->database, $this->config);
		$contentMeta = new model_content_meta($this->database, $this->config);
		$sessionFeedback = new session_feedback($this->database, $this->config);
		$sessionAdminUser = new session_admin_user($this->database, $this->config);

		// get content status always
		$this->view->setObject('contentStatus', $content->getStatus());

		// create
		if (array_key_exists('create', $_POST)) {
			if ($content->create($_POST)) {
				$createOrUpdateId = $this->database->dbh->lastInsertId();

				// feedback
				$userAction->lazyCreate(array(
					'description' => ucfirst($_POST['type']) . ' / ' . $_POST['title']
					, 'user_id' => $sessionAdminUser->getData('id')
					, 'action' => 'create'
				));
				$sessionFeedback->set(ucfirst($_POST['type']) . ' "' . $_POST['title'] . '" created. <a href="' . $this->config->getUrl('back') . '">Back to list</a>');
				$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/?edit=' . $lastInsertId);
			} else {
				$sessionFeedback->set('Problem while creating ' . ucfirst($_POST['type']));
				$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/');
			}
		}

		// update
		if (array_key_exists('update', $_POST)) {
			if ($content->update($_GET['edit'], $_POST)) {
				$createOrUpdateId = $_GET['edit'];

				// wipe all existing tags and media assignments
				$contentMeta->deleteByContentIdAndName($createOrUpdateId, 'tag');
				$contentMeta->deleteByContentIdAndName($createOrUpdateId, 'media');

				// feedback
				$userAction->lazyCreate(array(
					'description' => ucfirst($_POST['type']) . ' / ' . $_POST['title']
					, 'user_id' => $sessionAdminUser->getData('id')
					, 'action' => 'update'
				));
				$sessionFeedback->set('Content updated. <a href="' . $this->config->getUrl('current_noquery') . '">Back to list</a>');
			} else {
				$sessionFeedback->set('Problem updating ' . $_POST['type'] . ', ' . $_POST['title']);
			}
			$this->route('current');
		}

		// archive
		if (array_key_exists('archive', $_GET)) {
			if ($content->lazyUpdate(
				array('status' => 'archive')
				, array('id' => $_GET['archive'])
			)) {
				$sessionFeedback->set('Content archived successfully');
				$userAction->lazyCreate(array(
					'description' => 'content ' . $_GET['archive']
					, 'user_id' => $sessionAdminUser->getData('id')
					, 'action' => 'archive'
				));
			} else {
				$sessionFeedback->set('Problem archiving content');
			}
			$this->route('current_noquery');
		}

		// delete
		if (array_key_exists('delete', $_GET)) {
			if ($content->delete(array('id' => $_GET['delete']))) {
				// $contentMany = new model_content_meta($this->database, $this->config, 'content_tag');
				// $contentMany->delete(array('content_id', $_GET['delete']));
				// $contentMany->setTableName('content_media');
				// $contentMany->delete(array('content_id', $_GET['delete']));
				$sessionFeedback->set('Content archived successfully');
				$userAction->lazyCreate(array(
					'description' => 'content ' . $_GET['archive']
					, 'user_id' => $sessionAdminUser->getData('id')
					, 'action' => 'archive'
				));
			} else {
				$sessionFeedback->set('Problem archiving content');
			}
			$this->route('current_noquery');
		}

		// any post or get event
		if (
			array_key_exists('create', $_POST)
			|| array_key_exists('update', $_POST)
			|| array_key_exists('delete', $_GET)
			|| array_key_exists('archive', $_GET)
		) {
			$content->createTotal();
		}

		// any create or update event
		if (
			array_key_exists('create', $_POST)
			|| array_key_exists('update', $_POST)
		) {

			// apply tag assignemtns
			if (array_key_exists('tag', $_POST)) {
				$contentMeta->create($createOrUpdateId, 'tag', $_POST['tag']);
			}

			// apply media assignemtns
			if (array_key_exists('media', $_POST)) {
				$contentMeta->create($createOrUpdateId, 'media', $_POST['media']);
			}
		}

		// edit
		if (array_key_exists('edit', $_GET)) {
			if (! $content->read($this->config->getUrl(2), false, array($_GET['edit']))) {
				$this->route('current_noquery');
			}
			$this->view
				->setObject('model_content', $content->getDataFirst())
				->loadTemplate('admin/content/create-update');
		}

		// create draft entry and redirect to edit page
		if ($this->config->getUrl(3) == 'new') {
			$sessionHistory = new session_history($this->database, $this->config);
			$content->create(array(
				'title' => 'Untitled'
				, 'html' => ''
				, 'type' => 'post'
				, 'status' => 'draft'
			));
			$this->route($sessionHistory->getLast() . '?edit=' . $content->getLastInsertId());
		}
	}


	public function index() {
		$this->view->loadTemplate('admin/dashboard');
	}


	public function page() {
		$this->post();
	}


	public function post() {
		$content = new model_content($this->database, $this->config);
		$content->read($this->config->getUrl(2));
		$this->view
			->setObject($content)
			->loadTemplate('admin/content/list');
	}
}
