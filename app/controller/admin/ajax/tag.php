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
		$modelTag = new model_tag($this->database, $this->config);
		$modelTag->readUniqueLike();
		$this->view
			->setObject('tags', $modelTag)
			->getTemplate('admin/_tags');
	}


	public function search($compatibility = false) {
		if (! array_key_exists('query', $_GET)) {
			return;
		}
		$modelTag = new model_tag($this->database, $this->config);
		$modelTag->readUniqueLike($_GET['query']);
		$this->view
			->setObject('tags', $modelTag)
			->getTemplate('admin/_tags');
	}


	public function create() {
		if (! array_key_exists('title', $_GET)) {
			exit;
		}
		$modelTag = new model_tag($this->database, $this->config);
		if (! $modelTag->create(array(
			'title' => $_GET['title']
			, 'description' => ''
		))) {
			exit;
		}
		$modelTag->read($_GET['title']);
		$this->view
			->setObject('tags', $modelTag)
			->getTemplate('admin/_tags');
	}
}
