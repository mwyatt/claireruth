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

class Controller_Admin_Ajax_Media extends Controller
{


	/**
	 * default display for the media browser
	 */
	public function read() {
		$modelMedia = new model_media($this->database, $this->config);
		$modelMedia->read();
		$this->view
			->setObject('media', $modelMedia)
			->renderTemplate('_media');
	}


	/**
	 * handles the uploading of data
	 * @return string the forms and error messages required to update
	 */
	public function upload() {
		$file = new File($this->database, $this->config);
		$file->setTypesAcceptable(array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/jpeg', 'image/pjpeg', 'application/pdf'));

		// success
		if ($file->upload('media', $_FILES)) {
			echo 'ok';
		}

		echo '<pre>';
		print_r($file);
		echo '</pre>';
		exit;
		
	}
}
