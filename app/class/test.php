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
		$this->json();
		exit;

echo '<pre>';
var_dump($this->config->getOption('site_title'));
echo '</pre>';
exit;



		$this->logger();
// 		$this->options();
// 		// $model->read();
		$model = new model_content_meta($this);
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
		$model = new model_options($this);
		$model->read();

echo '<pre>';
print_r($model->getData());
echo '</pre>';
exit;
		
	}


	public function logger()
	{
		$model = new model_log($this);
		$model->log('admin', 'hello world');
	}


	public function cache()
	{
		$cache = new Cache();
		if ($cache->read('example-cache-more')) {
			// set parser with $cache->getData()
		} else {
			$model = new model_options($this);
			$model->read();
			$cache->create('example-cache-more', $model->getData());
		}
		// echo '<pre>';
		// print_r($cache);
		// echo '</pre>';
		// exit;

		// used in admin
		$cache->delete('example-cache-more');
	}


	public function media()
	{
	    
		echo '<form action="#" method="post" enctype="multipart/form-data"><input type="file" name="form_media[]" multiple="multiple"><input type="submit"></form>';
		$file = new File($this);
		$file->setTypesAcceptable(array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg', 'image/jpeg', 'image/pjpeg', 'application/pdf'));
echo '<pre>';
		var_dump($file->upload('form_media', $_FILES));
print_r($file);
echo '</pre>';
exit;
	}


	public function mail()
	{
		$mail = new Mail($this->database, $this->config, $this->view);
		$mail->send(array(
			'to' => 'martin.wyatt@gmail.com',
			'subject' => 'subject line',
			'template' => 'mail/test'
		));
		exit;
	}


	public function destroySession()
	{
		
		// session
		session_start();
		if (array_key_exists('session', $_GET)) {
			if ($_GET['session'] == 'destroy') {
				session_destroy();
				exit;
			}
		}
	}


	public function json()
	{
		$json = new Json($this);
		$json->read('main-menu');
		echo '<pre>';
		print_r($json);
		echo '</pre>';
		exit;
	}


	public function updateContent()
	{
		$modelContent = new model_content($this);
		
		$modelContent->update(
			array('status' => 'visible')
		);
	}
}
