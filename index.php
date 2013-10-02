<?php

/**
 * @package	claireruth
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
define('BASE_PATH', (string) (__DIR__ . '/'));
require_once(BASE_PATH . 'config.php');
require_once(BASE_PATH . 'app/class/autoloader.php');
spl_autoload_register(array('Autoloader', 'load'));
$error = new error($errorReporting);
$database = new database($credentials);
$session = new session();
$session
	->start()
	->refreshExpire();
$config = new config();
$mainOption = new model_options($database, $config);
$mainOption->read();
$config
	->setOptions($mainOption->getData())
	->setUrl()
	->setObject($error)
	->setObject($session);
$cron = new cron($database, $config);
$cron->start(array(
	'cron_email_error'
	, 'cron_email_newsletter'
));
$controller = new controller();

// admin, ajax
if ($controller->load(array($config->getUrl(0)), $config->getUrl(1), false, $database, $config)) {
	exit;
}

// frontend
if ($controller->load(array('front'), $config->getUrl(0), false, $database, $config)) {
	exit;
}
exit;
