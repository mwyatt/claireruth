<?php

/**
 * Install the Database
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

$controller = new Controller($database, $config);
if ($session->get('installing')) {
	$user = new Model_Mainuser($database, $config);
	require_once(BASE_PATH . 'install-table.php');
	require_once(BASE_PATH . 'install-tabledata.php');
	$session->getUnset('installing');
	$controller->route('home');
} else {
	$files = glob(BASE_PATH . 'img/upload/'); // get all file names
	foreach($files as $file){ // iterate files
	  if(is_file($file))
	    unlink($file); // delete file
	}
	$database->dbh->query("DROP DATABASE " . Database::$credentials['basename']); 
	$database->dbh->query("CREATE DATABASE " . Database::$credentials['basename']);
	$session->set('installing', true);
	$controller->route('home', '?install');
}
