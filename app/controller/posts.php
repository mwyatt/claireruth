<?php

/**
  *	Posts
  *	
  *	@author Martin Wyatt <martin.wyatt@gmail.com> 
  *	@copyright 2012 Martin Wyatt
  *	@license http://www.php.net/license/3_01.txt PHP License 3.01
  */ 	
  
  
// Object(s)
$posts = new Content($DBH, 'post');	  


// Single
if ($config->getUrl(2) && $config->getUrl(3) && $config->getUrl(4)) {

	// Check Content by Date
	if ($post->getDate($config->getUrl(2), $config->getUrl(3), 'post', $config->getUrl(4))) {

		// View: posts-single
		require_once('app/view/posts-single.php');
		exit;
	} else {
		$go->home('posts/'); // redirect
	}
}


// Yearly
if ($config->getUrl(2) && !$config->getUrl(3)) {

	// http://mawyatt.com/posts/2012/
	if ($post->getDate($config->getUrl(2), null, 'post')) {
		
		// View: posts-year
		require_once('app/view/posts-year.php');
		exit;
	} else {
	
		// Redirect
		$go->home('posts/');
	}
}


// Invalid Url
if ($config->getUrl(2) || $config->getUrl(3) || $config->getUrl(4)) {

	// Redirect
	$go->home('posts/');
}
	
	
// All Posts
$posts
	->select();


// View: posts
require_once('app/view/posts.php');
exit;