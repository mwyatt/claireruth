<?php


/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 


/**
 * the directory seperator
 */
define('DS', DIRECTORY_SEPARATOR);


/**
 * app environment from php.ini
 */
define('ENV', getenv('APP_ENV'));


/**
 * app version
 */
define('VERSION', '0.0.1');


/**
 * 
 */
define('PATH', dirname(__FILE__) . DS);
define('APP', PATH . 'anchor' . DS);
define('SYS', PATH . 'system' . DS);
define('EXT', '.php');

require SYS . 'start' . EXT;

/**
 * initial system object
 * @var stdClass
 */
$system = new stdClass();


/**
 * autoloader
 */
define('BASE_PATH', (string) (__DIR__ . '/'));
require_once(BASE_PATH . 'config.php');
require_once(BASE_PATH . 'app/class/autoloader.php');
spl_autoload_register(array('Autoloader', 'load'));


/**
 * core objects
 */
$error = new error($errorReporting);
$system->database = new database($credentials);
$system->config = false;
$system->config = new config($system);
$options = new model_options($system);
$options->read();
$options->arrangeByName();
$system->config
	->setOptions($options->getData())
	->initiateUrl()
	->phpSettings()
	->setObject($error);

// site switching
if (array_key_exists('site', $_GET)) {
	$site = new Site();
}


/**
 * store each unique url
 */
$sessionHistory = new session_history($system);
$sessionHistory->add($system->config->getUrl('current'));


/**
 * unit tests
 */
// $test = new test($system);
// $test->media();


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
