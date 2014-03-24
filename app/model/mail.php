<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Mail extends Model
{	


	public $fields = array(
		'id'
		, 'addressed_to'
		, 'addressed_from'
		, 'subject'
		, 'content'
		, 'time'
	);
}
