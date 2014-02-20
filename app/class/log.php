<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Log extends Config
{


	/**
	 * writes a log message to the log table
	 * usage = Log::create('{user} did this')
	 * @param  string $message 
	 */
	public static function create($message)
	{
		$username = $_SESSION['username'];
		Common_Logger::write('admin', "$msg ($username)", Common_Logger::INFO);		
	}
}
