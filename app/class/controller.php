<?php

/**
 * Controller
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
 
class Controller extends Route
{


	/**
	 * code here to initialise the controller
	 * this can be overidden or expanded later on down the controller tree
	 * if required
	 */
	public function initialise()
	{

		// will run for any class, can expand on this in future classes
	}


	/**
	 * always happens after initialise
	 */
	public function run()
	{
		
		// code for core logic
	}


	public function runMethod($part)
	{
		if (! $action = $this->url->getPathPart($part)) {
			return;
		}
		if (! method_exists($this, $action)) {
			return;
		}
		$this->$action();
	}
}
