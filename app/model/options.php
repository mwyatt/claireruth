<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 			
class Model_Options extends Model
{	


	public function read($select = '', $where = array(), $ids = array(), $limit = array())	{		
		$sth = $this->database->dbh->query("	
			select
				options.name
				, options.value
			from options
		");
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$this->data[$row['name']] = $row['value'];
		}			
		return $sth->rowCount();
	}	
}
