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

class Controller_Admin_Content extends Controller_Admin
{


	/**
	 * handles crud for all content operations
	 */
	public function run() {
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
			return $this->create();
		}

		// update
		if (array_key_exists('update', $_POST)) {
			return $this->update();
		}

		// archive
		if (array_key_exists('archive', $_GET)) {
			return $this->archive();
		}

		// edit
		if (array_key_exists('edit', $_GET)) {
			return $this->edit();
		}
		$this->content();
	}


	public function content() {
		$content = new model_content($this);
		$statuses = $content->getStatus();
		$where = array(
			'type' => $this->url->getPathPart(2)
		);
		if (array_key_exists('status', $_GET)) {
			if (in_array($_GET['status'], $statuses)) {
				$where['status'] = $_GET['status'];
			}
		} else {
			$where['status'] = 'visible';
		}
		$content->read(array(
			'where' => $where,
			'order_by' => 'time_published desc'
		));
		$this->view
			->setObject('statuses', $statuses)
			->setObject('contents', $content)
			->getTemplate('admin/content/list');
	}


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
		$mold->time_published = strtotime(implode(' ', $_POST['time_published']));
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

		// single
		$content = $modelContent->getDataFirst();
		$this->view
			->setObject('contentDate', date('Y-m-d', $content->time_published))
			->setObject('contentTime', date('G:i', $content->time_published))
			->setObject('content', $content)
			->getTemplate('admin/content/update');
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
