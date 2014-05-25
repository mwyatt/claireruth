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

class Controller_Admin_Ajax_Content extends Controller_Admin
{

	
	public function run()
	{
		$this->runMethod(3);
	}


	/**
	 * takes a normal title and returns a unique slug by looking at all other
	 * content 
	 * @return string 
	 */
	public function slug() {
		if (! array_key_exists('title', $_GET)) {
			exit;
		}
		$friendlyTitle = $this->urlFriendly($_GET['title']);
		$modelcontent = new model_content($this);
		$success = $modelcontent->read(array(
			'where' => array(
				'slug' => $friendlyTitle
			)
		));
		if ($success) {
			echo $friendlyTitle . '-2';
		} else {
			echo $friendlyTitle;
		}
	}
}
