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

class Controller_Admin_Tag extends Controller_Admin
{


	public function initialise()
	{
		$modelTag = new model_tag($this);
		$sessionFeedback = new session_feedback($this);

		// updated
		if (array_key_exists('update', $_POST)) {
			if ($modelTag->lazyUpdate(
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
			if ($modelTag->readById(array($_GET['edit']))) {
				$this->view
					->setObject('tag', $modelTag->getDataFirst())
					->getTemplate('admin/tag/update');
			} else {
				$this->route('current_sans_query');
			}
		}

		// delete
		if (array_key_exists('delete', $_GET)) {
			if ($modelTag->deleteById(array($_GET['delete']))) {
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
		$modelTag = new model_tag($this);
		$modelTag->read();
		$this->view
			->setObject($modelTag)
			->getTemplate('admin/tag/list');
	}
}
