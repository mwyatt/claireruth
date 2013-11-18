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

class Controller_Admin_Ajax_Content_Meta extends Controller
{


	public function read() {
		echo '<pre>';
		print_r('variable');
		echo '</pre>';
		exit;
		
		$modelTag = new model_tag($this->database, $this->config);
		$modelTag->readUniqueLike();
		$this->view
			->setObject('tags', $modelTag)
			->loadTemplate('admin/_tags');
	}
}
