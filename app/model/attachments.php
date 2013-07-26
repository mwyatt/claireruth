<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 			
class Model_Attachments extends Model_Media
{
	
	public $thumbPath = 'media/upload/thumb/';
	public $unique = array();
	
	
	public function __construct($DBH) {
		$this->DBH = $DBH;
	}	
	
	
	/**
	 * Sift through @param results to get unique values
	 * Then Query the Database for Unique Results
	 * @result true if @param result isset false on failure
	 */ 
	public function selectUnique($results)
	{	
	
		// Set Array of Unique ID's
		$this->setUnique($results);
	
		$SQL = "	
			SELECT
				`id`
				,`title`
				,`title_slug`
				,`date_uploaded`
				,`alt`
				,`description`
				,`media_tree_id`
				,`type`
				,`filename`
				,`user_id`
			FROM
				media	
		";
		
		// Extend SQL
		if ($this->getUnique()) :
			$first = false;
			foreach ($this->getUnique() as $uniqueId) :
				$SQL .= ($first == false ? " WHERE id = '$uniqueId'" : " OR id = '$uniqueId'");
				$first = true;
			endforeach;
		endif;
		
		// Execute SQL
		$STH = $this->DBH->query($SQL);
		
		// Return
		return $this->setResult(
			($STH->rowCount() > 0 ? $STH->fetchAll(PDO::FETCH_ASSOC) : false)
		);
	}	
	
	
	
	/**
	 * Sift through @param results to get unique values
	 * @return array of unique attachments
	 */
	public function setUnique($results)
	{	
		if ($results) {
			foreach ($results as $result) {			
				if ($result['attached']) {
					
					// Collect all Attachment ID's
					foreach ($result['attached'] as $attachment) {
						$this->unique[] = $attachment;
					}					
				}
			}
		
			// Get Unique 
			$this->unique = array_unique($this->unique);
			
			// Reset Keys
			$this->unique = array_values($this->unique);	
		} else {
		
			// Return
			return false;
		}								
		
		// Return
		return $this->getUnique();
	}
	
	
	/**
	 * @return @param unique
	 */
	public function getUnique()
	{	
		return $this->unique;
	}
	
	
	/**
		@desc loops through each attachment and adds the ['thumb'] key
		@return result
	*/
	public function setThumb($id, $name) {
	
		if ($row = $this->getResult($id)) :
		
			// Explode Filename Extension
			$fileName = explode('.', $row['filename']);
			
			// Create -(class).ext
			$class Model_= '-('.$name.').'.end($fileName); 
			
			// Remove .ext from Array
			array_pop($fileName);
			
			// Set Filepath
			$filePath = $this->thumbPath;
			
			// Implode Complete Filepath and Filename
			$fileName = implode($fileName).$class;	
			
			// Search for Existing File
			if (file_exists($filePath.$fileName)) {
			
				// Return
				return $fileName;
			} else {	
			
				// Return
				return false;
			}					
		endif;
	}	
	
	
	/**
	 * Append the attachment rows, add the thumb key if required and return
	 * @return result row
	 */ 
	public function append($result, $thumb)
	{			
		if (array_key_exists('attached', $result)) :
			if ($result['attached']) :
				for ($a = 0; $a < count($result['attached']) ; $a++) {
					for ($b = 0; $b < count($this->getResult()) ; $b++) {
						if ($result['attached'][$a] == $this->result[$b]['id']) :
							$result['attached'][$a] = $this->result[$b];
							$result['attached'][$a]['thumb'] = $this->setThumb($result['attached'][$a]['id'], $thumb);
						endif;
					}
				}
			endif;
		endif;
		
		// Return
		return $result;
	}
	
	
	public function getAttachments()
	{
		return ($this->attachments ? true : false);
	}		
	
	
	
}