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

class Controller_Admin_Media extends Controller_Admin
{


	public function initialise()
	{
		$modelMedia = new model_media($this);
		$sessionFeedback = new session_feedback($this);

		// update
		if (array_key_exists('update', $_POST)) {
			if ($modelMedia->lazyUpdate(
				array(
					'title' => (array_key_exists('title', $_POST) ? $_POST['title'] : '')
					, 'description' => (array_key_exists('description', $_POST) ? $_POST['description'] : '')
				)
				, array('id' => $_GET['edit'])
			)) {
				$sessionFeedback->set(ucfirst($this->url->getPathPart(1)) . ' updated. <a href="' . $this->url->getCache('current_sans_query') . '">Back to list</a>');
			} else {
				$sessionFeedback->set('Problem updating ' . $this->url->getPathPart(1) . ' ' . $_POST['title']);
			}
			$this->route('current');
		}

		// edit
		if (array_key_exists('edit', $_GET)) {
			if ($modelMedia->readById(array($_GET['edit']))) {
				$this->view
					->setObject('model_media', $modelMedia->getDataFirst())
					->getTemplate('admin/media/update');
			} else {
				$this->route('current_sans_query');
			}
		}

		// delete
		if (array_key_exists('delete', $_GET)) {
			if ($modelMedia->deleteById(array($_GET['delete']))) {
				$modelContentMeta = new model_content_meta($this);
				$modelContentMeta->deleteByValue($this->url->getPathPart(1), $_GET['delete']);
				$sessionFeedback->set(ucfirst($this->url->getPathPart(1)) . ' deleted successfully');
			} else {
				$sessionFeedback->set('Problem deleting ' . $this->url->getPathPart(1));
			}
			$this->route('current_sans_query');
		}
	}

	public function index() {
		$modelMedia = new model_media($this);
		$modelMedia->read();
		$this->view
			->setObject($modelMedia)
			->getTemplate('admin/media/list');
	}
}
