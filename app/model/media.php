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


	/**
	 * reads out all media
	 * @return int total rows bringing through
	 */
	public function read($contentIds = array()) {	
		$baseurl = $this->config->getUrl('base'); 
		$sth = $this->database->dbh->prepare("	
			select
				media.id
				, media.title
				, concat('$baseurl', '$this->dir', media.path) as path
				, media.type
				, media.date_published
				, concat(user.first_name, ' ', user.last_name) as user_full_name
			from media
			left join content_media on content_media.media_id = media.id
			left join user on user.id = media.user_id
			" . ($contentIds ? ' where content_media.content_id = :content_id ' : '') . "
			group by media.id
		");
		if ($contentIds) {
			foreach ($contentIds as $contentId) {
				$sth->execute(array(
					':content_id' => $contentId
				));	
				foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$row = $this->addThumb($row);
					$this->data[$contentId][] = $row;
				}
			}
			return $this->data;
		}
		$sth->execute();				
		foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$row = $this->addThumb($row);
			$this->data[] = $row;
		}
		return $sth->rowCount();
	}	
	

	/**
	 * appends thumbnail information if it is an image
	 * @param array $row modified row
	 */
	public function addThumb($row)
	{
		if ($row['type'] != 'application/pdf') {
			$row['thumb_150'] = $this->buildUrl(array('thumb/?src=' . $row['path'] . '&w=150&h=120'), false);
			$row['thumb_350'] = $this->buildUrl(array('thumb/?src=' . $row['path'] . '&w=350&h=220'), false);
			$row['thumb_760'] = $this->buildUrl(array('thumb/?src=' . $row['path'] . '&w=760&h=540'), false);
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
	public function create($cols = array(), $secondary = array(), $tertiary = array(), $quantenary = array()) {
		$errorMessage = array();
		$successData = array();
		$files = $_FILES;
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
				, date_published
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
				$errorMessage[$fileNameFriendly] = 'general error found';
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
				$errorMessage[$fileNameFriendly] = 'file must be .gif, .jpg, .png or .pdf';
				continue;
			}

			// check for duplication
			if (file_exists($filePath)) {
				$errorMessage[$fileNameFriendly] = '"' . $fileInformation['filename'] . '" already exists, please rename it';
				$this->session->set('feedback', '');
				continue;
			}

			// check its not too big
			if ($file['size'] > 5000000 /* 5mb */) {
				$errorMessage[$fileNameFriendly] = 'file is too big';
				$this->session->set('feedback', '');
				continue;
			}

			// check it is possible to move from tmp
			if (! move_uploaded_file($file['tmp_name'], $filePath)) {
				$errorMessage[$fileNameFriendly] = 'while moving the temporary file an error occured, try again';
				continue;
			}

			// store if all is ok
			if ($valid) {
				$returnDataAndInsert = array(
					$fileInformation['basename']
					, $fileInformation['basename']
					, $filePathWithoutBase
					, $file['type']
					, time()
					, $this->session->get('user', 'id')
				);

				// database
				$sthMedia->execute($returnDataAndInsert);

				// for return information
				$returnDataAndInsert['id'] = $this->database->dbh->lastInsertId();
				$successData[] = $returnDataAndInsert;
			}
		}

		// error messages can be accessed if required
		if ($errorMessage) {
			$this->session->set('feedback_array', $errorMessage);
		}
		return $successData;
	}


	public function deleteById($id) {	
		// $sth = $this->database->dbh->prepare("
		// 	select 
		// 		id
		// 		, title
		// 		, path
		// 		, date_published
		// 		, user_id
		// 	from media
		// 	where id = ?
		// ");	
		// $sth->execute(array($id));		
		// $row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = $this->database->dbh->prepare("
			delete from media
			where id = ? 
		");				
		$sth->execute(array($id));		
		$sth = $this->database->dbh->prepare("
			delete from content_meta
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
			from media
			where id = ?
		");
		foreach ($ids as $id) {
			$sth->execute(array($id));
			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$row['guid'] = $this->buildUrl(array($row['path']), false);
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
			from media
			where media.path like ?
		");
		$sth->execute(array('%' . $path . '%'));
		$this->data = $sth->fetchAll(PDO::FETCH_OBJ);
		return $sth->rowCount();
	}	


	public function setData($rows) {
		foreach ($rows as $key => $row) {
			$rows[$key]['guid'] = $this->buildUrl(array($row['basename']), false);
		}
		return $rows;
	}
}
