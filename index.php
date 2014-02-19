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
$sessionHistory = new session_history($database, $config);
$sessionHistory->add($config->getUrl('current'));
$test = new test($database, $config);
$test->run();
$initialController = new controller();
$initialController->view = new View($database, $config);
$controller = new controller($initialController, $database, $config);
$controller->loadClass();
exit;


// $content = new model_content($database, $config);
// $content->read(array('type' => 'post'));
// $media = new model_media($database, $config);
// $media->readContentId($content->getDataIds());
// $content->combine(array('media' => $media));
// echo '<pre>';
// print_r($content);
// echo '</pre>';
// exit;
