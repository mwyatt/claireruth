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

class Controller_Admin_Ajax_Content_Meta extends Controller_Admin
{


	public function initialise()
	{
		if (! array_key_exists('content_id', $_GET) || ! array_key_exists('name', $_GET) || ! array_key_exists('values', $_GET)) {
			exit (json_encode(false));
		}
		if (! is_array($_GET['values'])) {
			exit (json_encode(false));
		}
	}


	/**
	 * creates the desired meta key
	 * @return null|string 
	 */
	public function create() {
		$modelContentMeta = new model_content_meta($this);
		if (! $modelContentMeta->create(
			$_GET['content_id']
			, $_GET['name']
			, $_GET['values']
		)) {
			exit (json_encode(false));
		}
		exit (json_encode(true));
	}


	public function delete() {
		$modelContentMeta = new model_content_meta($this);
		if (! $modelContentMeta->delete(
			$_GET['content_id']
			, $_GET['name']
			, $_GET['values']
		)) {
			exit (json_encode(false));
		}
		exit (json_encode(true));
	}
}
