<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Model_Media extends Model
{
	

	public $fields = array(
		'id'
		, 'title'
		, 'description'
		, 'path'
		, 'type'
		, 'time_published'
		, 'user_id'
	);
}
