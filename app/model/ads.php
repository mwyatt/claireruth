<?php

/**
 * Manage Advertisement Banners
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Ads extends Model
{	

	public function __construct($DBH) {
		$this->DBH = $DBH;
	}


	// /**
	//  * Select data based on @param settings
	//  * @result true if @param result isset false on failure
	//  */ 
	// public function select($type = false)
	// {		
	// 	if ($type)
	// 		$type = " AND a.type = '$type'";
						
	// 	$STH = $this->DBH->query("
	// 		SELECT
	// 			a.id
	// 			, a.title
	// 			, a.html
	// 			, a.target
	// 			, a.type
	// 			, a.status
	// 			, a.position
	// 			, a.media_id
	// 			, m.title as media_title
	// 			, m.description as media_description
	// 			, m.filename as media_filename
	// 		FROM
	// 			main_ads AS a
	// 		LEFT JOIN
	// 			main_media AS m
	// 		ON
	// 			a.media_id = m.id
	// 		WHERE	
	// 			a.status = 'visible'				
	// 		{$type}
	// 	");		
		
	// 	// Process Result Rows
	// 	while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {	
	// 		$this->setRow($row['id'], 'title', $row['title']);
	// 		$this->setRow($row['id'], 'html', $row['html']);		
	// 		$this->setRow($row['id'], 'target', $row['target']);		
	// 		$this->setRow($row['id'], 'type', $row['type']);		
	// 		$this->setRow($row['id'], 'status', $row['status']);		
	// 		$this->setRow($row['id'], 'position', $row['position']);		
	// 		$this->setRow($row['id'], 'media_id', $row['media_id']);		
	// 		$this->setRow($row['id'], 'media_title', $row['media_title']);		
	// 		$this->setRow($row['id'], 'media_description', $row['media_description']);		
	// 		$this->setRow($row['id'], 'media_filename', $row['media_filename']);		
	// 	}	
		
	// 	return $this;
	// }
	
	// /**
	// 	@return result or false
	// */
	// public function shuffle()
	// {		
	// 	if ($this->result)
	// 		shuffle($this->result);
			
	// 	return $this;
	// }
}