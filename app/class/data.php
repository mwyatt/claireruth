<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Data extends System
{


	/**
	 * universal storage property, used for many things
	 * @var array
	 */
	public $data;


	/**
	 * @param mixed $value 
	 */
	public function setData($value)
	{		
		return $this->data = $value;
	}


	/**
	 * get
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function getData($key = '')
	{		
		if ($key) {
			if (array_key_exists($key, $this->data)) {
				return $this->data[$key];
			}
			return;
		}
		return $this->data;
	}	


	/**
	 * retrieves the first row of data, if there is any
	 * @return object, array, bool       
	 */
	public function getDataFirst()
	{
		$data = $this->getData();
		if (! $data) {
			return;
		}
		return reset($data);
	}


	/**
	 * builds an array of {property} from the data property
	 * @param  string $property 
	 * @return array           
	 */
	public function getDataProperty($property)
	{
		if (! $this->getData()) {
			return;
		}
		$collection = array();
		foreach ($this->getData() as $mold) {
			$collection[] = $mold->$property;
		}
		return $collection;
	}
}
