<?php

/**
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
 
class Cron_Email_Newslsetter extends Cron
{

	public $period = 259200;


	/**
	 * emails an error report to the admin every n days
	 */
	public function jobEmailErrorReport($recordedTime)
	{
		$lapsedTime = time() - $recordedTime;
		if ($lapsedTime > ???????) {
			// delete option
		}
	}
}
