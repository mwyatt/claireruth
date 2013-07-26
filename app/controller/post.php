<?php

/**
 * League
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

$mainContent = new mainContent($database, $config);

if ($config->getUrl(1)) {
	$id = end(explode('-', $config->getUrl(1)));
	if (! $mainContent->readById($id))
		return false;
	$view
		->setObject($mainContent)
		->loadTemplate('post-single');
}
 
$mainContent->readByType('press');

$view
	->setObject($mainContent)
	->loadTemplate('post');