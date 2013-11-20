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

class Controller_Admin_Tag extends Controller
{


	public function initialise()
	{
		$modelTag = new model_tag($this->database, $this->config);
		$sessionFeedback = new session_feedback($this->database, $this->config);

		// updated
		if (array_key_exists('update', $_POST)) {
			if ($modelTag->update(
				array(
					'title' => (array_key_exists('title', $_POST) ? $_POST['title'] : '')
					, 'description' => (array_key_exists('description', $_POST) ? $_POST['description'] : '')
				)
				, array('id' => $_GET['edit'])
			)) {
				$sessionFeedback->set(ucfirst($this->config->getUrl(1)) . ' updated. <a href="' . $this->config->getUrl('current_noquery') . '">Back to list</a>');
			} else {
				$sessionFeedback->set('Problem updating ' . $this->config->getUrl(1) . ' ' . $_POST['title']);
			}
			$this->route('current');
		}

		// edit
		if (array_key_exists('edit', $_GET)) {
			if ($modelTag->readById(array($_GET['edit']))) {
				$this->view
					->setObject('model_tag', $modelTag->getDataFirst())
					->loadTemplate('admin/tag/update');
			} else {
				$this->route('current_noquery');
			}
		}

		// delete
		if (array_key_exists('delete', $_GET)) {
			if ($modelTag->deleteById(array($_GET['delete']))) {
				$modelContentMeta = new model_content_meta($this->database, $this->config);
				$modelContentMeta->deleteByValue($this->config->getUrl(1), $_GET['delete']);
				$sessionFeedback->set(ucfirst($this->config->getUrl(1)) . ' deleted successfully');
			} else {
				$sessionFeedback->set('Problem deleting ' . $this->config->getUrl(1));
			}
			$this->route('current_noquery');
		}
	}

	public function index() {
		$modelTag = new model_tag($this->database, $this->config);
		$modelTag->read();
		$this->view
			->setObject($modelTag)
			->loadTemplate('admin/tag/list');
	}
}
