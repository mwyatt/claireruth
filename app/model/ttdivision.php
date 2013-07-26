<?php

/**
 * ttDivision
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Ttdivision extends Model
{	
	

	public function read() {	
	
		$sth = $this->database->dbh->query("	
			select
				tt_division.id
				, tt_division.name
			from
				tt_division
			order by
				tt_division.id
		");
		
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$row['guid'] = $this->getGuid('division', $row['name']);
			$this->data[] = $row;
		}	
		return $this;

	}	


	public function delete($id)	{	
	
		$sth = $this->database->dbh->query("
			DELETE FROM
				tt_team
			WHERE
				id = '$id'		
		");
		
		return $sth->rowCount();
		
	}
	
	
	public function readByName($name) {	
		$sth = $this->database->dbh->prepare("	
			select
				tt_division.id
				, tt_division.name
			from tt_division
			where tt_division.name like ?
			limit 1
		");
		$sth->execute(array('%' . $name . '%'));
		$this->data = $sth->fetch(PDO::FETCH_ASSOC);
		return $sth->rowCount();
	}	


	// public function readSummary() {	
	
	// 	$sth = $this->database->dbh->query("	
	// 		select
	// 			tt_division.id
	// 			, tt_division.name
	// 		from
	// 			tt_division
	// 		order by
	// 			tt_division.id
	// 	");
		
	// 	while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
	// 		$this->data[] = $row;
	// 	}	

	// }	

	
}