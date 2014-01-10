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


	/**
	 * creates the desired meta key
	 * @return null|string 
	 */
	public function create() {
		if (! array_key_exists('content_id', $_GET) || ! array_key_exists('name', $_GET) || ! array_key_exists('value', $_GET)) {
			exit;
		}
		$modelContentMeta = new model_content_meta($this->database, $this->config);
		if (! $modelContentMeta->create(
			$_GET['content_id']
			, $_GET['name']
			, $_GET['value']
		)) {
			exit;
		}
		exit('success');
	}


	public function delete() {
		if (! array_key_exists('content_id', $_GET) || ! array_key_exists('name', $_GET) || ! array_key_exists('value', $_GET)) {
			exit;
		}
		$modelContentMeta = new model_content_meta($this->database, $this->config);
		if (! $modelContentMeta->delete(
			$_GET['content_id']
			, $_GET['name']
			, array($_GET['value'])
		)) {
			exit;
		}
		exit('success');
	}
}
