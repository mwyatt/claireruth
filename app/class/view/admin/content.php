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
	// 	$modelLog = new model_log($this->database, $this->config);
	// 	$modelContent = new model_content($this->database, $this->config);
	// 	$sessionFeedback = new session_feedback($this->database, $this->config);
	// 	if ($modelContent->create($_POST)) {
	// 		$createOrUpdateId = $this->database->dbh->lastInsertId();
	// 		$modelLog->log('admin', 'post created');
	// 		$sessionFeedback->set(ucfirst($_POST['type']) . ' "' . $_POST['title'] . '" created. <a href="' . $this->config->getUrl('back') . '">Back to list</a>');

	// 		$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/?edit=' . $lastInsertId);
	// 	} else {
	// 		$sessionFeedback->set('Problem while creating ' . ucfirst($_POST['type']));
	// 		$this->route('base', 'admin/content/' . $this->config->getUrl(2) . '/');
	// 	}
	// }


	public function update()
	{
		$modelLog = new model_log($this->database, $this->config);
		$modelContent = new model_content($this->database, $this->config);
		$sessionFeedback = new session_feedback($this->database, $this->config);
		if ($modelContent->update($_GET['edit'], $_POST)) {
			$createOrUpdateId = $_GET['edit'];

			// feedback
			$modelLog->log('admin', 'post updated');
			$sessionFeedback->set('Content updated. <a href="' . $this->config->getUrl('current_noquery') . '">Back to list</a>');
		} else {
			$sessionFeedback->set('Problem updating ' . $_POST['type'] . ', ' . $_POST['title']);
		}
		$this->route('current');
	}


	public function archive()
	{
		$modelLog = new model_log($this->database, $this->config);
		$modelContent = new model_content($this->database, $this->config);
		$sessionFeedback = new session_feedback($this->database, $this->config);
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
		$modelLog = new model_log($this->database, $this->config);
		$modelContent = new model_content($this->database, $this->config);
		$sessionFeedback = new session_feedback($this->database, $this->config);
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
		$modelContent = new model_content($this->database, $this->config);
		$result = $modelContent->read(array(
			'where' => array(
				'type' => $this->config->getUrl(2),
				'id' => $_GET['edit']
			)
		));
		if (! $result) {
			$this->route('current_noquery');
		}
		return $modelContent->getDataFirst();
	}


	public function create()
	{
		$modelLog = new model_log($this->database, $this->config);
		$modelContent = new model_content($this->database, $this->config);
		$sessionFeedback = new session_feedback($this->database, $this->config);
		$sessionHistory = new session_history($this->database, $this->config);
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
