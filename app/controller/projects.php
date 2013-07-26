<?php

/**
  *	Projects
  *	
  *	@author Martin Wyatt <martin.wyatt@gmail.com> 
  *	@copyright 2012 Martin Wyatt
  *	@license http://www.php.net/license/3_01.txt PHP License 3.01
  */ 	

/*
possible url combinations
	http://mawyatt.com/projects/project-name/
	http://mawyatt.com/projects/tags/tag-name/
*/  
  
/**
  * models
  */
require_once(Config::read('dir.M').'content.php');
require_once(Config::read('dir.M').'attached.php');

$project = new Content;

/* =========================== single =========================== */
if (Config::getUrl(2)) {
	if ($project->get('project', 1, Config::getUrl(2))) {	
		$attached->get($project); // get unique attachments
		$attached->getThumb('project', 200, 200); // add thumbnails to attachment array
		$project->setAttached($attached->getResult()); // assign attachments to projects
	
		/**
		  * view: projects-single
		  */
		require_once(Config::read('dir.V').'projects-single.php');
		exit;
	} else {
		$go->home('projects/'); // redirect
	}
}

/* =========================== unset invalid urls =========================== */
if (Config::getUrl(2)) {
	$go->home('projects/'); // redirect
}
	
/* =========================== all =========================== */

$project->get('project'); // get all projects
$attached->get($project); // get unique attachments
$attached->getThumb('project', 200, 200); // add thumbnails to attachment array
$project->setAttached($attached->getResult()); // assign attachments to projects

/**
  * view: projects
  */
require_once(Config::read('dir.V').'projects.php');
exit;