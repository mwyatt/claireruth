<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Model_Media extends Model
{
	

	public $fields = array(
		'id'
		, 'title'
		, 'description'
		, 'path'
		, 'type'
		, 'time_published'
		, 'user_id'
	);


	/**
	 * base location for all media
	 * which is added after the website
	 * is installed
	 * @var string
	 */
	public $dir = 'media/upload/';


	/**
	 * @param  array $molds 
	 * @return bool       
	 */
	public function create($molds = array())
	{
        $sth = $this->database->dbh->prepare('
            insert into ' . $this->getIdentity() . ' (
            	title
            	, description
            	, path
            	, type
            	, time_published
            	, user_id
        	)
            values (?, ?, ?, ?, ?, ?)
        ');
        foreach ($molds as $mold) {
			$this->tryExecute(__METHOD__, $sth, array(
				$mold->title
				, $mold->description
				, $mold->path
				, $mold->type
	            , time()
				, $mold->user_id
	        ));
        }
        return $sth->rowCount();
	}	





	/**
	 * @param  array  $properties (id => ?, array(key => value))
	 * @return bool             
	 */
	public function update($id, $mold)
	{
		$sth = $this->database->dbh->prepare('
			update ' . $this->getIdentity() . ' set
				title = ?
				, description = ?
				, path = ?
				, type = ?
				, user_id = ?
			where id = ?
		'); 
		$this->tryExecute(__METHOD__, $sth, array(
			$mold->title
			, $mold->description
			, $mold->path
			, $mold->type
			, $mold->user_id
			, $id
		));
        return $sth->rowCount();
	}


	/**
	 * needs testing
	 * @param  array  $ids 
	 * @return int      
	 */
	public function delete($ids = array())
	{
		foreach ($ids as $id) {
			$filePath = BASE_PATH . $this->getDataFirst('path');
			if (! file_exists($filePath) || ! unlink($filePath)) {
				return;
			}
			parent::delete($ids);
		}
		return $sth->rowCount();
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
				$parsedResults[] = $result;
			}
		}
		return $this->setData($parsedResults);
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
		$files = $this->tidyFiles($files['media']);
		$sessionAdminUser = new session_admin_user($this->database, $this->config);
		$errorData = array();
		$successData = array();
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
				$moldName = $model->getMoldName();
				$mold = new $moldName();
				$mold->title = $fileInformation['basename'];
				$mold->description = $fileInformation['basename'];
				$mold->path = $filePathWithoutBase;
				$mold->type = $file['type'];
				$mold->time_published = time();
				$mold->user_id = $sessionAdminUser->getData('id');
				$this->create(array($mold));

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
}
