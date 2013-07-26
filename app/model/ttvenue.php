<?php

/**
 * ttVenue
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Ttvenue extends Model
{		

	public function read()
	{	

		$sth = $this->database->dbh->query("	
			select
				tt_venue.id
				, tt_venue.name
			from tt_venue
			group by tt_venue.id
		");

		$this->setDataStatement($sth);

		return $this;

	}	

}