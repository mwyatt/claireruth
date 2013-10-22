<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Content_Meta extends Model
{	


	/**
	 * the column name will be completed on construct and used
	 * within methods
	 * @var string
	 */
	public $name;


	/**
	 * facilitates the setting of name on construct
	 * @param object $database 
	 * @param object $config   
	 * @param string  $name     the name column
	 */
	public function __construct($database = false, $config = false, $name = '') {
		parent::__construct($database, $config);

		// sets the name column flag
		$this->setName($name);
	}


	/**
	 * gets all items or by specific content id
	 * @param  integer $contentId 
	 * @return array             
	 */
	public function read($contentId = 0) {	
		$sth = $this->database->dbh->prepare("	
			select
				id
				, content_id
				, $this->colName
			from $this->tableName
			" . ($contentId ? ' where content.id = :content_id ' : '') . "
			group by $this->tableName.name
			order by $this->tableName.name desc
		");
		if ($contentId) {
			$sth->bindValue(':content_id', $id, PDO::PARAM_STR);
		}
		$sth->execute(array(
			':id' => $id
		));	
		return $results = $sth->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	 * simple return of name
	 * @return string 
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * sets the name property
	 * @param string $name 
	 */
	public function setName($name = false)
	{
		$this->name = $name;
	}
}
