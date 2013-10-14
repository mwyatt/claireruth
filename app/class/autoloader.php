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
	 *
	 * order of operations:
	 * 		foo_bar
	 * 		check for app/class/foo_bar.php
	 * 		app/class/foo/bar.php
	 * 		appl/foo/bar.php
	 * 
	 * @param  string $title attempted class to load
	 * @return null            
	 */
	public static function load($title) {
		$lowerTitle = strtolower($title);
		$classExtension = '.php';

		// check for app/class/foo_bar.php
		$basePath = BASE_PATH . 'app/';
		$testPath = $basePath . 'class/' . $lowerTitle . $classExtension;
		if (is_file($testPath)) {
			require_once($testPath);
			return;
		}

		// app/class/foo/bar.php
		$titlePath = '';
		foreach (explode('_', $lowerTitle) as $sliceOfPathPie) {
			$titlePath .= strtolower($sliceOfPathPie) . '/';
		}
		$titlePath = rtrim($titlePath, '/');
		$testPath = $basePath . 'class/' . $titlePath . $classExtension;
		if (is_file($testPath)) {
			require_once($testPath);
			return;
		}

		// appl/foo/bar.php
		$testPath = $basePath . $titlePath . $classExtension;
		if (is_file($testPath)) {
			require_once($testPath);
			return;
		}
	}	
}
