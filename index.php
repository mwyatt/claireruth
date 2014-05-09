<?php


/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 


/**
 * definitions
 */
define('DS', DIRECTORY_SEPARATOR);
define('US', '/');
define('ENV', getenv('APP_ENV'));
define('VERSION', '0.0.1');
define('BASE_PATH', (string) (__DIR__ . '/'));
define('PATH_APP', BASE_PATH . 'app' . DS);
define('PATH_CLASS', PATH_APP . 'class' . DS);
define('EXT', '.php');


/**
 * initialise app
 */
require BASE_PATH . 'config' . EXT;
require PATH_APP . 'initialise' . EXT;
