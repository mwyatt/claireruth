<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Content_Meta extends Model
{	


	// public function readByContentId($contentIds)
	// {
	// 	$query = "
	// 		select
	// 			name
	// 			, value
	// 		from content_meta
	// 		where content_id = :content_id
	// 	";
	// 	$sth = $this->database->dbh->prepare($query);		
	// 	foreach ($contentIds as $contentId) {
	// 		$this->bindValue($sth, ':content_id', $contentId);
	// 		$sth = $this->tryExecute($sth, '57687685677457333');
	// 		$parsedResults[$contentId] = $sth->fetchAll(PDO::FETCH_ASSOC);
	// 	}
	// 	echo '<pre>';
	// 	print_r($parsedResults);
	// 	echo '</pre>';
	// 	exit;
		
	// }
}
