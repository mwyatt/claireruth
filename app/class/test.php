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
echo '<pre>';
var_dump($this->config->getOption('site_title'));
echo '</pre>';
exit;



		$this->logger();
// 		$this->options();
// 		// $model->read();
		$model = new model_content_meta($this->database, $this->config);
		$mold = new mold_content_meta();
		$mold->content_id = 10;
		$mold->name = 'tester';
		$mold->value = 600;
		$mold2 = $mold;
		var_dump($model->create(array($mold, $mold2)));
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
		$model = new model_log($this->database, $this->config);
		$model->log('admin', 'hello world');
	}
}
