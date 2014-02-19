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
		$mold->content_id = 10;
		$mold->name = 'test';
		$mold->value = 20;
		var_dump($model->update(array(
			'where' => array(
				'content_id' => 10,
				'name' => 'test'
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
