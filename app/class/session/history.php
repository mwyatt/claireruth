<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session_History extends Session
{


	public function getPreviousUrl($current) {
		if (! array_key_exists('history', $_SESSION)) {
			$_SESSION['history'][0] = $current;
			$_SESSION['history'][1] = false;
			return;
		} else {
			if ($_SESSION['history'][0]) {
				$_SESSION['history'][1] = $_SESSION['history'][0];
			}
			$_SESSION['history'][0] = $current;
			if ($_SESSION['history'][1]) {
				return $_SESSION['history'][1];
			} else {
				return;
			}
		}
	}
}
