<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Maincontent_Media extends Model
{	


	public function create($contentId, $mediaIds = array()) {	
		$sth = $this->database->dbh->prepare("	
			insert into main_content_media (
				content_id
				, media_id
			) values (
				?
				, ?
			)
		");
		foreach ($mediaIds as $mediaId) {
			$sth->execute(array(
				$contentId
				, $mediaId
			));	
		}
		return $sth->rowCount();
	}


	public function deleteByContentId($id) {	
		$sth = $this->database->dbh->prepare("	
			delete from
				main_content_media
			where main_content_media.content_id = ?
		");
		$sth->execute(array($id));	
		return $sth->rowCount();
	}
}