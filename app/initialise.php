<?php


/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 


/**
 * boot session
 */
session_start();


/**
 * autoloader include and register
 */
require PATH_CLASS . 'autoloader' . EXT;
spl_autoload_register(array('Autoloader', 'load'));


/**
 * core object
 */
$system = new system();
$system->setPhpSettings();


/**
 * set other objects
 */
$system->setUrl(new url());
$system->setConfig(new config());
$system->setDatabase(new database($credentials));


/**
 * build options and set into config
 * @var model_options
 */
$options = new model_options($system);
$options->read();
$options->arrangeByName();
$system->config->setOptions($options->getData());


if (array_key_exists('site', $_GET)) {
	$site = new Site();
}

	
/**
 * store each unique url
 */
$sessionHistory = new session_history($system);
$sessionHistory->add($system->url->getCache('current'));



/**
 * unit tests
 */
$test = new test($system);
// $test->json();


/**
 * controller
 * @var controller
 */
$controller = new controller($system);
$controller->loadClass();


/**
 * cron
 */
$cron = new cron($system);
$cron->refresh(array(
	'cron_email_newsletter'
));


/**
 * core system exit
 * possibly no need to exit anywhere else?
 */
exit;
