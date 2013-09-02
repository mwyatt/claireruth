<?php

try {	
		
	// to manage the users allowed to log into this website
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS
			main_user
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, email VARCHAR(50) NOT NULL DEFAULT ''
				, password VARCHAR(255) NOT NULL DEFAULT ''
				, first_name VARCHAR(75) NOT NULL DEFAULT ''
				, last_name VARCHAR(75) NOT NULL DEFAULT ''
				, date_registered TIMESTAMP DEFAULT NOW()
				, level TINYINT(1) UNSIGNED NOT NULL DEFAULT '1'
				, PRIMARY KEY (id)
			)
	");
	
	// keep track of the actions made by users registered to this website
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS 
			main_user_action
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, description VARCHAR(255) NOT NULL DEFAULT ''
				, user_id INT UNSIGNED
				, time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
				, action VARCHAR(6) NOT NULL DEFAULT ''
				, PRIMARY KEY (id)
				, KEY (user_id)
			)
	");

	// main focus is the blog posts, pages or any other content which
	// requires a title and / or content
	// status
	// 		hidden
	// 		deleted (new)
	// 		visible
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS 
			main_content
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, title VARCHAR(255) NOT NULL DEFAULT ''
				, html VARCHAR(8000) DEFAULT ''
				, type VARCHAR(50) NOT NULL DEFAULT ''
				, date_published INT UNSIGNED DEFAULT 0
				, status VARCHAR(50) NOT NULL DEFAULT 'hidden'
				, user_id INT UNSIGNED NOT NULL	
				, PRIMARY KEY (id)
				, KEY (user_id)
			)
	");

	/**
	 * relational database between content and the media which is attached
	 * the first media found in a query here will be the featured item?
	 */
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS 
			main_content_media
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, content_id INT UNSIGNED NOT NULL
				, media_id INT UNSIGNED NOT NULL
				, PRIMARY KEY (id)
				, KEY (content_id)
				, KEY (media_id)
			)
	");


	/**
	 * relates to the content table to allow the assignment of various
	 * tags, on each load all unique ones will be found and then
	 * displayed on front end
	 */
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS 
			main_content_tag
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, content_id INT UNSIGNED NOT NULL
				, name VARCHAR(255) NOT NULL DEFAULT ''
				, PRIMARY KEY (id)
				, KEY (content_id)
			)
	");

	// concerns itself with all core options which need to be stored in
	// permenant storage, (site title etc)
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS 
			main_option
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, name VARCHAR(255) NOT NULL DEFAULT ''
				, value VARCHAR(255) NOT NULL DEFAULT ''
				, PRIMARY KEY (id)
			)
	");	

	// all uploaded media has a corresponding database entry? currently under
	// used
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS 
			main_media
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, title VARCHAR(500) NOT NULL
				, path VARCHAR(500) NOT NULL
				, date_published INT UNSIGNED DEFAULT 0
				, user_id INT UNSIGNED NOT NULL
				, PRIMARY KEY (id)
				, KEY (user_id)
			)		
	");
} catch (PDOException $e) { 
	echo '<h1>Exception while Installing Tables</h1>';
	echo $e;
}