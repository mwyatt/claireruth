<?php

/**
 * handles all file operations, including the upload of them
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class File extends Config
{


	/**
	 * base location for all media
	 * which is added after the website
	 * is installed
	 * @var string
	 */
	public $path = 'media/upload/';


	/**
	 * possible types
	 * @var array
	 */
	public $typesPossible = array(
		'image/gif',
		'image/png',
		'image/jpeg',
		'image/pjpeg',
		'image/jpeg',
		'image/pjpeg',
		'application/pdf'
	);


	/**
	 * in megabytes
	 * @var integer
	 */
	public $sizeLimit = 5; 


	/**
	 * acceptable types for this upload
	 * @var array
	 */
	public $typesAcceptable = array();


	/**
	 * feedback storage
	 * @var array
	 */
	public $feedback = array(
		'success' => array(),
		'error' => array()
	);


	/**
	 * gets the full path for the upload folder with optional filename
	 * @param  string $filename 
	 * @return string           
	 */
	public function getPath($filename = '') {
		return BASE_PATH . $this->path . $filename;
	}


	/**
	 * starts a file upload collection, always accepts multiple files
	 * @param  string $formName   the key used in the form
	 * @param  array $fileGroups $_FILES
	 * @return bool             
	 */
	public function upload($formName, $fileGroups) {

		// must pass file initialisation
		if (! $files = $this->initialiseFileGroup($formName, $fileGroups)) {
			return;
		}

		// process each file, upload, or fail
		foreach ($files as $fileId => $file) {
			$this->uploadFile($fileId, $file);
		}

		// all must be successfull to return true here
		return $this->isUploadSuccessfull();
	}


	/**
	 * passes back a int to identify if an upload has been
	 * fully successfull
	 * @return boolean
	 */
	public function isUploadSuccessfull()
	{
		if (count($this->feedback['success']) && ! count($this->feedback['error'])) {
			return true;
		}
	}


	/**
	 * adds a row to the feedback chain
	 * @param int $fileId   
	 * @param string $type     
	 * @param string $fileName 
	 * @param string $message  
	 */
	public function setFeedback($fileId, $type, $message)
	{
		$this->feedback[$type][$fileId][] = $message;
	}


	/**
	 * store all types which are acceptable for this upload
	 * checks against pool of possible file types
	 * @param array $types 
	 */
	public function setTypesAcceptable($types = array())
	{
		foreach ($types as $type) {

			// must be a type that exists
			if (! in_array($type, $this->typesPossible)) {
				continue;
			}

			// acceptable
			$this->typesAcceptable[] = $type;
		}
	}


	/**
	 * sets the size limit for a single file during this upload
	 * in megabytes
	 * @param int $size in mb
	 */
	public function setSizeLimit($size)
	{
		$this->sizeLimit = $size;
	}


	/**
	 * gets the size limit or converts and returns in another format
	 * @param  string $type 'bytes' only so far
	 * @return int       
	 */
	public function getSizeLimit($type = '')
	{

		// 1048576 bytes = 1 mb
		if ($type) {
			return $this->sizeLimit * 1048576;
		}

		// mb
		return $this->sizeLimit;
	}


	/**
	 * find out if the type is acceptable
	 * @param  string  $type 
	 * @return boolean       
	 */
	public function isTypeAcceptable($type)
	{
		return in_array($type, $this->typesAcceptable);
	}


	/**
	 * handy stringify of the acceptable filetypes for feedback
	 * @return string 
	 */
	public function getTypesAcceptableString()
	{
		return implode(', ', $this->typesAcceptable);
	}


	/**
	 * asseses validity of file
	 * uploads the single file, sets feedback
	 * @param  int $fileId ties the file to one key
	 * @param  array $file   soon after converted to StdClass
	 */
	public function uploadFile($fileId, $file)
	{

		// both file and file info converted to objects
		$file = $this->convertArrayToObject($file);
		$fileInformation = $this->convertArrayToObject(pathinfo($file->name));

		// handy variable names
		$friendlyFileName = $this->urlFriendly($fileInformation->filename);
		$friendlyFileNameWithExtension = $friendlyFileName . '.' . $fileInformation->extension;
		$pathFile = $this->getPath($friendlyFileNameWithExtension);

		// error
		if ($file->error) {
			return $this->setFeedback($fileId, 'error', $file->name . ' has error code ' . $file->error . ' and could not be uploaded');
		}

		// is type unacceptable
		if (! $this->isTypeAcceptable($file->type)) {
			return $this->setFeedback($fileId, 'error', $file->name . ' must be of type ' . $this->getTypesAcceptableString());
		}

		// duplicate file
		if (file_exists($pathFile)) {
			return $this->setFeedback($fileId, 'error', $friendlyFileNameWithExtension . ' already exists, please rename the file and try again');
		}

		// check its not too big
		if ($file->size > $this->getSizeLimit('bytes')) {
			return $this->setFeedback($fileId, 'error', $friendlyFileNameWithExtension . ' must be less than ' . $this->getSizeLimit() . 'mb in size');
		}

		// move temporary file
		if (! move_uploaded_file($file->tmp_name, $pathFile)) {
			return $this->setFeedback($fileId, 'error', $friendlyFileNameWithExtension . ' had a problem while moving to the upload folder');
		}

		// create database entry
		$mold = new Mold_Media();
		$mold->title = $fileInformation->basename;
		$mold->description = $fileInformation->basename;
		$mold->path = $friendlyFileNameWithExtension;
		$mold->type = $file->type;
		$mold->time_published = time();
		// $sessionAdminUser = new Session_Admin_User($this);
		// $mold->user_id = $sessionAdminUser->getData('id');
		$mold->user_id = 0;
		$model = new Model_Media($this);
		$model->create(array($mold));

		// success
		$this->setFeedback($fileId, 'success', $friendlyFileNameWithExtension . ' was successfully uploaded');
	}


	/**
	 * tidies up the files array to more readable format
	 * @param  array $array $fileGroups[$key] preferrably
	 * @return array        the sorted files
	 */
	public function initialiseFileGroup($formName, $fileGroups) {

		// expected form name not found
		if (! array_key_exists($formName, $fileGroups)) {
			return;
		}

		// tidy up the files to be worked on easily
		$initialisedFiles = array();
		foreach ($fileGroups[$formName] as $associativeName => $details) {
			foreach ($details as $fileKey => $detail) {
				$initialisedFiles[$fileKey][$associativeName] = $detail;
			}
		}
		return $initialisedFiles;
	}


	public function delete($filePaths = array())
	{
		 // is_uploaded_file($_FILES['image1']['tmp_name'])

		foreach ($ids as $id) {
			$filePath = BASE_PATH . $this->getDataFirst('path');
			if (! file_exists($filePath) || ! unlink($filePath)) {
				return;
			}
			parent::delete($ids);
		}
		return $sth->rowCount();
	}
} 
