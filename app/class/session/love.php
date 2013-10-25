<?php

/**
 * @todo could be more general, session var which is tracking the history
 * of loved items?
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session_Love extends Session
{


	/**
	 * keeps a record of what you love
	 * then you cant love it again
	 * you cant love something twice, silly!
	 * @param int $contentId 
	 */
	public function add($contentId)
	{
		$currentRecord = $this->getData();

		// return true if not already in array
		if (! in_array($contentId, $currentRecord)) {
			$currentRecord[] = $contentId;
			return $this->setData($currentRecord);
		}

		// the content is already loved
		return false;
	}
}
