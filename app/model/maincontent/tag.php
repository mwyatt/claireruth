<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Maincontent_Tag extends Model
{	


	/**
	 * gets all tags or by specific content id
	 * @param  integer $contentId 
	 * @return array             
	 * @todo build guid so that tags can be navigated to             
	 */
	public function read($contentId = 0) {	
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, name
			from main_content_tag
			" . ($contentId ? ' where main_content.id = :content_id ' : '') . "
			group by main_content_tag.name
			order by main_content_tag.name desc
		");
		if ($contentId) {
			$sth->bindValue(':content_id', $id, PDO::PARAM_STR);
		}
		$sth->execute(array(
			':id' => $id
		));	
		return $results = $sth->fetchAll(PDO::FETCH_ASSOC);
	}	


}