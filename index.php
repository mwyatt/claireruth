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
$config = new config();
$config
	->setOptions($options->getData())
	->initiateUrl()
	->setObject($error);
$cron = new cron($database, $config);
$cron->poll(array(
	'emailErrorReport'
	, 'emailNewsletter'
));





$tagmaker = new model($database, $config);
$sth = $database->dbh->prepare("	
	select
		content_id
		, name
		, value
	from content_meta
");
$sth->execute();				
foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
	$rows[$row['value']][] = $row;
}
foreach ($rows as $newtagname) {
	// $tagmaker->create()
}
echo '<pre>';
print_r($rows);
echo '</pre>';
exit;


// keep track of the pages requested
// helpful for last attempted page redirection
// after login..
$sessionHistory = new session_history($database, $config);
$sessionHistory->add($config->getUrl('current'));

// navigate app
$controller = new controller();

// admin, ajax
if ($controller->load(array($config->getUrl(0)), $config->getUrl(1), false, $database, $config)) {
	exit;
}

// frontend
if ($controller->load(array('front'), $config->getUrl(0), false, $database, $config)) {
	exit;
}

// global exit
exit;
