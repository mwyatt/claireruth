<?php

/**
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
 
class Cron extends Model
{


	public function start($keys = array())
	{
		$options = new model($this->config, $this->database, 'options');
		foreach ($keys as $key) {
			if (! $this->config->getOption($key)) {
				$options->create(array(
					'name' => $key
					, 'value' => time()
				));
			}
		}
	}
}