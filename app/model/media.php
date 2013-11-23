<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Model_Media extends Model
{
	

	/**
	 * base location for all media
	 * which is added after the website
	 * is installed
	 * @var string
	 */
	public $dir = 'media/upload/';


	public function read()
	{
		$baseurl = $this->config->getUrl('base'); 
		$parsedData = array();
		$sth = $this->database->dbh->prepare("	
			select
				media.id
				, media.title
				, media.description
				, concat('$baseurl', '$this->dir', media.path) as path
				, media.type
				, media.time_published
				, concat(user.first_name, ' ', user.last_name) as user_full_name
			from media
				left join user on user.id = media.user_id
		");
		$this->tryExecute($sth, '12315514344124');
		if ($sth->rowCount()) {
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
				$row = $this->buildThumb($row);
				$parsedData[] = $row;
			}
		}
		return $this->setData($parsedData);
	}


	public function readById($ids = array())
	{
		$baseurl = $this->config->getUrl('base'); 
		$parsedData = array();
		$sth = $this->database->dbh->prepare("	
			select
				media.id
				, media.title
				, media.description
				, concat('$baseurl', '$this->dir', media.path) as path
				, media.type
				, media.time_published
				, concat(user.first_name, ' ', user.last_name) as user_full_name
			from media
				left join user on user.id = media.user_id
			where media.id = ?
		");
		foreach ($ids as $id) {
			$this->bindValue($sth, 1, $id);
			$this->tryExecute($sth, '12315514344124');
			if ($sth->rowCount()) {
				$parsedData[] = $this->buildThumb($sth->fetch(PDO::FETCH_ASSOC));
			}
		}
		return $this->setData($parsedData);
	}


	/**
	 * reads out all media
	 * @return int total rows bringing through
	 */
	public function readByContentId($contentIds = array()) {	
		$baseurl = $this->config->getUrl('base'); 
		$parsedData = array();
		$sth = $this->database->dbh->prepare("	
			select
				media.id
				, media.title
				, media.description
				, concat('$baseurl', '$this->dir', media.path) as path
				, media.type
				, media.time_published
				, concat(user.first_name, ' ', user.last_name) as user_full_name
			from content_meta
                left join media on media.id = content_meta.value
				left join user on user.id = media.user_id
			where content_meta.content_id = :content_id
                and content_meta.name = 'media'
		");
		foreach ($contentIds as $contentId) {
			$this->bindValue($sth, ':content_id', $contentId);
			$this->tryExecute($sth, '88667845');
			if ($sth->rowCount()) {
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$row = $this->buildThumb($row);
					$parsedData[$contentId][] = $row;
				}
			}
		}
		return $this->setData($parsedData);
	}	
	

	/**
	 * appends thumbnail information if it is an image
	 * @param array $row modified row
	 */
	public function buildThumb($row)
	{
		if ($row['type'] != 'application/pdf') {
			$row['thumb']['300'] = $this->buildUrl(array('thumb/?src=' . $row['path'] . '&w=300&h=130'), false);
			$row['thumb']['150'] = $this->buildUrl(array('thumb/?src=' . $row['path'] . '&w=150&h=120'), false);
			$row['thumb']['350'] = $this->buildUrl(array('thumb/?src=' . $row['path'] . '&w=350&h=220'), false);
			$row['thumb']['760'] = $this->buildUrl(array('thumb/?src=' . $row['path'] . '&w=760&h=540'), false);
		}
		return $row;
	}


	/**
	 * looking for $_FILES['media'], uploads and creates entries in db
	 * any errors are stored in session->feedback_array
	 * @return array 	mass array filled with all the data which
	 *                  would be found in the database anyway
	 *                  can be used to generate the form for ajax
	 *                  uploader
	 */
	public function upload($files) {
		if (! $files) {
			return;
		}
		$sessionAdminUser = new session_admin_user($this->database, $this->config);
		$errorData = array();
		$successData = array();
		if (empty($files) || ! array_key_exists('media', $files)) {
			return;
		}
		$files = $this->tidyFiles($files['media']);
		$sthMedia = $this->database->dbh->prepare("
			insert into media (
				title
				, description
				, path
				, type
				, time_published
				, user_id
			)
			values (
				?, ?, ?, ?, ?, ?
			)
		");		
		foreach ($files as $key => $file) {
			$valid = true;
			$fileInformation = pathinfo($file['name']);
			$filePath = BASE_PATH . $this->dir . $this->urlFriendly($fileInformation['filename']) . '.' . $fileInformation['extension'];
			$filePathWithoutBase = $this->urlFriendly($fileInformation['filename']) . '.' . $fileInformation['extension'];
			$fileNameFriendly = $this->urlFriendly($fileInformation['filename']);

			// any error at all
			if ($file['error']) {
				$errorData[$key] = array(
					'name' => $fileNameFriendly
					, 'message' => 'general error found'
				);
				continue;
			}

			// file must be image or pdf
			if (
				$file['type'] != 'image/gif'
				&& $file['type'] != 'image/png'
				&& $file['type'] != 'image/jpeg'
				&& $file['type'] != 'image/pjpeg'
				&& $file['type'] != 'image/jpeg'
				&& $file['type'] != 'image/pjpeg'
				&& $file['type'] != 'application/pdf'
			) {
				$errorData[$key] = array(
					'name' => $fileNameFriendly
					, 'message' => 'file must be .gif, .jpg, .png or .pdf'
				);
				continue;
			}

			// check for duplication
			if (file_exists($filePath)) {
				$errorData[$key] = array(
					'name' => $fileNameFriendly
					, 'message' => '"' . $fileInformation['filename'] . '" already exists, please rename it'
				);
				// $this->session->set('feedback', '');
				continue;
			}

			// check its not too big
			if ($file['size'] > 5000000 /* 5mb */) {
				$errorData[$key] = array(
					'name' => $fileNameFriendly
					, 'message' => 'file is too big'
				);
				// $this->session->set('feedback', '');
				continue;
			}

			// check it is possible to move from tmp
			if (! move_uploaded_file($file['tmp_name'], $filePath)) {
				$errorData[$key] = array(
					'name' => $fileNameFriendly
					, 'message' => 'while moving the temporary file an error occured, try again'
				);
				continue;
			}

			// store if all is ok
			if ($valid) {
				$inserData = array(
					$fileInformation['basename']
					, $fileInformation['basename']
					, $filePathWithoutBase
					, $file['type']
					, time()
					, $sessionAdminUser->getData('id')
				);

				// database
				$sthMedia->execute($inserData);

				// for feedback
				$successData[] = array(
					'name' => $fileInformation['basename']
					, 'message' => 'Successfully uploaded'
				);
			}
		}
		return $this->setData(array(
			'success' => $successData
			, 'error' => $errorData
		));
	}


	public function deleteById($ids = array()) {
		$sth = $this->database->dbh->prepare("	
			delete from media
			where id = ? 
		");
		foreach ($ids as $id) {
			$this->bindValue($sth, 1, $id);
			if ($this->tryExecute($sth, '12315514344124')) {
				$this->readById(array($id));
				unlink(BASE_PATH . $this->dir . $this->getDataFirst('path'));
			}
		}
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


	public function readByPath($path) {	
		$sth = $this->database->dbh->prepare("	
			select
				id
				, title
				, path
				, time_published
				, user_id
			from media
			where media.path like ?
		");
		$sth->execute(array('%' . $path . '%'));
		$this->data = $sth->fetchAll(PDO::FETCH_OBJ);
		return $sth->rowCount();
	}	
}
