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
			->setObject('medias', $modelMedia)
			->getTemplate('admin/_medias');
	}


	/**
	 * handles the uploading of data
	 * @return string the forms and error messages required to update
	 */
	public function upload() {
		$modelMedia = new model_media($this->database, $this->config);
		$modelMedia->upload($_FILES);
		$this
			->view
			->setObject($modelMedia)
			->getTemplate('admin/media/return');
	}


	/**
	 * lightbox view for attaching media
	 */
	public function lightbox() {
		$modelMedia = new model_media($this->database, $this->config);
		$modelMedia->read();
		$this->view
			->setObject('medias', $modelMedia)
			->getTemplate('admin/media/lightbox');
	}


	// public function getDirectory() {
	// 	$handler = glob($this->basePath . $this->currentPath . '*', GLOB_MARK);
	// 	$items = array(
	// 		'folder' => array()
	// 		, 'file' => array()
	// 	);
	// 	foreach ($handler as $key => $handle) {
	// 		$fileInfo = pathinfo($handle);
	// 		if (is_dir($handle)) {
	// 			$items['folder'][$key] = $fileInfo;
	// 		} else {
	// 			$items['file'][$key] = $fileInfo;
	// 			$items['file'][$key]['path'] = $handle;
	// 			$items['file'][$key]['guid'] = $this->guid . $this->currentPath . $fileInfo['basename'];
	// 		}
	// 	}
	// 	$this->out($items);
	// }


	// public function createFolder() {
	// 	if (! is_file($this->basePath . $this->currentPath)) {
	// 	    if (mkdir($this->basePath . $this->currentPath)) {
	// 			$this->out($this->currentPath);
	// 	    }
	// 	}
	// }


	// public function removeFolder() {
	// 	if (is_dir($this->basePath . $this->currentPath)) {
	// 		if (rmdir($this->basePath . $this->currentPath)) {
	// 			$this->out($this->currentPath);
	// 		}
	// 	}
	// }


	// public function removeFile() {
	// 	if (file_exists($this->basePath . $this->currentPath)) {
	// 	    if (unlink($this->basePath . $this->currentPath)) {
	// 			$this->out($this->currentPath);
	// 	    }
	// 	}
	// }


	// public function validate($path) {
	// 	if (strpos($path, '..') !== false) {
	// 		exit;
	// 	}
	// 	return $this->currentPath = $path;
	// }
}
