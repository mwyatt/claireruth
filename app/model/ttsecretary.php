<?php

/**
 * ttSecretary
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Ttsecretary extends Model
{		

	public function read()
	{	

		$sth = $this->database->dbh->query("	
			select
				tt_secretary.id
				, concat(tt_secretary.first_name, ' ', tt_secretary.last_name) as full_name
			from tt_secretary
		");

		$this->setDataStatement($sth);

		return $this;

	}	

}