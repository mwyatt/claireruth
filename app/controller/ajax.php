<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Controller_Ajax extends Controller
{

	
	public function index() {
		$this->route('base');
	}


	/**
	 * use the content id to build the lurrrvee button
	 * @param  integer $contentId 
	 */
	public function love($contentId = 0)
	{
		if (! array_key_exists('content_id', $_GET)) {
			return;
		}
		// if (! $contentId = intval($contentId)) {
		// 	return;
		// }
		$meta = new model_content_meta($this->database, $this->config, 'love');
			echo '<pre>';
			print_r($meta);
			echo '</pre>';
			exit;
		
		$meta->read();
	}
}
