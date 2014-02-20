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
		$this->logger();
// 		$this->options();
// 		// $model->read();
// 		$model = new model_content_meta($this->database, $this->config);
// 		$mold = new mold_content_meta();
// 		$mold->content_id = 10;
// 		$mold->name = 'tester';
// 		$mold->value = 600;
// 		var_dump($model->delete(array(
// 			'where' => array(
// 				'content_id' => 10
// 			)
// 		)));
// 		$model->read(array(
// 			'where' => array(
// 				'content_id' => 10
// 			)
// 		));

// echo '<pre>';
// print_r($model->getData());
// echo '</pre>';
// exit;


// 		var_dump($model->update($mold, array(
// 			'where' => array(
// 				'content_id' => 10,
// 				'name' => 'test'
// 			)
// 		)));

// 		var_dump($model->create(array($mold)));

// 		exit;
	}

	public function options()
	{
		$model = new model_options($this->database, $this->config);
		$model->read();

echo '<pre>';
print_r($model->getData());
echo '</pre>';
exit;
		
	}


	public function logger()
	{
		echo '<pre>';
		print_r($this->log->log());
		echo '</pre>';
		exit;

	}
}
