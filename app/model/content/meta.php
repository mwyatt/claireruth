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
	public function read($select = "", $where = array(), $ids = array(), $limit = array()) {
		$this->read('
			id
			, content_id
			, name
			, value
		', array('name' => $this->getName()));
		$this->getData();
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
