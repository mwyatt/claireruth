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
	// 	INSERT INTO main_user_meta
	// 		(user_id, name, value)
	// 	VALUES
	// 		('1', 'first_name', 'Martin')
	// 		, ('1', 'last_name', 'Wyatt')
	// 		, ('1', 'age', '24')
	// 		, ('2', 'first_name', 'Mike')
	// 		, ('2', 'last_name', 'Turner')
	// 		, ('3', 'first_name', 'Grant')
	// 		, ('3', 'last_name', 'Saggers')
	// 		, ('4', 'first_name', 'Neil')
	// 		, ('4', 'last_name', 'Hepworth')
	// 		, ('5', 'first_name', 'Harry')
	// 		, ('5', 'last_name', 'Rawcliffe')
	// ");	

	$database->dbh->query("
		INSERT INTO main_content (
			title
			, html
			, type
			, status
			, user_id
			, date_published
		) VALUES
			('Links', '<ul><li><a title=\"ETTA\" href=\"http://www.etta.co.uk\" target=\"_blank\">English Table Tennis Association</a></li><li><a title=\"Lancashire Table Tennis Association\" href=\"http://www.lctta.org.uk\" target=\"_blank\">Lancashire Table Tennis Association</a></li><li><a title=\"Thorntons Table Tennis\" href=\"http://http://www.thorntonstabletennis.co.uk/\" target=\"_blank\">Bill Thornton Table Tennis Equipment</a></li><li><a title=\"Lancashire and Cheshire League\" href=\"http://www.tabletennis.co.uk/lancscheshire\" target=\"_blank\">Lancashire &amp; Cheshire League</a></li><li><a title=\"Rushton Table Tennis\" href=\"http://www.rushtonstabletennis.com\" target=\"_blank\">Geoff Rushton&nbsp;Table Repairs</a></li><li><a title=\"Doals Table Tennis\" href=\"http://www.doalsttc.co.uk\" target=\"_blank\">Doals Table Tennis Club&nbsp;</a></li></ul>', 'page', 'visible', '1', $epochTime)
			, ('Premier league for sport', '<p><a title=\"Hyndburn TT Club\" href=\"http://eastlancstt.org.uk/local-clubs/#1\">Hyndburn Table Tennis Club</a> is part of a new project involving Premier League Football clubs and 4 other sports including Table Tennis.</p><p>Following interviews in November, <a title=\"Welcome\" href=\"http://eastlancstt.org.uk/wp-content/themes/bones/library/handbook.pdf\">Michael Moir</a> has now been appointed as the new Coach for the Project and took up post on 11th January 2011.</p><p>All existing coaches and volunteers, together with anyone who feels they would like to contribute to the project with ideas or better still wish to be directly involved in running the Club are welcome to attend club sessions on Friday evening.</p><p><a title=\"Hyndburn TT Club\" href=\"http://eastlancstt.org.uk/local-clubs/#1\">More Information on the Club</a></p>', 'page', 'visible', '1', $epochTime)
			, ('Competitions', '<p>Each division is based on having ten teams, each team will play home and away. At the end of the season the top two teams in each division will be promoted. The bottom two in each division will be relegated. If a division has only nine teams then only one would be relegated but two promoted.</p><h2>The Fred Holden (Handicap) Cup</h2><p>The Fred Holden Trophy is contested by all the teams in the league, the competition is handicapped, this season the competition will return to a knock-out. A preliminary round will start the competition with around 16 teams being involved to ensure the first round will be contested by 32 teams, it will then continue down to a final in April</p><h2>Divisional Handicap Competitions</h2><div><strong>A charge of £3 will be made for this competition</strong></div><div>This is an individual competition. Players are drawn into groups depending on numbers of entries, with other players from their division. Each group plays off as a league, with each player playing every other player in their group once.</div><div>The top players in each group then go into a knock out stage down to the semi finals which will be played on a Sunday in February/March 2013.</div><div>The competition will be held week commencing 10th December, Monday for the premier division, Tuesday for divisions 1 and 2, Wednesday for division 3.</div><div>All start at 7.15pm prompt; competitions will not be run if there are less that eight entrants.</div><h2>Summer League</h2><p>This is run usually from June to September, Monday Evening 7pm until 10pm. The competition is open to all league players, players from other leagues, players who don’t play in leagues, old players, new players, even people who have never picked up a bat in their life, absolutely anyone can take part, it’s handicapped to give everybody an equal chance, cost is £3 per night.</p><h2>Merit Competitions</h2><p>This will be displayed along with the league tables and results on the notice board, local papers and the web site. Trophies will be awarded for those finishing in first and second place.</p><h2>Annual Closed Competitions</h2><p>These will be contested on&nbsp; <strong>Sunday 24th&nbsp;Februrary 2013</strong>&nbsp;the competitions include Singles, Doubles, Handicapped Singles, Handicapped Doubles.</p><p><strong>Please note:- No competitions will be run if the entry in all singles competitions is less than eight players, and in all doubles competitions is less than sixteen players (8 teams).</strong></p>', 'page', 'visible', '1', $epochTime)
			, ('Coaching', '<p>Coaching for under 18s is now on Friday evening between 5.00 and 8.00 pm</p><p>This is part of the Premier League 4 Sport Project.</p><p>The coaching is done by Mick Moir who has been appointed as part of the Project and his role is to develop the sport in schools and across the community.</p><p>He is also delivering sessions on Wednesdays – between 1.00 and 3.00 for Adults and 4.00 and 6.00 for under 18s.</p>', 'page', 'visible', '1', $epochTime)
			, ('Schools', '<p>A number of schools in the area actively encourage table tennis and have a table tennis club during or after school.</p><p>In conjunction with Roger Haythornthwaite the schools competition manager for Hyndburn and Ribble Valley we have re-introduced an annual inter schools competition.</p><p>Visit the <a title=\"Archive - Schools Competitions\" href=\"http://eastlancstt.org.uk/archive/School%20Links.htm\">Archive</a> for information/results on past school team competitions and individual schools competitions.</p>', 'page', 'visible', '1', $epochTime)	
			, ('Town teams', '<p>Please visit the <a title=\"Archive - Town Teams\" href=\"http://eastlancstt.org.uk/archive/Town%20Teams.htm\" target=\"_blank\">Archive</a> for information on the Town Teams in past years.</p><p>Updated Town Team information will be placed here as new results arrive.</p>', 'page', 'visible', '1', $epochTime)
			, ('Summer League', '<p>Please contact <a title=\"Handbook\" href=\"http://eastlancstt.org.uk/wp-content/themes/bones/library/handbook.pdf\">Ged Simpson</a> for more information on this years Summer League.</p>', 'page', 'visible', '1', $epochTime)
			, ('Contact us', '<p><strong>Hyndburn Sports Centre</strong><br>
				Henry Street<br>
				Church<br>
				Accrington<br>
				<strong>Tel:</strong> Accrington 385945</p>
				<p><strong>Harry Rawcliffe</strong><br>
				<strong>Tel:&nbsp;</strong>01254 663451<br>
				Development Officer ELTTL</p>
				<p>&nbsp;</p>
				<img class=\"aligncenter\" src=\"http://eastlancstt.org.uk/archive/images/centre2.jpg\" alt=\"How to find us\">', 'page', 'visible', '1', $epochTime)
			, ('Local Clubs', '<section class=\"post_content clearfix\" itemprop=\"articleBody\"><table id=\"inner_menu\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td><a href=\"#1\">Hyndburn</a></td><td><a href=\"#2\">Whalley</a></td><td><a href=\"#3\">Rawtenstall</a></td><td><a href=\"#4\">Doals</a></td></tr></tbody></table><h2 id=\"1\">Hyndburn TT Club</h2><p>We offer coaching and competition for all ability levels – from beginner to advanced – we will be starting a junior league for teams and running individual competitions.</p><p><span style=\"font-size: 20px; font-weight: bold;\">Junior Session</span></p><p>We have now started a new junior club for young people interested in table tennis&nbsp;it’s held on Friday evenings. With two sessions&nbsp;5.00 till 6.30 primary age&nbsp;beginners/intermediate&nbsp;6.30 till 8.00 secondary age.</p><p>£2.00 per session</p><h2>Open Session</h2><ul><li>All Ages</li><li>Coaching Avaliable</li></ul><p>Wednesday 5:00 – 7:00</p><h2>Coaching and Competition</h2><p>We offer coaching and competition for all ability levels – from beginner to advanced – we will be starting a junior league for teams and running individual competitions.</p><p>If you don’t want to compete we will just offer coaching and facilities to practice and play just for fun.</p><p>Contact <a title=\"Welcome\" href=\"http://eastlancstt.org.uk/the-league/welcome/\">Mick Moir</a> for more information on 07531674059</p><h2>Future events</h2><ul><li>Junior League</li><li>Ladder</li></ul><hr><h2 id=\"2\">Whalley TT Club</h2><p style=\"text-align: center;\"><span style=\"font-size: 20px; font-weight: bold;\"><a title=\"Venues\" href=\"http://eastlancstt.org.uk/the-league/venues/\">Whalley Village Hall</a></span></p><p style=\"text-align: center;\">Wednesday evening 7.30 till 10.00</p><p style=\"text-align: center;\">Saturday 9.30 to 12.30</p><p>(during term time)</p><p style=\"text-align: left;\">For more information contact</p><h2 style=\"text-align: left;\">Eric Ronnan</h2><p style=\"text-align: left;\">Tel: 01254 822555</p><p>Email: ericronnan@whalleyvillagehall.org.uk</p><hr><h2 id=\"3\">Rawtenstall TT Club</h2><h2><a title=\"Venues\" href=\"http://eastlancstt.org.uk/the-league/venues/\">Kay Street Baptists Table Tennis Club</a></h2><div><p>Rawtenstall</p><p>Mondays 7.30 till 10.00</p><p>New players welcome</p><p>For more information contact</p><h3>Trevor Elkington</h3><p>Tel:&nbsp;01706 217786</p></div><p>&nbsp;</p><hr><h2 id=\"4\">Doals TT Club</h2><div class=\"diagram\"><a href=\"http://eastlancstt.org.uk/media/doals-poster.pdf\" target=\"_blank\"><img src=\"http://eastlancstt.org.uk/media/doals-poster.gif\" alt=\"Poster\"></a></div><p style=\"text-align: center;\"><strong><span style=\"font-size: xx-large;\">WEIR Village</span></strong></p><p>BACUP</p><p style=\"text-align: center;\">(Based in Doals Community Centre</p><p style=\"text-align: center;\">Next to 193 Burnley Road, OL13 8RW)</p><p><a href=\"http://www.doalsttc.co.uk/\" rel=\"nofollow\" target=\"_blank\"><span style=\"color: #0066cc; font-family: Arial; font-size: medium;\">www.doalsttc.co.uk</span></a><span style=\"font-size: medium;\"><span style=\"color: navy; font-family: Arial;\">.</span></span></p><p style=\"text-align: center;\"><img src=\"http://eastlancstt.org.uk/archive/doals_tt_files/image002.jpg\" alt=\"\" width=\"353\" height=\"249\" border=\"0\"><strong></strong></p><p>MONDAYS &amp; WEDS 7.00 till 10.00</p><p style=\"text-align: center;\">(Mondays for league home games only during season)</p><p style=\"text-align: center;\">Top quality tables, designed sports lighting &amp; a high ceiling make us a great place to play table tennis.</p><p style=\"text-align: center;\"><strong>Wheelchair friendly &amp; open to all ages 11+</strong></p><p style=\"text-align: center;\"><strong><em>Members only</em> </strong>but if interested in becoming one ring or email:</p><p>Neil Hepworth 0787 383 4942</p><p>hepworth_neil@hotmail.com</p><hr></section>', 'page', 'visible', '1', $epochTime)
	");

	// $database->dbh->query("
	// 	INSERT INTO main_content_meta
	// 		(content_id, name, value)
	// 	VALUES
	// 		('1', 'colour', 'Red')
	// 		, ('1', 'price', '200')
	// 		, ('1', 'attached', '1, 3, 2')
	// 		, ('1', 'tags', 'Photoshop, HTML, ')
	// 		, ('7', 'meta_title', 'Contact Me')
	// 		, ('7', 'meta_keywords', 'contact, me, eastlancs')
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

	// $database->dbh->query("
	// 	INSERT INTO main_menu
	// 		(title, guid, parent_id, position, type)
	// 	VALUES
	// 		('Home', 'http://localhost/mvc/', '0', '1', 'main')
	// 		, ('Posts', 'http://localhost/mvc/posts/', '0', '2', 'main')
	// 		, ('Contact', 'http://localhost/mvc/contact-me/', '0', '3', 'main')
	// ");	

	$database->dbh->query("
		INSERT INTO
			tt_division
			(name)
		VALUES
			('Premier')
			, ('First')
			, ('Second')
			, ('Third')
	");	

	$database->dbh->query("
		INSERT INTO `tt_venue` (`id`, `name`, `address`) VALUES
		(2, 'Ramsbottom Cricket Club', '53.645636,-2.313649'),
		(1, 'Burnley Boys Club', '53.803642,-2.237198'),
		(3, 'Low Moor Club', '53.870917,-2.412058'),
		(4, 'East Lancashire Cricket Club', '53.75312,-2.498476'),
		(5, 'Hyndburn Leisure Centre', '53.755232,-2.385353'),
		(6, 'Kay Street Baptist Church', '53.702059,-2.283925'),
		(7, 'Whalley Village Hall', '53.820441,-2.405952'),
		(8, 'Methodist Church Brierfield', '53.824827,-2.234167'),
		(9, 'Adpak Machinery Systems', '53.836766,-2.237565'),
		(10, 'Doals Community Centre', '53.726808,-2.198435');
	");	

	// $database->dbh->query("
	// 	INSERT INTO `tt_secretary` (`id`, `first_name`, `last_name`, `phone_landline`, `phone_mobile`) VALUES
	// 	(1, 'Graham', 'Young', '01706 220142', '07710 917055 '),
	// 	(2, 'Bryan', 'Edwards', '01617 975082', NULL),
	// 	(3, 'Peter', 'Marsland', '01282 430446', '07805761863'),
	// 	(4, 'Ian', 'Howarth', '01254 245260', '07854 444697'),
	// 	(5, 'Fred', 'Wade', '01282 789049', '07973 294690'),
	// 	(6, 'Damon', 'Blezard', '', '07766140631'),
	// 	(7, 'Doug', 'Argyle', '', '07894 552380'),
	// 	(8, 'Matthew', 'Harrison', '01282 412840', '07870 918815'),
	// 	(9, 'Wilton', 'Holt', '01706 825197', ''),
	// 	(10, 'Phil', 'Mileham', '01200 425005', '07837 686746'),
	// 	(11, 'Trev', 'Elkington', '01706 217786', '07981 387755 '),
	// 	(12, 'Grant', 'Saggers', '', '07887 902466'),
	// 	(13, 'John', 'Thornber', '01282 420856', '07979 907526 '),
	// 	(14, 'Alan', 'Prudden', '01282 459672', '07773 958330 '),
	// 	(15, 'Alan', 'Duckworth', '', '07949 061828 '),
	// 	(16, 'Martin', 'Drury', '01254 823960', '07802876259'),
	// 	(17, 'Paul', 'Wood', '01619 983703', '07714 097341 '),
	// 	(18, 'Scott', 'Thompson', '01200 427747', '07972 482818 '),
	// 	(19, 'Martin', 'Ormsby', '01254 393726', '07805683093'),
	// 	(20, 'Chris', 'Booth', '', '07429 114793 '),
	// 	(21, 'Mike', 'Hindle', '01254 703291', '07710 596735 '),
	// 	(22, 'Michael', 'Moir', '', '07531 674059 '),
	// 	(23, 'John', 'Farrow', '01282 601444', '07887 751172 '),
	// 	(24, 'Phil', 'Grace', '01254 825552', '07889 168135 '),
	// 	(25, 'Kim', 'Croyden', '01200 427655', ''),
	// 	(26, 'Neil', 'Hepworth', '01706 874636', '07873 834942 '),
	// 	(27, 'Felicity', 'Pickard', '01282 710499', '07528 495795 '),
	// 	(28, 'Ross', 'Erwin', '01706 211365', '07703 475648 '),
	// 	(29, 'Eric', 'Ronnan', ' 01254 822555', ''),
	// 	(30, 'Ryan', 'Monk', '01200 423191', '07583153457'),
	// 	(31, 'Duncan', 'Taylor', '01706 223757', '07733 128483 '),
	// 	(32, 'Bernard', 'Mills', '01706 877391', '07881 610874'),
	// 	(34, 'Harry', 'Rawcliffe', '01254 663451', NULL),
	// 	(36, 'Bob', 'Walker', '01282 685128', '07505 147363'),
	// 	(37, 'Ged', 'Simpson', '01254 237381', ' 07519 553139  '),
	// 	(39, 'Eric', 'Ronnan', '01254 822555', NULL);
	// ");	

	$database->dbh->query("
		INSERT INTO `tt_team` (`id`, `name`, `home_night`, `secretary_id`, `venue_id`, `division_id`) VALUES
		(1, 'Clitheroe LMC', 4, 0, 3, 2),
		(2, 'Hyndburn TTC A', 3, 0, 5, 1),
		(3, 'Ward & Stobbart', 1, 0, 5, 1),
		(4, 'Burnley Boys Club', 1, 0, 1, 1),
		(5, 'Ramsbottom A', 2, 0, 2, 1),
		(6, 'Ramsbottom B', 2, 0, 2, 1),
		(7, 'Rovers', 3, 0, 5, 1),
		(8, 'East Lancs', 3, 0, 4, 1),
		(9, 'Hyndburners', 1, 0, 5, 1),
		(10, 'The Lions', 3, 0, 5, 2),
		(11, 'KSB A', 2, 0, 6, 1),
		(12, 'Hyndburn TTC B', 2, 0, 5, 2),
		(13, 'Old Masters', 2, 0, 5, 2),
		(15, 'HSC', 3, 0, 5, 2),
		(16, 'Brierfield', 4, 0, 8, 1),
		(17, 'Mavericks', 1, 0, 5, 2),
		(18, 'KSB B', 3, 0, 6, 2),
		(19, 'Whalley Eagles', 3, 0, 7, 2),
		(20, 'KSB C', 3, 0, 6, 2),
		(21, 'KSB D', 3, 0, 6, 3),
		(22, 'Whalley Hawks', 3, 0, 7, 2),
		(23, 'Hyndburn TTC D ', 3, 0, 5, 4),
		(24, 'Hyndburn TTC C', 3, 0, 5, 3),
		(25, 'Tackyfire', 1, 0, 5, 3),
		(26, 'Whalley Kestrels', 3, 0, 7, 3),
		(27, 'Adpak Aces', 1, 0, 9, 3),
		(28, 'Doals Marauders', 1, 0, 10, 4),
		(29, 'The Misfits', 1, 0, 5, 4),
		(30, 'Slayers', 3, 0, 5, 3),
		(31, 'Rolling Doals', 1, 0, 10, 3),
		(33, 'Doals Jetstream', 1, 0, 10, 4),
		(34, 'Hyndburn TTC E', 3, 0, 5, 3),
		(35, 'Whalley Falcons', 3, 0, 7, 3),
		(36, 'KSB E', 1, 0, 6, 4),
		(37, 'KSB F', 1, 0, 6, 4),
		(38, 'Whalley Phoenix', 3, 0, 7, 4),
		(39, 'Doals Vikings', 1, 0, 10, 4),
		(40, 'Hyndburn TTC F ', 3, 0, 5, 4),
		(41, 'Ramsbottom C', 2, 0, 5, 2),
		(42, 'Ramsbottom D', 3, 0, 5, 3),
		(43, 'Whalley Merlins', 3, 0, 7, 4);
	");	
	
	$database->dbh->query("
		INSERT INTO `tt_player` (`id`, `first_name`, `last_name`, `rank`, `team_id`, `phone_landline`, `phone_mobile`) VALUES
		(1, 'Martin', 'Wyatt', 2532, 4, '', ''),
		(2, 'Keith', 'Lee', 2332, 4, '', ''),
		(3, 'Andrea', 'Harrison', 2280, 4, '', ''),
		(4, 'Matt', 'Harrison', 2209, 4, '', ''),
		(199, 'Cliff', 'Dale', 1849, 12, '', ''),
		(7, 'Dave', 'Kay', 2363, 4, '', ''),
		(10, 'Colin', 'Parkinson', 2326, 8, '', ''),
		(11, 'Matt', 'Nettleton', 2348, 8, '', ''),
		(12, 'Ashley', 'Bradburn', 2380, 8, '', ''),
		(13, 'Noel', 'Duffy', 2181, 8, '', ''),
		(14, 'Ian', 'Haworth', 2152, 8, '', ''),
		(15, 'Liam', 'Bedford', 2421, 8, '', ''),
		(16, 'Michael', 'Moir', 2723, 2, '', '07531 674059 '),
		(19, 'Ben', 'Farrarr', 2050, 2, '', ''),
		(20, 'Dean', 'Walmersley', 2361, 2, '', ''),
		(21, 'Graham', 'Young', 2403, 2, '01706 220142', '07710 917055'),
		(24, 'Duncan', 'Rigby', 2110, 9, '', ''),
		(25, 'Fred', 'Wade', 1872, 9, '01282 789049', '07973 294690'),
		(26, 'Dan', 'Chamberlain', 1808, 9, '', ''),
		(27, 'Dave', 'Southern', 1930, 9, '', ''),
		(31, 'Mandy', 'Winskill', 2173, 0, '', ''),
		(33, 'Graham', 'Hoy', 2399, 5, '', ''),
		(35, 'Tommy', 'Ryan', 2239, 6, '', ''),
		(36, 'Martin', 'Holt', 2346, 6, '', ''),
		(37, 'Neil', 'Booth', 2201, 6, '', ''),
		(38, 'Les', 'Phillipson', 2147, 6, '', ''),
		(40, 'Damon', 'Blezard', 2191, 7, '', '07766140631'),
		(41, 'Adam', 'Blezard', 2191, 7, '', ''),
		(42, 'Ian', 'Mason', 1957, 7, '', ''),
		(44, 'Ian', 'Mitchell', 2044, 10, '', ''),
		(45, 'Martin', 'Ormsby', 1939, 10, '01254 393726', '07805683093'),
		(46, 'Chris', 'Walton', 1807, 10, '', ''),
		(47, 'John', 'Kopec', 1726, 10, '', ''),
		(48, 'Keith', 'Ward', 2384, 3, '', ''),
		(49, 'Keith', 'Jackson', 2381, 3, '', ''),
		(50, 'Ged', 'Simpson', 2011, 3, '01254 237381', ' 07519 553139  '),
		(51, 'Jack', 'Keogh', 2173, 16, '', ''),
		(52, 'Matt', 'Hirst', 2143, 11, '', ''),
		(53, 'Daven', 'Argile', 2088, 11, '', ''),
		(54, 'Matt', 'Hodgkinson', 2047, 11, '', ''),
		(55, 'Fred', 'Coghlan', 2051, 15, '', ''),
		(56, 'David', 'Eastwood', 1935, 16, '', ''),
		(57, 'David', 'Allen', 1988, 41, '', ''),
		(58, 'Barry', 'Hall', 2075, 13, '', ''),
		(59, 'John', 'Thornber', 1882, 13, '01282 420856', '07979 907526 '),
		(60, 'Doug', 'Argile', 1813, 11, '', ''),
		(61, 'Ian', 'Beecroft', 1881, 17, '', ''),
		(62, 'Graham', 'Burns', 1745, 15, '', ''),
		(63, 'Bob', 'Birch', 1885, 1, '', ''),
		(64, 'Alan', 'Prudden', 1842, 17, '01282 459672', '07773 958330 '),
		(65, 'Ray', 'Kay', 1981, 9, '', ''),
		(66, 'Trevor', 'Elkington', 1977, 18, '', ''),
		(67, 'Tim', 'Fields', 1891, 41, '', ''),
		(68, 'Mike', 'Turner', 1653, 19, '', ''),
		(69, 'Scott', 'Thompson', 1750, 1, '01200 427747', '07972 482818 '),
		(71, 'Derek', 'Edwards', 1700, 41, '', ''),
		(73, 'Kieran', 'Cunliffe', 1672, 22, '', ''),
		(74, 'John', 'Burgoyne', 1686, 17, '', ''),
		(75, 'Roger', 'Whewell', 1672, 13, '', ''),
		(77, 'Peter', 'Howard', 1782, 16, '', ''),
		(78, 'Phil', 'Hutchinson', 1679, 1, '', ''),
		(79, 'Frank', 'Hamer', 1639, 15, '', ''),
		(80, 'Ian', 'Pickles', 1671, 13, '', ''),
		(81, 'Peter', 'Marsland', 1576, 16, '01282 430446', '07805761863'),
		(82, 'John', 'Collins', 1841, 18, '', ''),
		(84, 'David', 'Borland', 1840, 19, '', ''),
		(85, 'Alan', 'Calow', 1872, 19, '', ''),
		(86, 'Craig', 'Milnes', 1742, 18, '', ''),
		(87, 'Paul', 'Wood', 1646, 41, '01619 983703', '07714 097341 '),
		(88, 'Phil', 'Mileham', 1856, 19, '01200 425005', '07837 686746'),
		(90, 'Graham', 'Meloy', 1536, 17, '', ''),
		(91, 'Grant', 'Saggers', 1399, 20, '', '07887 902466'),
		(92, 'Alan', 'Duckworth', 1608, 15, '', '07949 061828 '),
		(93, 'Derek', 'Parkinson', 1443, 16, '', ''),
		(94, 'Simon', 'Kavanagh', 1121, 21, '', ''),
		(96, 'Albert', 'Pickles', 1772, 13, '', ''),
		(98, 'Mark', 'Gleave', 1951, 15, '', ''),
		(99, 'Simon', 'Charnley', 2241, 41, '', ''),
		(101, 'Stephen', 'Siddall', 1629, 42, '', ''),
		(102, 'Neil', 'McKinnon', 1631, 20, '', ''),
		(103, 'Elton', 'Atkins', 1355, 25, '', ''),
		(104, 'Pak', 'Wan', 1235, 25, '', ''),
		(106, 'Charlie', 'McGrath', 1997, 2, '', ''),
		(107, 'Steve', 'Horner', 1742, 20, '', ''),
		(108, 'Richard', 'Staines', 1241, 24, '', ''),
		(110, 'Adam', 'Robinson', 1803, 22, '', ''),
		(111, 'Gerald', 'Laxton', 1323, 31, '', ''),
		(112, 'Tim', 'Prior', 1140, 20, '', ''),
		(114, 'Martin', 'Drury', 1581, 22, '01254 823960', '07802876259'),
		(115, 'Philip', 'Grace', 1248, 26, '', ''),
		(116, 'Chris', 'Booth', 1240, 25, '', '07429 114793 '),
		(117, 'Barrie', 'Howarth', 959, 21, '', ''),
		(120, 'Paul', 'McGovern', 1198, 27, '', ''),
		(122, 'Matthew', 'Birch', 971, 27, '', ''),
		(123, 'Keith', 'Ainscoe', 1069, 29, '', ''),
		(124, 'Chris', 'Leaves', 962, 42, '', ''),
		(125, 'Paul', 'Waddington', 1193, 30, '', ''),
		(128, 'Harry', 'Rawcliffe', 875, 29, '01254 663451', ''),
		(129, 'John', 'Farrow', 1133, 27, '01282 601444', '07887 751172 '),
		(130, 'Alan', 'Ross', 952, 35, '', ''),
		(131, 'Mike', 'Hindle', 889, 30, '01254 703291', '07710 596735 '),
		(132, 'Peter', 'Booth', 891, 30, '', ''),
		(133, 'Mark', 'Read', 749, 29, '', ''),
		(134, 'Anthony', 'Farrow', 906, 27, '', ''),
		(135, 'Eddie', 'Pilling', 539, 29, '', ''),
		(136, 'Duncan', 'Taylor', 681, 28, '01706 223757', '07733 128483 '),
		(137, 'Alven', 'Burrows', 684, 30, '', ''),
		(139, 'Adam', 'Hek', 1020, 25, '', ''),
		(140, 'Dominic', 'Siddall', 1114, 42, '', ''),
		(143, 'Neil', 'Hepworth', 1413, 31, '01706 874636', '07873 834942 '),
		(144, 'Steve', 'Nightingale', 1243, 26, '', ''),
		(146, 'Carlton', 'Cooper', 1046, 36, '', ''),
		(148, 'Ryan', 'Fallon', 1020, 31, '', ''),
		(149, 'Bob', 'Walker', 576, 33, '01282 685128', '07505 147363'),
		(150, 'Ian', 'Mills', 663, 28, '', ''),
		(151, 'Kim', 'Croydon', 820, 35, '', ''),
		(152, 'Bernard', 'Milnes', 800, 37, '', ''),
		(156, 'Eric', 'Ronnan', 616, 43, '01254 822555', ''),
		(157, 'Peter', 'Hepworth', 911, 31, '', ''),
		(158, 'Jon', 'Andrews', 547, 39, '', ''),
		(160, 'Hugh', 'Graham', 858, 35, '', ''),
		(161, 'Warwick', 'Lewthwaite', 601, 43, '', ''),
		(163, 'Matt', 'Calow', 457, 38, '', ''),
		(164, 'Bob', 'Thompson', 456, 39, '', ''),
		(165, 'Dominic', 'Walsh', 572, 28, '', ''),
		(166, 'Ian', 'Glover', 573, 36, '', ''),
		(169, 'Graham', 'Davie', 394, 38, '', ''),
		(171, 'Zachary', 'Geldard', 994, 34, '', ''),
		(172, 'Felicity', 'Pickard', 1084, 24, '01282 710499', '07528 495795 '),
		(173, 'Reece', 'Monk', 841, 23, '', ''),
		(174, 'Daniel', 'O''Sullivan', 315, 40, '', ''),
		(175, 'John', 'Price', 259, 38, '', ''),
		(176, 'Kean', 'Erwin', 561, 36, '', ''),
		(178, 'Peter', 'Brzezicki', 218, 40, '', ''),
		(181, 'Paul', 'Milnes', 485, 37, '', ''),
		(182, 'Ryan', 'Monk', 568, 23, '01200 423191', '07583153457'),
		(183, 'Will', 'Cooper', 420, 36, '', ''),
		(185, 'Sam', 'Hinchcliffe', 741, 34, '', ''),
		(186, 'Harry', 'Walker', 200, 33, '', ''),
		(187, 'Cory', 'Foster', 538, 23, '', ''),
		(188, 'Dec', 'O''Kane', 824, 34, '', ''),
		(189, 'Roger', 'Millham', 204, 40, '', ''),
		(200, 'Nigel', 'Fewster', 1863, 12, '', ''),
		(201, 'Ian', 'Lloyd', 1799, 12, '', ''),
		(203, 'Mina', 'Makram', 1800, 12, '', ''),
		(204, 'David', 'Cain', 1755, 41, '', ''),
		(205, 'Frank', 'Gray', 1737, 17, '', ''),
		(206, 'Catherine', 'Lawson', 1231, 25, '', ''),
		(207, 'Malcolm', 'Pelham', 300, 23, '', ''),
		(208, 'Thomas', 'Boden', 176, 40, '', ''),
		(210, 'Ian', 'Wheeldon', 1691, 41, '', ''),
		(211, 'Tom', 'Warbuton', 1025, 42, '', ''),
		(212, 'Jordan', 'Brookes', 2120, 5, '', ''),
		(213, 'Michael', 'Brierley', 2173, 5, '', ''),
		(214, 'Alan', 'Auxilly', 2040, 5, '', ''),
		(215, 'Terry', 'Taylor', 2000, 6, '', ''),
		(216, 'Michael', 'Wrigley', 1745, 16, '', ''),
		(217, 'Peter', 'Norcliffe', 750, 35, '', ''),
		(218, 'Stuart', 'Cole', 1144, 21, '', ''),
		(220, 'Adam', 'Kemp', 1237, 24, '', ''),
		(221, 'Ross', 'Erwin', 1063, 36, '01706 211365', '07703 475648 '),
		(222, 'Simon', 'Milnes', 378, 37, '', ''),
		(223, 'Ben', 'Milnes', 300, 37, '', ''),
		(224, 'David', 'Greenwood', 671, 39, '', ''),
		(225, 'Stuart', 'Summer', 160, 33, '', ''),
		(233, 'Thomas', 'Summer', 234, 33, '', ''),
		(226, 'John', 'Walsh', 193, 39, '', ''),
		(227, 'Roman', 'Mychajlshyn', 754, 28, '', ''),
		(228, 'Luke', 'Waring', 384, 43, '', ''),
		(229, 'Jorden', 'Barnowski', 496, 43, '', ''),
		(230, 'Chris', 'Donald', 580, 38, '', ''),
		(231, 'Phillip', 'Hutchinson', 972, 26, '', ''),
		(232, 'Kathan', 'Oldfield', 850, 42, '', ''),
		(234, 'James', 'Croydon', 792, 38, '', ''),
		(235, 'Dave', 'Evans', 271, 39, '', ''),
		(236, 'Burhan', 'Khan', 794, 40, '', ''),
		(237, 'Eli', 'Smith', 464, 40, '', ''),
		(238, 'Francis', 'Kinsella', 410, 40, '', ''),
		(239, 'Thomas', 'Fewster', 263, 40, '', ''),
		(240, 'Mick', 'Cage', 1394, 18, '', ''),
		(105, 'Bryan', 'Edwards', 1328, 42, '01617 975082', ''),
		(241, 'Mark', 'Buckley', 200, 37, '', ''),
		(242, 'Anthony', 'Richardson', 388, 43, '', ''),
		(243, 'Duggie', 'Hills', 1981, 5, '', ''),
		(244, 'Brian', 'Glazier', 1600, 18, '', ''),
		(245, 'Mozi', 'Maffi', 1036, 21, '', ''),
		(246, 'Luke', 'Donald', 184, 38, '', ''),
		(247, 'Mohammed', 'Buta', 1751, 15, '', ''),
		(248, 'Peter', 'Lee', 2165, 5, '', ''),
		(249, 'Michael', 'Mullarkey', 1383, 20, '', '');
	");	

} catch (PDOException $e) { 
	echo '<h1>Exception while Installing Test Data</h1>';
	echo $e;
}