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
		if (! $modelContent->read(array('where' => array('id' => $_GET['archive'])))) {
			return $sessionFeedback->set('Problem archiving content');
		}
		$mold = $modelContent->getDataFirst();
		$mold->status = 'archive';
		if (! $modelContent->update($mold, array(
			'where' => array(
				'id' => $_GET['archive']
			)
		))) {
			return $sessionFeedback->set('Problem archiving content');
		}
		$sessionFeedback->set('Content archived successfully');
		$modelLog->log('admin', 'post archived');
		$this->route('current_sans_query');
	}


	// public function delete()
	// {
	// 	$modelLog = new model_log($this);
	// 	$modelContent = new model_content($this);
	// 	$sessionFeedback = new session_feedback($this);
	// 	if ($modelContent->delete(array(
	// 		'id' => $_GET['delete']
	// 	))) {
	// 		$sessionFeedback->set('Content deleted permanently');
	// 		$modelLog->log('admin', 'post deleted');
	// 	} else {
	// 		$sessionFeedback->set('Problem deleting content');
	// 	}
	// 	$this->route('current_sans_query');
	// }


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
			$this->route('current_sans_query');
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
		$mold->type = $this->url->getPathPart(2);
		$mold->time_published = time();
		$mold->user_id = 0;
		$mold->status = 'draft';
		$modelContent->create(array($mold));
		$this->route($this->url->getCache('admin') . $this->url->getPathPart(1) . '/' . $this->url->getPathPart(2) . '/?edit=' . $modelContent->getLastInsertId());
	}
} 
