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

class Controller_Admin_Ajax_Tag extends Controller
{


	/**
	 * default display for the tag browser
	 */
	public function read() {
		$model = new model_tag($this->database, $this->config);
		$model->readUniqueLike();
		$this->view
			->setObject('tags', $model)
			->getTemplate('admin/_tags');
	}


	public function search() {
		if (! array_key_exists('query', $_GET)) {
			return;
		}
		$words = ;
		$where = array();
		foreach (explode(' ', $_GET['query']) as $word) {
			$where
		}
		$model = new model_tag($this->database, $this->config);
		$model->read(array(
			'where' => array(
				'title' => '%something%'
			)
		));
		$this->view
			->setObject('tags', $model)
			->getTemplate('admin/_tags');
	}


	/**
	 * outputs the id of the found tag, or newly created one
	 */
	public function create() {
		if (! array_key_exists('title', $_GET)) {
			exit;
		}
		$tagNewTitle = $_GET['title'];
		$model = new model_tag($this->database, $this->config);
		$model->read(array(
			'where' => array(
				'title' => $tagNewTitle
			)
		));
		if ($mold = $model->getDataFirst()) {
			exit($mold->id);
		}
		$mold = new mold_tag();
		$mold->title = $tagNewTitle;
		$mold->description = '';
		$insertIds = $model->create(array($mold));
		echo current($insertIds);
	}
}
