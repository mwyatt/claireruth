<?php

/**
 * 
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Maincontent_Meta extends Model
{	


	public function readMedia($id) {	
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, name
				, value
			from main_content_meta
			where content_id = :id
		");
		$sth->execute(array(
			':id' => $id
		));	
		$results = $sth->fetchAll(PDO::FETCH_ASSOC);
		$sth = $this->database->dbh->prepare("	
			select
				id
				, filename
				, basename
				, type
				, date_published
			from main_media
			where main_media.id = :id
		");
		foreach ($results as $result) {
			if ($result['name'] == 'media') {
				$sth->execute(array(
					':id' => $result['value']
				));	
			}
		}
		$results = $sth->fetchAll(PDO::FETCH_ASSOC);
		echo '<pre>';
		print_r($results);
		echo '</pre>';
		exit;
	}	
}