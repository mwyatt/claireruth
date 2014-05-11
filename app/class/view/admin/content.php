<?php

/**
 * functions here can be repeated for ease of use in various areas, ajax
 * normal control etc
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class View_Admin_Content extends Config
{


	// public function create()
	// {
	// 	$modelLog = new model_log($this);
	// 	$modelContent = new model_content($this);
	// 	$sessionFeedback = new session_feedback($this);
	// 	if ($modelContent->create($_POST)) {
	// 		$createOrUpdateId = $this->database->dbh->lastInsertId();
	// 		$modelLog->log('admin', 'post created');
	// 		$sessionFeedback->set(ucfirst($_POST['type']) . ' "' . $_POST['title'] . '" created. <a href="' . $this->url->getCache('back') . '">Back to list</a>');

	// 		$this->route('base', 'admin/content/' . $this->url->getPathPart(2) . '/?edit=' . $lastInsertId);
	// 	} else {
	// 		$sessionFeedback->set('Problem while creating ' . ucfirst($_POST['type']));
	// 		$this->route('base', 'admin/content/' . $this->url->getPathPart(2) . '/');
	// 	}
	// }


	/**
	 * removes all meta assigned to the current content item and reassigns
	 * the new meta bindings
	 * @param  string $metaName identifies the type of meta
	 */
	public function updateMeta($metaName)
	{
		$modelContentMeta = new model_content_meta($this);
		$success = false;
		if (array_key_exists($metaName . '_attached', $_POST)) {
			$molds = array();
			foreach ($_POST[$metaName . '_attached'] as $value) {
				$mold = new mold_content_meta();
				$mold->content_id = $_GET['edit'];
				$mold->name = $metaName;
				$mold->value = $value;
				$molds[] = $mold;
			}
			$success = $modelContentMeta->create($molds);
		}
		return $success;
	}


	public function update()
	{
		$modelLog = new model_log($this);
		$modelContent = new model_content($this);
		$sessionFeedback = new session_feedback($this);
		$modelContentMeta = new model_content_meta($this);
		$modelContentMeta->delete(array(
			'where' => array(
				'content_id' => $_GET['edit']
			)
		));
		$mold = new mold_content();
		$mold->title = $_POST['title'];
		$mold->slug = $this->urlFriendly($_POST['slug']);
		$mold->html = $_POST['html'];
		$mold->type = $_POST['type'];
		$mold->time_published = $_POST['time_published'];
		$mold->status = $_POST['status'];
		$modelContent->update($mold, array(
			'where' => array(
				'id' => $_GET['edit']
			)
		));
		$this->updateMeta('media');
		$this->updateMeta('tag');
			$modelLog->log('admin', 'post updated');
			$sessionFeedback->set('Content updated. <a href="' . $this->url->getCache('current_sans_query') . '">Back to list</a>');
		$this->route('current');
	}


	public function archive()
	{
		$modelLog = new model_log($this);
		$modelContent = new model_content($this);
		$sessionFeedback = new session_feedback($this);
		if ($modelContent->update(
			array('status' => 'archive')
			, array('id' => $_GET['archive'])
		)) {
			$sessionFeedback->set('Content archived successfully');
			$modelLog->log('admin', 'post archived');
		} else {
			$sessionFeedback->set('Problem archiving content');
		}
		$this->route('current_noquery');
	}


	public function delete()
	{
		$modelLog = new model_log($this);
		$modelContent = new model_content($this);
		$sessionFeedback = new session_feedback($this);
		if ($modelContent->delete(array(
			'id' => $_GET['delete']
		))) {
			$sessionFeedback->set('Content deleted successfully');
			$modelLog->log('admin', 'post deleted');
		} else {
			$sessionFeedback->set('Problem deleting content');
		}
		$this->route('current_noquery');
	}


	public function edit()
	{
		$modelContent = new model_content($this);
		$modelContent->read(array(
			'where' => array(
				'type' => $this->url->getPathPart(2),
				'id' => $_GET['edit']
			)
		));
		$modelContent->bindMeta('media');
		$modelContent->bindMeta('tag');
		if (! $modelContent->getData()) {
			$this->route('current_noquery');
		}
		return $modelContent->getDataFirst();
	}


	public function create()
	{
		$modelLog = new model_log($this);
		$modelContent = new model_content($this);
		$sessionFeedback = new session_feedback($this);
		$sessionHistory = new session_history($this);
		$mold = new mold_content();
		$mold->title = 'Untitled';
		$mold->slug = '';
		$mold->html = '';
		$mold->type = 'post';
		$mold->time_published = time();
		$mold->user_id = 0;
		$mold->status = 'draft';
		$modelContent->create(array($mold));
		$this->route($sessionHistory->getLast() . '?edit=' . $modelContent->getLastInsertId());
	}
} 
