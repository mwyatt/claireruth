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

class Controller_Ajax extends Controller
{

	
	public function index() {
		// $this->config->getObject('Route')->home();
	}


	public function tagManagement() {
		if (! array_key_exists('query', $_GET)) {
			return;
		}
		$maincontentTag = new model_maincontent_tag($this->database, $this->config);
		$maincontentTag->readUniqueLike($_GET['query']);
		$this->view
			->setObject($maincontentTag)
			->loadTemplate('admin/ajax/tag/query-result');
	}


	public function mediaBrowser() {
		$this->load(array('ajax', 'mediabrowser'), $this->config->getUrl(2), $this->view, $this->database, $this->config);
	}


	/**
	 * outputs the requested data as json code
	 * @param  array $data 
	 * @return null       echos out the json data
	 */
	public function out($data) {
		if (! empty($data)) {
			echo json_encode($data);
		}
		exit;
	}
}
