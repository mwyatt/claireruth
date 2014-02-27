<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 			
class Model_Options extends Model
{	


	public $fields = array(
		'id'
		, 'name'
		, 'value'
	);


	public function arrangeByName()
	{
		$models = array();
		foreach ($this->getData() as $model) {
			$models[$model->name] = $model->value;
		}
		return $this->setData($models);
	}
}
