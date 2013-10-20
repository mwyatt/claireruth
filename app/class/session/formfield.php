<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Session_Formfield extends Session
{


	/**
	 * constantly builds a library of remembered fields
	 * these are overwritten when the field is submitted again
	 * @param array $structure  mainly $_POST, key->val
	 * @param array $fieldNames array of key to save, sometimes omitted
	 */
	public function add($structure, $fieldNames = array())
	{

		// do this better
		$currentData = array();
		if ($this->getData()) {
			$currentData = $this->getData();
		}

		// core loop
		// do this better
		foreach ($structure as $structureKey => $structureValue) {

			// if pairing is required
			// possibly could break with multiple if
			if ($fieldNames && in_array($structureKey, $fieldNames)) {
				$currentData[$structureKey] = $structureValue;
			}
		}
		return $this->setData($currentData);
	}
}
