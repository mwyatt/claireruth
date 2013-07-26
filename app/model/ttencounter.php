<?php

/**
 * ttFixture
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Ttencounter extends Model
{

	public function deleteByFixtureId($id) {
		$sth = $this->database->dbh->prepare("
			delete from tt_encounter
			where fixture_id = ?
		");				
		$sth->execute(array($id));	
		return $sth->rowCount();
	}

	
}
