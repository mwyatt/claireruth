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


	/**
	 * handles crud for all content
	 */
	public function initialise() {}


	public function index() {
		$modelMedia = new model_media($this->database, $this->config);
		$modelMedia->read();
		$this->view
			->setObject($modelMedia)
			->loadTemplate('admin/media/all');
	}


	public function page() {
		$content = new model_content($this->database, $this->config);
		$content->read($this->config->getUrl(2));
		$this->view
			->setObject($content)
			->loadTemplate('admin/content/list');
	}


	public function post() {
		$content = new model_content($this->database, $this->config);
		$content->read($this->config->getUrl(2));
		$this->view
			->setObject($content)
			->loadTemplate('admin/content/list');
	}
}
	