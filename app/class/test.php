<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
 
class Test extends Config
{


	public function run()
	{
		// $model->read();
		$model = new model_content_meta($this->database, $this->config);
		$mold = new mold_content_meta();
		$mold->content_id = 99;
		$mold->name = 'test';
		$mold->value = 99;
		var_dump($model->update($mold, array(
			'where' => array(
				'content_id' => 3,
				'name' => 'tag'
			)
		)));

echo '<pre>';
print_r($model->getData());
echo '</pre>';
exit;



		var_dump($model->read(array(
			'where' => array(
				'content_id' => 10,
				'name' => 'test'
			)
		)));
		var_dump($model->create(array($mold)));
		// $model->delete(array(
		// 	'where' => array(
		// 		'content_id' => 1,
		// 		'name' => 'media'
		// 	)
		// ));

		exit;
	}
}
