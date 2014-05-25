<?php

/**
 * ajax
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Controller_Admin_Ajax_Tag extends Controller_Admin
{


	public function run()
	{
		$this->runMethod(3);
	}


	/**
	 * default display for the tag browser
	 */
	public function read() {
		$model = new model_tag($this);
		$model->readUniqueLike();
		$this->view
			->setObject('tags', $model)
			->getTemplate('admin/_tags');
	}


	public function searching() {
		if (! array_key_exists('query', $_GET)) {
			return;
		}
		$model = new model_tag($this);
		$model->readSearch($_GET['query']);
		$this->view
			->setObject('dropTemplate', '_tags')
			->setObject('tags', $model)
			->getTemplate('_drop');
	}


	/**
	 * outputs the id of the found tag, or newly created one
	 */
	public function create() {
		if (! array_key_exists('title', $_GET)) {
			exit;
		}
		$tagNewTitle = $_GET['title'];
		$model = new model_tag($this);
		$model->read(array(
			'where' => array(
				'title' => $tagNewTitle
			)
		));
		if ($mold = $model->getDataFirst()) {
			$this->view->setObject('tag', $mold);
		} else {
			$mold = new mold_tag();
			$mold->title = $tagNewTitle;
			$mold->description = '';
			$insertIds = $model->create(array($mold));
			$model->read(array(
				'where' => array(
					'id' => $insertIds
				)
			));
			$mold = $model->getDataFirst();
			$this->view->setObject('tag', $mold);
		}
		$this->view->getTemplate('_tag');
	}
}
