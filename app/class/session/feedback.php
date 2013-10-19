<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session_Feedback extends Session
{


	public function set($message = false, $positivity = false)
	{
		$this->setData(array(
			'message' => $message
			, 'positivity' => $positivity
		));
	}
}
