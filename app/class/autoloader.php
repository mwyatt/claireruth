<?php

/**
 * AutoLoader
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Autoloader {


	/**
	 * Load classes dynamically
	 * possible class names:
	 * 		Class
	 * 		Controller_
	 * 		Model_
	 * 		
	 * 		
	 *
	 * 
	 * order of operations:
	 * 		classname = foo_bar
	 * 		app/class/site/sitename/foo_bar.php
	 * 		app/class/foo_bar.php
	 * 		app/class/foo/bar.php
	 * 		app/foo/bar.php
	 * 
	 * @param  string $title attempted class to load
	 * @return null            
	 */
	public static function call($class) {
		$class = strtolower($class);

		// 

		// check for app/class/foo_bar.php
		$testPath = PATH_CLASS . $class . EXT;
		if (is_file($testPath)) {
			require_once($testPath);
			return;
		}

		// app/class/foo/bar.php
		$titlePath = '';
		foreach (explode('_', $class) as $sliceOfPathPie) {
			$titlePath .= strtolower($sliceOfPathPie) . '/';
		}
		$titlePath = rtrim($titlePath, '/');
		$testPath = PATH_CLASS . $titlePath . EXT;
		if (is_file($testPath)) {
			require_once($testPath);
			return;
		}

		// appl/foo/bar.php
		$testPath = PATH_APP . $titlePath . EXT;
		if (is_file($testPath)) {
			require_once($testPath);
			return;
		}
	}	
}
