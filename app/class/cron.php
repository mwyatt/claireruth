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


	/**
	 * stores various time periods in human readable
	 * form for convenience
	 * @var array
	 */
	public $time = array(
		'hour' => 3600
		, 'day' => 86400
		, 'week' => 604800
		, 'month' => 2629743
		, 'year' => 31556926
	);


	/**
	 * gets the time period required
	 * @param  string $key 
	 * @return int      
	 */
	public function getTime($key)
	{
		if (! array_key_exists($key, $this->time)) {
			return;
		}
		return $this->time[$key];
	}


	/**
	 * polls options entries to see if a cron job is required
	 * @param  array  $keys each key to check
	 */
	public function poll($keys = array())
	{
		$modelOptions = new model($this->database, $this->config, 'options');
		foreach ($keys as $key) {
			$dbName = 'cron' . ucfirst($key);
			if ($recordedTime = $this->config->getOption($dbName)) {
				if (method_exists($this, $methodName = 'job' . ucfirst($key))) {

					// perform method
					$this->$key($recordedTime);

					// remove option
					$modelOptions->lazyDelete(array(
						'name' => $key
						, 'value' => time()
					));
				} else {
					trigger_error('cron method \'' . $key . '\' does not exist.', E_USER_ERROR);
				}
			} else {

				// add option
				$modelOptions->lazyCreate(array(
					'name' => $key
					, 'value' => time()
				));
			}
		}
	}
}
