<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Model_Mainmedia extends Model
{
	
	
	protected $tick = 0;


	public $upload;


	public $error;


	// public $thumb = array(
	// 	'small' => array(
	// 		'width' => 250, 'height' => 250
	// 	),
	// 	'medium' => array(
	// 		'width' => 450, 'height' => 450
	// 	),
	// 	'large' => array(
	// 		'width' => 650, 'height' => 650
	// 	)
	// );


	public $dir = 'media/upload/';

	
	public function deleteById($id) {	
		// $sth = $this->database->dbh->prepare("
		// 	select 
		// 		id
		// 		, title
		// 		, path
		// 		, date_published
		// 		, user_id
		// 	from main_media
		// 	where id = ?
		// ");	
		// $sth->execute(array($id));		
		// $row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = $this->database->dbh->prepare("
			delete from main_media
			where id = ? 
		");				
		$sth->execute(array($id));		
		$sth = $this->database->dbh->prepare("
			delete from main_content_meta
			where content_id = ? and name = 'media'
		");				
		$sth->execute(array($id));		
		$this->session->set('feedback', 'media was deleted');
		return $sth->rowCount();
	}


	public function read() {	
		$sth = $this->database->dbh->query("	
			select
				id
				, title
				, path
				, date_published
				, user_id
			from main_media
		");
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$row['guid'] = $this->getGuid('media', $row['path'], $this->dir);
			$this->data[] = $row;
		}
		return $sth->rowCount();
	}	


	public function readById($ids) {	
		if (! is_array($string = $ids)) {
			$ids = array();
			$ids[] = $string;
		}
		$sth = $this->database->dbh->prepare("	
			select
				id
				, title
				, path
				, date_published
				, user_id
			from main_media
			where id = ?
		");
		foreach ($ids as $id) {
			$sth->execute(array($id));
			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$row['guid'] = $this->getGuid('media', $row['path'], $this->dir);
			$this->data[] = $row;
		}
		return $sth->rowCount();
	}


	public function readByPath($path) {	
		$sth = $this->database->dbh->prepare("	
			select
				id
				, title
				, path
				, date_published
				, user_id
			from main_media
			where main_media.path like ?
		");
		$sth->execute(array('%' . $path . '%'));
		$this->data = $sth->fetchAll(PDO::FETCH_OBJ);
		return $sth->rowCount();
	}	


	public function setData($rows) {
		foreach ($rows as $key => $row) {
			$rows[$key]['guid'] = $this->getGuid('media', $row['basename'], $this->dir);
		}
		return $rows;
	}
	
	
	public function getExtension($val)
	{	
		$val = explode('.', $val); // split via '.'
		$val = end($val);
		$val = strtolower('.'.$val); // .JPG -> .jpg
		return ($val ? $val : false);	
	}
	
	
	public function getTitle($val)
	{	
		$val = explode('.', $val); // split via '.
		array_pop($val);
		$val = implode($val);
		return ($val ? $val : false);			
	}
	
	public function getFilename($val)
	{	
		$val = explode('.', $val); // split via '.
		array_pop($val);
		$val = implode($val);
		$val = $this->getObject('View')->urlFriendly($val); // convert to friendly
		return ($val ? $val : false);			
	}
	
	
	public function getError()
	{	
		return ($this->error ? $this->error : false);
	}
	
	
	public function getUpload()
	{	
		return ($this->upload ? $this->upload : false);
	}
	

	/**
	 * tidies up the files array to more readable format
	 * @param  array $array $_FILES['media'] preferrably
	 * @return array        the sorted array
	 */
	public function tidyFiles($array) {	
		foreach($array as $key => $files) {
			foreach($files as $i => $val) {
				$new[$i][$key] = $val;    
			}    
		}
		return $new;
	}
	

	/**
	 * uploads the files and attaches them to the content id provided
	 * @param  int $id the id of the recently created content
	 * @return bool  
	 */
	public function uploadAttach($id) {
		$files = $_FILES;
		if (empty($files) || ! array_key_exists('media', $files)) {
			return;
		}
		$uploadPath = BASE_PATH . $this->dir;
		$files = $this->tidyFiles($files['media']);
		
		$sthMedia = $this->database->dbh->prepare("
			insert into main_media (
				path
				, date_published
				, user_id
			)
			values (
				:path
				, :date_published
				, :user_id
			)
		");		
		$sthContentMeta = $this->database->dbh->prepare("
			insert into main_content_meta (
				content_id
				, name
				, value
			)
			values (
				:content_id
				, :name
				, :value
			)
		");			
		foreach ($files as $key => $file) {
			$fileInformation = pathinfo($file['name']);
			$filePath = $uploadPath . $fileInformation['basename'];

			if ($file['error']) {
				return false;
			}

			if (
				$file['type'] != 'image/gif'
				&& $file['type'] != 'image/png'
				&& $file['type'] != 'image/jpeg'
				&& $file['type'] != 'image/pjpeg'
				&& $file['type'] != 'image/jpeg'
				&& $file['type'] != 'image/pjpeg'
				&& $file['type'] != 'application/pdf'
			) {
				$this->session->set('feedback', 'File must be .gif, .jpg, .png or .pdf');
				return false;
			}

			if (file_exists($filePath)) {
				$this->session->set('feedback', 'Unable to upload file "' . $file['name'] . '" because it already exists');
				return false;
			}

			if ($file['size'] > 2000000 /* 2mb */) {
				$this->session->set('feedback', 'Unable to upload file "' . $file['name'] . '" because it is too big');
				return false;
			}

			if (! move_uploaded_file($file['tmp_name'], $filePath)) {
				$this->session->set('feedback', 'While moving the temporary file an error occured');
				return false;
			}

			$sthMedia->execute(array(
				':path' => $fileInformation['basename']
				, ':date_published' => time()
				, ':user_id' => $this->session->get('user', 'id')
			));

			$mediaId = $this->database->dbh->lastInsertId();

			$sthContentMeta->execute(array(
				':content_id' => $id
				, ':name' => 'media'
				, ':value' => $mediaId
			));
		}
		return true;
	}
}
