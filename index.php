<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
session_start();
if (array_key_exists('session', $_GET)) {
	if ($_GET['session'] == 'destroy') {
		session_destroy();
		exit;
	}
}


/**
 * config, autoloader
 */
define('BASE_PATH', (string) (__DIR__ . '/'));
require_once(BASE_PATH . 'config.php');
require_once(BASE_PATH . 'app/class/autoloader.php');
spl_autoload_register(array('Autoloader', 'load'));


/**
 * core objects
 */
$error = new error($errorReporting);
$database = new database($credentials);
$options = new model_options($database);
$options->read();
$options->arrangeByName();
$config = new config($database);
$config
	->setOptions($options->getData())
	->initiateUrl()
	->setObject($error);


/**
 * store each unique url
 */
$sessionHistory = new session_history($database, $config);
$sessionHistory->add($config->getUrl('current'));


/**
 * unit tests
 */
// $test = new test($database, $config);
// $test->run();


/**
 * controller
 * @var controller
 */
$controller = new controller(false, $database, $config);
$controller->loadClass();


/**
 * core system exit
 * possibly no need to exit anywhere else?
 */
exit;


/* Enable errors, which a custom error handler is set for later. */
ini_set('display_errors',true);	
/* Set time zone to UK. */
ini_set('date.timezone', "Europe/London");
/* Keep processing after user disconnect (for cron). */
ignore_user_abort(true);
