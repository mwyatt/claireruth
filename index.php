<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
session_start();


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
$config = new config($database);
$config
	->setOptions($options->getData())
	->initiateUrl()
	->setObject($error);
$config->log = new model_log($database, $config);
$sessionHistory = new session_history($database, $config);
$sessionHistory->add($config->getUrl('current'));

/**
 * unit tests
 */
$test = new test($database, $config);
$test->run();

/**
 * controller
 * @var controller
 */
$initialController = new controller();
$initialController->view = new View($database, $config);
$controller = new controller($initialController, $database, $config);
$controller->loadClass();
exit;
