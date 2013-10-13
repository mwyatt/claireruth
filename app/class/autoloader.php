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
		$title = strtolower($title);
		$path = BASE_PATH . 'app' . '/' . 'class' . '/' . $title . '.php';
		if (is_file($path)) {
			require_once($path);
			return;
		}

		$path = BASE_PATH . 'app/';

		foreach (explode('_', $title) as $sliceOfPathPie) {
			$path .= strtolower($sliceOfPathPie) . '/';
		}

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