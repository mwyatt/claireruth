<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Log extends Model
{	


	public $fields = array(
		'id'
		, 'message'
		, 'time'
		, 'type'
	);


	/**
	 * utilises model create to make row
	 * @param  string $type    
	 * @param  string $message 
	 * @return int          
	 */
	public function log($type, $message)
	{
		$mold = new mold_log();
		$mold->message = $message;
		$mold->type = $type;
		$mold->time = time();
		return $this->create(array($mold));
	}
}
