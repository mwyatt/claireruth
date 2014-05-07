<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Log extends Config
{


	public static function create($message, $type)
	{
		// $username = $_SESSION['username'];
		$mold = new mold_log();
		$mold->message = $message;
		$mold->type = 'admin';
		$mold->time = time();
		$model = new model_log($this);
		$model->create(array($mold));
	}
}
