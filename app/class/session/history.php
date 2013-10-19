<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session_History extends Session
{


	/**
	 * gets the last but not the latest val
	 * @return string url
	 */
	public function getLast() {
		$currentHistory = $this->getData();
		end($currentHistory);
		return prev($currentHistory);
	}


	/**
	 * adds to the session array to keep a record of your request history
	 * @todo should $this->data be array() as default?
	 */
	public function add()
	{

		// first one
		if (! $currentHistory = $this->getData()) {
			$currentHistory = array();
		}

		// check that the record does not breach 20
		if (count($currentHistory) > 19) {
			array_shift($currentHistory);
		}

		// check that next request is unique
		if (end($currentHistory) == $this->config->getUrl('current')) {
			return;
		}

		// adds to the array
		$currentHistory[] = $this->config->getUrl('current');
		return $this->setData($currentHistory);
	}
}
