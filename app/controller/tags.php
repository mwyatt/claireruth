<?php

/**
  *	Tags
  *	
  *	@author Martin Wyatt <martin.wyatt@gmail.com> 
  *	@copyright 2012 Martin Wyatt
  *	@license http://www.php.net/license/3_01.txt PHP License 3.01
  */ 	

/*
possible url combinations
	http://mawyatt.com/tags/web-design

*/
  
/**
  * @include models
  */
require_once(Config::read('dir.M').'content.php');
require_once(Config::read('dir.M').'attached.php');

$content = new Content;

/* =========================== single =========================== */
if (Config::getUrl(2)) {
	if ($content->getTag(Config::getUrl(2))) {
		$data = $content->getResult();
		$posts = $data['post'];
		$projects = $data['project'];
	
		/**
		  * view: tags-single
		  */
		require_once(Config::read('dir.V').'tags-single.php');
		exit;
	}
}
$go->home(); // redirect