<?php

/**
 * Cache
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Cache
{


	/**
	 * list of allowed templates which should be cached
	 * @var array
	 */
	public $templates = array('player', 'team');


	/**
	 * check to see if the template should be cached
	 * @param  string  $templateTitle 
	 * @return boolean                
	 */
	public function check($templateTitle) {
		if (in_array($templateTitle, $this->templates)) {
			return true;
		}
		return false;
	}


	public $active;


	public function __construct($active) {
		$this->active = $active;
	}


	public function load($templateTitle) {
		if (! $this->check($templateTitle)) {
			return false;
		}
		if (file_exists($this->getPath($templateTitle)) && $this->isActive()) {
			require_once($this->getPath($templateTitle));
			exit;
		}
	}


	public function create($templateTitle, $fileContents) {
		if (! $this->check($templateTitle)) {
			return false;
		}
		if (! file_exists($this->getPath($templateTitle))) {
			file_put_contents($this->getPath($templateTitle), $fileContents);
		}
	}


	public function getPath($templateTitle) {
		return BASE_PATH . 'app/cache/' . $templateTitle . '.html';
	}


	public function isActive() {
		return $this->active;
	}

	
} 




