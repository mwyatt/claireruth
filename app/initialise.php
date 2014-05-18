<?php


/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
$config = new config();
$config->setData($configRaw);
$database = new database($configRaw);


/**
 * boot session
 */
session_start();


/**
 * core object
 */
$system = new system();
$system->setPhpSettings();


/**
 * set other objects
 */
$system->setUrl(new url());
$error = new error($system);
$error->initialise();
$system->setConfig($config);
$system->setDatabase($database);


/**
 * build options and set into config
 * @var model_options
 */
$options = new model_options($system);
$options->read();
$options->arrangeByName();
$system->config->setOptions($options->getData());

	
/**
 * store each unique url
 */
$sessionHistory = new session_history($system);
$sessionHistory->add($system->url->getCache('current'));


/**
 * unit tests
 */
$test = new test($system);
// $test->updateContent();


/**
 * find appropriate route and load controller
 * @var route
 */
$route = new route($system);
$route->loadMap();
$route->load();


/**
 * cron
 * handle any post render processes
 */
$cron = new cron($system);
$cron->refresh(array(
	'cron_email_newsletter'
));


/**
 * it was nice seeing you
 */
exit;
