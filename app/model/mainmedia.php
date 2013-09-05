<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Model_Mainmedia extends Model
{
	

	/**
	 * base location for all media
	 * which is added after the website
	 * is installed
	 * @var string
	 */
	public $dir = 'media/upload/';


	/**
	 * reads out all media
	 * @return int total rows bringing through
	 */
	public function read() {	
		$baseurl = $this->config->getUrl('base');
		$sth = $this->database->dbh->query("	
			select
				main_media.id
				, main_media.title
				, concat('$baseurl', '$this->dir', main_media.path) as path
				, main_media.date_published
				, concat(main_user.first_name, ' ', main_user.last_name) as user_full_name
			from main_media
			left join main_user on main_user.id = main_media.user_id
		");
		$this->data = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $sth->rowCount();
	}	
	

	/**
	 * passed $_FILES, uploads and creates entries in db
	 * @return bool true on success
	 */
	public function create() {
		$files = $_FILES;
		if (empty($files) || ! array_key_exists('media', $files)) {
			return;
		}
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
			$filePath = BASE_PATH . $this->dir . $fileInformation['basename'];

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
}
