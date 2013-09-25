<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 			
class Model_Options extends Model
{	


	public function read()	{		
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


	// public function update($name, $value) {
	// 	$sth = $this->database->dbh->prepare("
	// 		update options set
	// 			options.value = ?
	// 		where
	// 			options.name = ?
	// 	");				
	// 	$sth->execute(array(
	// 		$value
	// 		, $name
	// 	));		
	// 	return $sth->rowCount();
	// }
	

	/**
	 * generates name value rows and adds to the options database
	 * this updates if the row already exists
	 * @param  array $pairs name => value
	 * @return int        affected rows
	 */
	// public function create($pairs)
	// {
	// 	$sthCreate = $this->database->dbh->prepare("
	// 		insert into options (
	// 			options.name
	// 			, options.value
	// 		)
	// 		values (
	// 			?
	// 			, ?
	// 		)
	// 	");				
	// 	foreach ($pairs as $name => $value) {
	// 		$sthRead = $this->database->dbh->query("	
	// 			select options.name
	// 			from options
	// 			where options.name = '$name'
	// 		");
	// 		if ($sthRead->rowCount()) {
	// 			$this->update($name, $value);
	// 		} else {
	// 			$sthCreate->execute(array(
	// 				$name
	// 				, $value
	// 			));	
	// 		}
	// 	}
	// 	return $sthCreate->rowCount();
	// }
	
}
