<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Log extends Config
{


	public static function create($type, $message)
	{
		$mold = new mold_log();
		$mold->message = $message;
		$mold->type = $type;
		$mold->time = time();
		echo '<pre>';
		print_r($this);
		echo '</pre>';
		exit;
		
		$model = new model_log($this->database, $this->config);
		$model->create(array($mold));
	}
}
