<?php

/**
 * builds the site asked for
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
 
class Site extends System
{


	public function change($key)
	{
		$json = new Json($this);
		$json->setUsePath(false);
		$json->read('package');
		$data = $json->getData();
		$data->site = $key;
		$json->setData($data);
		$json->update('package');
		$json->read('package');
		echo '<pre>';
		print_r($json);
		echo '</pre>';
		exit;
		
	}
}
