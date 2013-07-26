<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 			
class Model_Mainoption extends Model
{	


	public function read()	{		
		$sth = $this->database->dbh->query("	
			select
				name
				, value
			from main_option
		");
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$this->data[$row['name']] = $row['value'];
		}			
		return $sth->rowCount();
	}


	public function update($name, $value) {
		$sth = $this->database->dbh->prepare("
			update main_option set
				value = ?
			where
				name = ?
		");				
		$sth->execute(array(
			$value
			, $name
		));		
		return $sth->rowCount();
	}
	
	
}
