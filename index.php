<?php

/**
 * @package	eastlancstt
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 

define('BASE_PATH', (string) (__DIR__ . '/'));
require_once(BASE_PATH . 'app/config.php');
require_once(BASE_PATH . 'app/class/autoloader.php');
spl_autoload_register(array('Autoloader', 'load'));
$error = new Error($errorReporting);
$database = new Database($credentials);
$session = new Session();
$session
	->start()
	->refreshExpire();
$config = new Config();
$mainOption = new Model_Mainoption($database, $config);
$mainOption->read();
$config
	->setOptions($mainOption->getData())
	->setUrl()
	->setObject($error)
	->setObject($session);
$controller = new Controller();

// admin, ajax
if ($controller->load(array($config->getUrl(0)), $config->getUrl(1), false, $database, $config)) {
	exit;
}

// frontend
if ($controller->load(array('front'), $config->getUrl(0), false, $database, $config)) {
	exit;
}
exit;
