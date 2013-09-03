<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Maincontent_Many extends Model
{	


	/**
	 * the table name will be completed on construct and used
	 * within methods
	 * @var string
	 */
	public $tableName = 'main_content_';


	/**
	 * the column name will be completed on construct and used
	 * within methods
	 * @var string
	 */
	public $colName = '_id';


	/**
	 * extends the model constructer but adds the identifier
	 * this allows for a dynamic model which handles
	 * main_content_media
	 * main_content_identifier
	 * tables
	 * @param object $database   
	 * @param object $config     
	 * @param string $identifier title of table
	 */
	public function __construct($database, $config, $identifier) {
		$this->session = new Session();
		$this->database = $database;
		$this->config = $config;
		$this->tableName = $this->tableName . $identifier;
		$this->colName = $identifier . $this->colName;
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
			" . ($contentId ? ' where main_content.id = :content_id ' : '') . "
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
	 * creates rows and ties to contentid
	 * @param  int $contentId 
	 * @param  array  $ids       tag name or media id
	 * @return int            affected rows
	 */
	public function create($contentId, $ids = array()) {	
		$sth = $this->database->dbh->prepare("	
			insert into $this->tableName (
				content_id
				, $this->colName
			) values (
				?
				, ?
			)
		");
		foreach ($ids as $id) {
			$sth->execute(array(
				$contentId
				, $id
			));	
		}
		return $sth->rowCount();
	}


	/**
	 * deletes items based on contentid
	 * @param  int $id 
	 * @return int     affected rows
	 */
	public function deleteByContentId($id) {	
		$sth = $this->database->dbh->prepare("	
			delete from
				$this->tableName
			where $this->tableName.content_id = ?
		");
		$sth->execute(array($id));	
		return $sth->rowCount();
	}
}
