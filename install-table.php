<?php

try {	
		
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

	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS 
			main_content_meta
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, content_id INT UNSIGNED NOT NULL
				, name VARCHAR(255) NOT NULL DEFAULT ''
				, value VARCHAR(255) NOT NULL DEFAULT ''
				, PRIMARY KEY (id)
				, KEY (content_id)
			)
	");

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
	
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS
			tt_archive
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, title VARCHAR(20) NOT NULL DEFAULT ''
				, html longtext NOT NULL
				, PRIMARY KEY (id)
			)		
	");	
	
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS
			tt_division
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, name VARCHAR(20) NOT NULL
				, PRIMARY KEY (id)
			)		
	");	
	
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS
			tt_venue
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, name VARCHAR(75) NOT NULL
				, address VARCHAR(200) NOT NULL
				, PRIMARY KEY (id)
			)		
	");		
	
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS
			tt_team
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, name VARCHAR(75) NOT NULL
				, home_night TINYINT(1)
				, secretary_id INT UNSIGNED
				, venue_id INT UNSIGNED NOT NULL
				, division_id INT UNSIGNED NOT NULL
				, PRIMARY KEY (id)
				, KEY (secretary_id)
				, KEY (venue_id)
				, KEY (division_id)
			)		
	");	
	
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS
			tt_player
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, first_name VARCHAR(75) NOT NULL DEFAULT ''
				, last_name VARCHAR(75) NOT NULL DEFAULT ''
				, rank INT UNSIGNED
				, team_id INT UNSIGNED
				, etta_license_number VARCHAR(10) NOT NULL DEFAULT ''
				, phone_landline VARCHAR(30) DEFAULT ''
				, phone_mobile VARCHAR(30) DEFAULT ''
				, PRIMARY KEY (id)
				, KEY (team_id)
			)		
	");	
	
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS
			tt_fixture
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, team_left_id INT UNSIGNED NOT NULL
				, team_right_id INT UNSIGNED NOT NULL
				, date_fulfilled INT
				, PRIMARY KEY (id)
				, KEY (team_left_id)
				, KEY (team_right_id)
			)		
	");	
	
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS
			tt_encounter_part
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, player_id INT UNSIGNED
				, player_score TINYINT UNSIGNED
				, player_rank_change TINYINT
				, status VARCHAR(20)
				, PRIMARY KEY (id)
			)		
	");	
	
	$database->dbh->query("
		CREATE TABLE IF NOT EXISTS
			tt_encounter
			(
				id INT UNSIGNED NOT NULL AUTO_INCREMENT
				, part_left_id INT UNSIGNED
				, part_right_id INT UNSIGNED
				, fixture_id INT UNSIGNED NOT NULL
				, PRIMARY KEY (id)
				, KEY (part_left_id)
				, KEY (part_right_id)
			)		
	");	

	$database->dbh->query("	
		create view tt_encounter_result as
		select
			tt_encounter.id as encounter_id
			, tt_encounter_part_left.id as tt_encounter_part_left_id
			, tt_encounter_part_right.id as tt_encounter_part_right_id
			, tt_encounter_part_left.player_id as left_id
			, tt_encounter_part_right.player_id as right_id
			, tt_encounter_part_left.player_score as left_score
			, tt_encounter_part_right.player_score as right_score
			, tt_encounter_part_left.player_rank_change as left_rank_change
			, tt_encounter_part_right.player_rank_change as right_rank_change
			, tt_encounter.fixture_id
			, tt_encounter_part_left.status
		from tt_encounter
		left join tt_encounter_part as tt_encounter_part_left on tt_encounter_part_left.id = tt_encounter.part_left_id
		left join tt_encounter_part as tt_encounter_part_right on tt_encounter_part_right.id = tt_encounter.part_right_id
		group by tt_encounter.id
	");
	
	$database->dbh->query("	
		create view tt_fixture_result as
		select
			tt_fixture.id as fixture_id
			, team_left.id as left_id
			, team_right.id as right_id
			, sum(tt_encounter_result.left_score) as left_score
			, sum(tt_encounter_result.right_score) as right_score
		from tt_fixture
		left join tt_team as team_left on team_left.id = tt_fixture.team_left_id
		left join tt_team as team_right on team_right.id = tt_fixture.team_right_id
		left join tt_encounter_result on tt_encounter_result.fixture_id = tt_fixture.id
		where tt_fixture.date_fulfilled IS NOT NULL
		group by tt_fixture.id
	");
	
} catch (PDOException $e) { 
	echo '<h1>Exception while Installing Tables</h1>';
	echo $e;
}