<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 			
class Model_Options extends Model
{	


	public function create($properties = array())
	{
        $sth = $this->database->dbh->prepare("
            insert into options (
                name
                , value
            )
            values (
                ?
                , ?
            )
        ");
        foreach ($molds as $mold) {
	        $sth->execute(array(
	            $mold->name
	            , $mold->value
	        ));                
        }
        return $sth->rowCount();
	}


	/**
	 * just gets all options and stores
	 * @param  array  $properties 
	 * @return bool             
	 */
	public function read($properties = array())
	{
		$sth = $this->database->dbh->query("	
			select
				options.name
				, options.value
			from options
		");
		$rows = array();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$rows[$row['name']] = $row['value'];
		}			
		return $this->setData($rows);
	}
}
