<?php

try {
	$database->dbh->query("
		insert into main_user (
			email
			, password
			, first_name
			, last_name
			, level
		) values
			('123', '" . crypt('123') . "', 'Martin', 'Wyatt', '10')
	");	
	$database->dbh->query("
		INSERT INTO main_option
			(name, value)
		VALUES
			('meta_title', 'Website title')			
			, ('meta_keywords', 'example, key, words')
			, ('meta_description', 'Website description')
			, ('site_title', 'Website title')
			, ('site_email', 'example@example.com')
			, ('site_social_twitter', 'username')
			, ('site_social_facebook', 'url')
			, ('site_social_youtube', 'username')		
			, ('site_social_google', 'username')
			, ('site_address_name', '')
			, ('site_address_line1', '')		
			, ('site_address_line2', '')		
			, ('site_address_line3', '')		
			, ('site_address_line4', '')		
			, ('site_address_towncity', 'Town / City')
			, ('site_address_county', 'County / Area')		
			, ('site_address_postcode', 'Postcode')		
			, ('site_telephone', '01234 567890')
			, ('site_mobile', '01234 567890')
	");	
} catch (PDOException $e) { 
	echo '<h1>Exception while Installing Test Data</h1>';
	echo $e;
}