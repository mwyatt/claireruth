<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session_History extends Session
{


	public function getLast() {
		$currentHistory = $this->getData();
		end($currentHistory);
		return current($currentHistory);
	}


	public function add()
	{
		if (! $currentHistory = $this->getData()) {
			$currentHistory = array();
		}
		if (count($currentHistory) > 9) {
			array_shift($currentHistory);
		}
		$currentHistory[] = $this->config->getUrl('current');
		return $this->setData($currentHistory);
	}
}
