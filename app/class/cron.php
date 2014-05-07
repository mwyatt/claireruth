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
	 * how long the job will wait until it performs the
	 * action again
	 * @var integer
	 */
	public $timeDelay = 0;


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
	public function refresh($classNames = array())
	{
		$modelOptions = new model_options($this);
		$moldOptions = new mold_options();
		foreach ($classNames as $className) {

			// skip name if non existent
			if (! class_exists($className)) {
				continue;
			}

			// not yet stored, store, skip
			if (! $timeRecorded = $this->config->getOption($className)) {
				$moldOptions->name = $className;
				$moldOptions->value = time();
				$modelOptions->create(array($moldOptions));
				continue;
			}

			// initiate model
			$modelJob = new $className($this);
			
			// skip if not ready
			if (! $modelJob->isJobReady($timeRecorded)) {
				continue;
			}

			// run job
			$modelJob->initialise();

			// remove
			$modelOptions->delete(array(
				'where' => array('name' => $className)
			));
		}
	}


	public function getTimeDelay()
	{
		return $this->timeDelay;
	}


	/**
	 * if the job has passed its time delay setting then return true
	 * @param  int  $timeRecorded 
	 * @return boolean               
	 */
	public function isJobReady($timeRecorded)
	{
		$lapsedTime = time() - $timeRecorded;
		if ($lapsedTime > $this->getTimeDelay()) {
			return true;
		}
	}
}
