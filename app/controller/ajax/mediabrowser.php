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

class Controller_Ajax_Mediabrowser extends Controller_Ajax
{


	public $guid = 'media/upload/';


	public $basePath = '';


	public $currentPath;


	public function __construct() {
		$this->basePath = BASE_PATH . $this->guid;
		if (array_key_exists('path', $_GET)) {
			$this->validate($_GET['path']);
		}
	}


	public function validate($path) {
		if (strpos($path, '..') !== false) {
			exit;
		}
		return $this->currentPath = $path;
	}


	public function getDirectory() {
		$handler = glob($this->basePath . $this->currentPath . '*', GLOB_MARK);
		$items = array(
			'folder' => array()
			, 'file' => array()
		);
		foreach ($handler as $key => $handle) {
			$fileInfo = pathinfo($handle);
			if (is_dir($handle)) {
				$items['folder'][$key] = $fileInfo;
			} else {
				$items['file'][$key] = $fileInfo;
				$items['file'][$key]['path'] = $handle;
				$items['file'][$key]['guid'] = $this->guid . $this->currentPath . $fileInfo['basename'];
			}
		}
		$this->out($items);
	}


	public function createFolder() {
		if (! is_file($this->basePath . $this->currentPath)) {
		    if (mkdir($this->basePath . $this->currentPath)) {
				$this->out($this->currentPath);
		    }
		}
	}


	public function removeFolder() {
		if (is_dir($this->basePath . $this->currentPath)) {
			if (rmdir($this->basePath . $this->currentPath)) {
				$this->out($this->currentPath);
			}
		}
	}


	public function removeFile() {
		if (file_exists($this->basePath . $this->currentPath)) {
		    if (unlink($this->basePath . $this->currentPath)) {
				$this->out($this->currentPath);
		    }
		}
	}


	public function upload() {
		// foreach ($_FILES['images']['size'] as $size) {
		// 	if ($size > 1000000  1mb ) {
		// 		echo 'Files must be under 1mb each.';
		// 		exit;
		// 	}
		// }
		foreach ($_FILES['images']['type'] as $type) {
			if ($type != 'image/jpeg' && $type != 'image/png' && $type != 'image/gif' && $type != 'application/pdf') {
				echo '<p>Please upload only jpg, png, gif or pdf files.</p>';
				return false;
			}
		}
		foreach ($_FILES["images"]["error"] as $key => $error) {
		    if ($error == UPLOAD_ERR_OK) {
		        $name = strtolower(str_replace(' ', '-', $_FILES["images"]["name"][$key]));
			    move_uploaded_file($_FILES["images"]["tmp_name"][$key], $this->basePath . $this->currentPath . $name);
		    }
		}
	}


}
