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
		$path = BASE_PATH . 'app/'
		$testPath = $path . 'class/' . $lowerTitle . $classExtension;
		if (is_file($testPath)) {
			require_once($testPath);
			return;
		}

		// explode
		$titlePath = '';
		foreach (explode('_', $lowerTitle) as $sliceOfPathPie) {
			$titlePath .= strtolower($sliceOfPathPie) . '/';
		}

		$path = BASE_PATH . 'app/';


		$path = rtrim($path, '/');
		$path .= '.php';
		
			echo "$path<br>";			
		if (is_file($path)) {
			require_once($path);
			return;
		}

		// echo '<h2>' . 'Class can\'t be found' . '<h2>';
		// echo '<pre>';
		// echo $title . '<br>';
		// print_r($path);
		// echo '</pre>';

		// exit;
		
	}

	
}
