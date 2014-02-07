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
		$currentHistory = $this->getData('common');
		end($currentHistory);
		return prev($currentHistory);
	}


	/**
	 * adds to the session array to keep a record of your request history
	 * @todo should $this->data be array() as default?
	 */
	public function add($url)
	{
		if (! $this->validate($url)) {
			return;
		}

		// first one
		if (! $currentHistory = $this->getData('common')) {
			$currentHistory = array();
		}

		// check that the record does not breach 20
		if (count($currentHistory) > 19) {
			array_shift($currentHistory);
		}

		// check that next request is unique
		if (end($currentHistory) == $url) {
			return;
		}

		// adds to the array
		$currentHistory[] = $url;
		return $this->setDataKey('common', $currentHistory);
	}


	public function setCaptureUrl($url)
	{
		return $this->setDataKey('capture_url', $url);
	}


	public function getCaptureUrl()
	{
		return $this->getData('capture_url');
	}


	/**
	 * searches a url for invalid key words in a url, this way the history is
	 * not stuffed with ajax requests (for example)
	 * @param  string $url 
	 * @return bool      
	 */
	public function validate($url)
	{
		$invalidThings = array('ajax');
		foreach ($invalidThings as $invalidThing) {
			if (strpos($url, $invalidThing) !== false) {
				return false;
			}
		}
		return true;
	}
}
