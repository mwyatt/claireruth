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

class Controller_Admin_Media extends Controller
{


	public function index() {
		$this->view->loadTemplate('admin/media-index');
	}


	public function gallery() {
		$this->view->loadTemplate('admin/media-gallery');
	}

	
}
	