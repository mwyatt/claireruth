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
            insert into user (
                email
                , password
                , first_name
                , last_name
                , time_registered
            )
            values (
                :email
                , :password
                , :first_name
                , :last_name
                , :time_registered
            )
        ");      






               
        $sth->execute(array(
            ':title' => $values['title']
            , ':html' => (array_key_exists('html', $values) ? $values['html'] : '')
            , ':type' => $values['type']
            , ':time_published' => time()
            , ':status' => (array_key_exists('status', $values) ? $values['status'] : 'hidden')
        ));                
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


	public function update($properties = array())
	{
		# code...
	}


	public function delete($properties = array())
	{
		# code...
	}
}
