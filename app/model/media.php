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


	public function read($properties = array())
	{
		$baseurl = $this->config->getUrl('base'); 
		$parsedData = array();
		$sth = $this->database->dbh->prepare("	
			select
				media.id
				, media.title
				, media.description
				, concat('$this->dir', media.path) as path
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
				, concat('$this->dir', media.path) as path
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
	 * reads media by content id
	 * @param  array  $contentIds 
	 * @return bool             
	 */
	public function readContentId($contentIds = array())
	{	
		$sth = $this->database->dbh->prepare("	
			select
				media.id
				, media.title
				, media.description
				, concat('$this->dir', media.path) as path
				, media.type
				, media.time_published
				, content_meta.content_id
				, concat(user.first_name, ' ', user.last_name) as user_full_name
			from content_meta
                left join media on media.id = content_meta.value
				left join user on user.id = media.user_id
			where content_meta.content_id = :content_id
                and content_meta.name = 'media'
		");
		$results = array();
		foreach ($contentIds as $contentId) {
			$this->bindValue($sth, ':content_id', $contentId);
			$this->tryExecute($sth);
			$results = $sth->fetchAll(PDO::FETCH_CLASS, 'Mold_Media');
			foreach ($results as $result) {
				$result = $this->buildThumb($result);
				$parsedResults[] = $result;
			}
		}
		return $this->setData($parsedResults);
	}	
	

	public function storeResult($sth)
	{
		$results = $sth->fetchAll(PDO::FETCH_CLASS, 'view_media');

		// read all needed media and tags
		// build url
		foreach ($results as $result) {
			$ids[] = $result->id;
			$result->url = $this->buildUrl(array($result->type, $result->title . '-' . $result->id));
		}
		$media = new model_media($this->database, $this->config);
		$medias = $media->readContentId($ids);
		$tag = new model_tag($this->database, $this->config);
		$tags = $tag->readContentId($ids);
		foreach ($results as $key => $result) {
			if ($tags && array_key_exists($result->id, $tags)) {
				$results[$key]->tag = $tags[$result->id];
			}
			if ($medias && array_key_exists($result->id, $medias)) {
				$results[$key]->media = $medias[$result->id];
			}
		}
		return $this->setData($results);
	}



	/**
	 * appends thumbnail information if it is an image
	 * @param array $result modified row
	 */
	public function buildThumb($result)
	{
		if ($result->type != 'application/pdf') {
			$result->thumb = new stdClass();
			$result->thumb->{'300'} = $this->buildUrl(array('thumb/?src=' . $this->config->getUrl('base') . $result->path . '&w=300&h=130'), false);
			$result->thumb->{'150'} = $this->buildUrl(array('thumb/?src=' . $this->config->getUrl('base') . $result->path . '&w=150&h=120'), false);
			$result->thumb->{'350'} = $this->buildUrl(array('thumb/?src=' . $this->config->getUrl('base') . $result->path . '&w=350&h=220'), false);
			$result->thumb->{'760'} = $this->buildUrl(array('thumb/?src=' . $this->config->getUrl('base') . $result->path . '&w=760&h=540'), false);
		}
		return $result;
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

			// get file information before its deleted from db
			$this->readById(array($id));
			$filePath = BASE_PATH . $this->getDataFirst('path');

			// execute sth delete, check file exists, remove file
			// if anything goes wrong, return false
			if (! $this->tryExecute($sth, '12315514344124') || ! is_file($filePath) || ! file_exists($filePath) || ! unlink($filePath)) {
				return;
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
