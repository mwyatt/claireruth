<?php

/**
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Ttarchive extends Model
{


	public function readById($ids)
	{	
		$sth = $this->database->dbh->prepare("	
			select
				tt_archive.id
				, tt_archive.title
				, tt_archive.html
			from tt_archive
			where tt_archive.id = ?
		");
		foreach ($ids as $id) {
			$sth->execute(array($id));
			$archives[] = $sth->fetch(PDO::FETCH_ASSOC);
		}
		if (count($archives) == 1) {
			$archives = current($archives);
		}
		if (! empty($archives)) {
			return $this->data = $archives;
		}
		return false;
	}	


	public function read()
	{	
		$sth = $this->database->dbh->query("	
			select
				tt_archive.id
				, tt_archive.title
			from tt_archive
		");
		$sth->execute();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$row['guid'] = $this->getGuid('archive', $row['title'], $row['id']);
			$this->data[] = $row;
		}
		return $sth->rowCount();
	}	
	

}