<?php


/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 


$configRaw = $json->getData();
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


echo '<pre>';
print_r($system);
echo '</pre>';
exit;


$route = array(
	'/' => 'controller',
	'ajax/' => 'controller_ajax',
	'post/' => 'controller_content',
	'page/' => 'controller_content',
	'tag/' => 'controller_tag',
	'admin/' => 'controller_admin',
	'admin/ajax/' => 'controller_admin_ajax',
	'admin/ajax/content/' => 'controller_admin_ajax_content',
	'admin/ajax/media/' => 'controller_admin_ajax_media',
	'admin/ajax/tag/' => 'controller_admin_ajax_tag',
	'admin/content/' => 'controller_admin_content'
);





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
