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
	 * possible
	 * 		app/class/foo.php
	 * 		app/model/foo.php
	 * 		app/controller/{site}/foo.php
	 * @param  string $class attempted class to load
	 * @return null            
	 */
	public static function call($class) {
		// echo '<pre>';
		// var_dump($class);
		// echo '</pre>';

		// normalise for consistency
		$class = strtolower($class);

		// turn into foo/bar.php
		$path = str_replace(CS, DS, $class) . EXT;

		// app/class/foo.php
		if (file_exists(PATH_CLASS . $path)) {
			return require_once(PATH_CLASS . $path);
		}
		
		// model/foo.php
		if (file_exists(PATH_APP . $path)) {
			return require_once(PATH_APP . $path);
		}

		// app/controller/{site}/foo.php
		$path = str_replace('controller' . DS, '', $path);
		if (file_exists(PATH_CONTROLLER . $path)) {
			return require_once(PATH_CONTROLLER . $path);
		}

		// no class!
		// fail silently
		// exit('autoloader: \'' . $class . '\' does not exist in the app');
	}
}
