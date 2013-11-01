<?php

/**
 * @package	claireruth
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
session_start();
define('BASE_PATH', (string) (__DIR__ . '/'));
require_once(BASE_PATH . 'config.php');
require_once(BASE_PATH . 'app/class/autoloader.php');
spl_autoload_register(array('Autoloader', 'load'));
$error = new error($errorReporting);
$database = new database($credentials);
$options = new model_options($database);
$options->read();

$config = new config($database);
$config
	->setOptions($options->getData())
	->initiateUrl()
	->setObject($error);
$cron = new cron($database, $config);
$cron->poll(array(
	'emailErrorReport'
	, 'emailNewsletter'
));
$sessionHistory = new session_history($database, $config);
$sessionHistory->add($config->getUrl('current'));
$controller = new controller(false, $database, $config);
$controller->loadMethod();
exit;
