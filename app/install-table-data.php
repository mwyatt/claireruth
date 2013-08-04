<?php

try {

	$epochTime = time();

	$database->dbh->query("
		insert into main_user (
			email
			, password
			, first_name
			, last_name
			, level
		) values
			('martin.wyatt@gmail.com', '" . crypt('elttl.13.admin') . "', 'Martin', 'Wyatt', '10')
			, ('realbluesman@tiscali.co.uk', '" . crypt('elttl.13.246') . "', 'Mike', 'Turner', '4')
			, ('gsaggers6@aol.com', '" . crypt('elttl.13.548') . "', 'Grant', 'Saggers', '1')
			, ('hepworth_neil@hotmail.com', '" . crypt('elttl.13.867') . "', 'Neil', 'Hepworth', '3')
			, ('henryrawcliffe@sky.com', '" . crypt('elttl.13.754') . "', 'Henry', 'Rawcliffe', '2')
	");	

	// $database->dbh->query("
	// 	INSERT INTO main_content (
	// 		title
	// 		, html
	// 		, type
	// 		, status
	// 		, user_id
	// 		, date_published
	// 	) VALUES
	// 	(
	// 	)
	// ");

	$database->dbh->query("
		INSERT INTO main_option
			(name, value)
		VALUES
			('meta_title', 'East Lancashire Table Tennis League')			
			, ('meta_keywords', 'table tennis, east lancashire, lancashire, ping pong, league, elttl, east lancashire table tennis league')
			, ('meta_description', 'The East Lancashire Table Tennis League, including Hyndburn, Rossendale, Burnley, Nelson and Ribble Valley Founded 2001 (Formerly known as the Hyndburn Table Tennis League founded in 1974)')
			, ('site_title', 'East Lancashire Table Tennis League')
			, ('site_email', 'martin.wyatt@gmail.com')			
			, ('site_social_twitter', '')
			, ('site_social_facebook', '')		
			, ('site_social_youtube', '')		
			, ('site_social_google', '')		
			, ('site_address_name', '')		
			, ('site_address_line1', '')		
			, ('site_address_line2', '')		
			, ('site_address_towncity', '')		
			, ('site_address_county', '')		
			, ('site_address_postcode', '')		
			, ('site_telephone', '')		
			, ('site_mobile', '')		
			, ('site_fax', '')	
			, ('season_status', '')
	");	

} catch (PDOException $e) { 
	echo '<h1>Exception while Installing Test Data</h1>';
	echo $e;
}