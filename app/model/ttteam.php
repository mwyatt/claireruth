<?php

/**
 * Fixture
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Ttteam extends Model
{	

	static $weekDays = array(
		1 => 'Monday'
		, 2 => 'Tuesday'
		, 3 => 'Wednesday'
		, 4 => 'Thursday'
		, 5 => 'Friday'
		, 6 => 'Saturday'
		, 7 => 'Sunday'
	);


	public function create() {	
 		if (! $this->validatePost(array('name', 'division_id', 'home_night', 'venue_id', 'form_create'))) {
			$this->session->set('feedback', 'All required fields must be filled');
			return false;
		}
		$sth = $this->database->dbh->prepare("
			INSERT INTO tt_team (
				name
				, home_night
				, venue_id
				, division_id
				, secretary_id
			) VALUES (
				:name
				, :home_night
				, :venue_id
				, :division_id
				, :secretary_id
			)
		");				
		$sth->execute(array(
			':name' => (array_key_exists('name', $_POST) ? $_POST['name'] : '')
			, ':home_night' => (array_key_exists('home_night', $_POST) ? $_POST['home_night'] : '')
			, ':venue_id' => (array_key_exists('venue_id', $_POST) ? $_POST['venue_id'] : '')
			, ':division_id' => (array_key_exists('division_id', $_POST) ? $_POST['division_id'] : '')
			, ':secretary_id' => (array_key_exists('secretary_id', $_POST) ? $_POST['secretary_id'] : '')
		));	
		if ($sth->rowCount()) {
			$this->session->set('feedback', 'Success, team "' . $_POST['name'] . '" created');
			return $this->database->dbh->lastInsertId();
		}
		$this->session->set('feedback', 'Problem while creating team');
		return false;
	}

	
	public function readWeekDays() {
		$this->data = $this->getWeekDays();
	}

	public function getWeekDays() {

		return self::$weekDays;

	}


	/**
	 * update team record using post
	 * @param  array $post 
	 * @return bool        
	 */
	public function update($id) {
 		if (! $this->validatePost(array('name', 'division_id', 'home_night', 'venue_id', 'form_create'))) {
			$this->session->set('feedback', 'All required fields must be filled');
			return false;
		}
		$sth = $this->database->dbh->prepare("
			select
				tt_team.name
			from
				tt_team
			where id = ?
		");				
		$sth->execute(array(
			$id
		));		
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = $this->database->dbh->prepare("
			update
				tt_team
			set
				name = :name
				, secretary_id = :secretary_id
				, venue_id = :venue_id
				, home_night = :home_night
				, division_id = :division_id
				, secretary_id = :secretary_id
			where
				id = :id
		");				
		$sth->execute(array(
			':name' => (array_key_exists('name', $_POST) ? $_POST['name'] : '')
			, ':id' => $id
			, ':secretary_id' => (array_key_exists('secretary_id', $_POST) ? $_POST['secretary_id'] : '')
			, ':venue_id' => (array_key_exists('venue_id', $_POST) ? $_POST['venue_id'] : '')
			, ':home_night' => (array_key_exists('home_night', $_POST) ? $_POST['home_night'] : '')
			, ':division_id' => (array_key_exists('division_id', $_POST) ? $_POST['division_id'] : '')
			, ':secretary_id' => (array_key_exists('secretary_id', $_POST) ? $_POST['secretary_id'] : '')
		));		
		$this->session->set('feedback', 'Team ' . ucfirst($row['name']) . ' updated');
		return true;
	}


	/**
	 * read team by id
	 * @param  int $id 
	 * @return bool     
	 */
	public function readById($ids)
	{	
		$sth = $this->database->dbh->prepare("	
			select
				tt_team.id
				, tt_team.name
				, tt_team.secretary_id
				, concat(tt_secretary.first_name, ' ', tt_secretary.last_name) as secretary_full_name
				, tt_secretary.phone_landline as secretary_phone_landline
				, tt_secretary.phone_mobile as secretary_phone_mobile
				, tt_team.home_night as home_night_id
				, tt_team.home_night
				, count(main_player.id) as player_count
				, tt_venue.id as venue_id
				, tt_venue.name as venue_name
				, tt_team.division_id
				, tt_division.name as division_name
			from tt_team
			left join tt_player as main_player on main_player.team_id = tt_team.id
			left join tt_player as tt_secretary on tt_secretary.id = tt_team.secretary_id
			left join tt_division on tt_team.division_id = tt_division.id
			left join tt_venue on tt_team.venue_id = tt_venue.id
			where tt_team.id = ?
			group by tt_team.id
			order by tt_division.id, tt_team.name
		");
		foreach ($ids as $id) {
			$sth->execute(array($id));
			$team = $sth->fetch(PDO::FETCH_ASSOC);
			$teams[$team['id']] = $team;
			$teams[$team['id']]['home_night'] = self::$weekDays[$team['home_night']];
			$teams[$team['id']]['division_guid'] = $this->getGuid('division', $team['division_name']);
			$teams[$team['id']]['secretary_guid'] = $this->getGuid('player', $team['secretary_full_name'], $team['secretary_id']);
			$teams[$team['id']]['guid'] = $this->getGuid('team', $team['name'], $team['id']);
		}
		if (count($teams) == 1) {
			$teams = current($teams);
		}
		if (! empty($teams)) {
			return $this->data = $teams;
		}
		return $sth->rowCount();
	}	


	public function deleteById($id)
	{	
	
		// tied to any fixtures?

		$sth = $this->database->dbh->prepare("

			select
				count(tt_fixture.id) as count

			from
				tt_team

			left join
				tt_fixture on tt_fixture.team_left_id = tt_team.id or tt_fixture.team_right_id = tt_team.id

			where
				tt_team.id = :id

			group by
				tt_team.id

		");				
		
		$sth->execute(array(
			':id' => $id
		));

		if ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
		
			if ($row['count']) {

				$this->getObject('mainUser')->setFeedback('Unable to delete team, fixtures have been generated');

				return false;

			}
		
		}

		// tied to any players?

		$sth = $this->database->dbh->prepare("

			select
				count(tt_player.id) as count

			from
				tt_team

			left join
				tt_player on tt_player.team_id = tt_team.id

			where
				tt_team.id = :id

			group by
				tt_team.id

		");				
		
		$sth->execute(array(
			':id' => $id
		));

		if ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
		
			if ($row['count']) {

				$this->getObject('mainUser')->setFeedback('Unable to delete team, players are assigned to it');

				return false;

			}
		
		}

		// delete team

		$sth = $this->database->dbh->prepare("

			delete from
				tt_team

			where
				tt_team.id = :id

		");				
		
		$sth->execute(array(
			':id' => $id
		));		

		// feedback & return

		if ($sth->rowCount()) {

			$this->getObject('mainUser')->setFeedback('Team Deleted Successfully');

		} else {

			$this->getObject('mainUser')->setFeedback('Error occurred, team not deleted');

		}

	}



	// public function selectByDivision($id)
	// {	
	
	// 	$sth = $this->database->dbh->query("	
	// 		SELECT
	// 			tt_team.id AS team_id
	// 			, tt_team.name AS team_name
	// 		FROM
	// 			tt_team
	// 		LEFT JOIN tt_division ON tt_team.division_id = tt_division.id
	// 		WHERE tt_division.id = '$id'
	// 		ORDER BY tt_team.id
	// 	");
		
	// 	while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
		
	// 		$this->data[$row['team_id']] = $row;
	
	// 	}	

	// }	

	
	public function read()
	{	
	
		$sth = $this->database->dbh->query("	
			select
				tt_team.id
				, tt_team.name
				, tt_team.home_night
				, count(tt_player.id) as player_count
				, tt_venue.name as venue_name
				, tt_division.name as division_name
			from
				tt_team
			left join tt_player on tt_player.team_id = tt_team.id
			left join tt_division on tt_team.division_id = tt_division.id
			left join tt_venue on tt_team.venue_id = tt_venue.id
			group by tt_team.id
			order by
				tt_division.id
				, tt_team.name
		");

		$view = new View($this->database, $this->config);

		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	

			if (array_key_exists('home_night', $row) && $row['home_night']) {
				$row['home_night'] = self::$weekDays[$row['home_night']];
			}

			$row['guid'] = $this->getGuid('team', $row['name'], $row['id']);

			$this->data[] = $row;
		
		}

		return $this;

	}	
	

	public function delete($id)	{
		$sth = $this->database->dbh->prepare("
			update tt_player set
				team_id = ?
			where
				team_id = ?
		");				
		$sth->execute(array(0, $id));	
		$sth = $this->database->dbh->prepare("
			select
				tt_team.name
			from tt_team
			where id = ?
		");				
		$sth->execute(array($id));	
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = $this->database->dbh->prepare("
			delete from tt_team
			where id = ?
		");				
		$sth->execute(array($id));	
		if ($sth->rowCount()) {
			$this->session->set('feedback', 'Team ' . ucfirst($row['name']) . ' deleted');
			return;
		}
		$this->session->set('feedback', 'Unable to delete team ' . ucfirst($row['name']));
		return;
	}


	public function readTotalByDivision($id) {
		$sth = $this->database->dbh->prepare("
			select
				count(tt_team.id)
			from tt_team
			where tt_team.division_id = ?
			group by tt_team.id
		");				
		$sth->execute(array($id));
		$this->data = $sth->rowCount();
		return $sth->rowCount();
	}


	public function readByDivision($id) {
		$sth = $this->database->dbh->prepare("	
			select
				tt_team.id
				, tt_team.name
			from tt_team
			where tt_team.division_id = ?
			group by tt_team.id
			order by tt_team.name asc
		");
		$sth->execute(array($id));
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$row['guid'] = $this->getGuid('team', $row['name'], $row['id']);
			$this->data[] = $row;
		}
		return $this;
	}


	public function readLeague($divisionId) {
		$sth = $this->database->dbh->prepare("	
			select
				tt_team.id
				, tt_team.name
				, sum(case when tt_fixture_result.left_id = tt_team.id and tt_fixture_result.left_score > tt_fixture_result.right_score or tt_fixture_result.right_id = tt_team.id and tt_fixture_result.right_score > tt_fixture_result.left_score then 1 else 0 end) as won
				, sum(case when tt_fixture_result.left_id = tt_team.id and tt_fixture_result.left_score = tt_fixture_result.right_score or tt_fixture_result.right_id = tt_team.id and tt_fixture_result.right_score = tt_fixture_result.left_score then 1 else 0 end) as draw
				, sum(case when tt_fixture_result.left_id = tt_team.id and tt_fixture_result.left_score < tt_fixture_result.right_score or tt_fixture_result.right_id = tt_team.id and tt_fixture_result.right_score < tt_fixture_result.left_score then 1 else 0 end) as lost
				, count(tt_fixture_result.fixture_id) as played
				, (sum(case when tt_fixture_result.left_id = tt_team.id then tt_fixture_result.left_score else 0 end) + sum(case when tt_fixture_result.right_id = tt_team.id then tt_fixture_result.right_score else 0 end)) as points
			from tt_team
			left join tt_fixture_result on tt_fixture_result.left_id = tt_team.id or tt_fixture_result.right_id = tt_team.id
			where tt_team.division_id = :division_id
			group by tt_team.id
			order by points desc
		");
		$sth->execute(array(':division_id' => $divisionId));
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$row['guid'] = $this->getGuid('team', $row['name'], $row['id']);
			if ($row['points']) {
				$this->data[] = $row;
			}
		}
		if ($this->data) {
			return $this->data;
		} else {
			return false;
		}
	}


	public function readLeagueByTeam($id) {
		$sth = $this->database->dbh->prepare("	
			select
				tt_team.id
				, tt_team.name
				, sum(case when tt_fixture_result.left_id = tt_team.id and tt_fixture_result.left_score > tt_fixture_result.right_score or tt_fixture_result.right_id = tt_team.id and tt_fixture_result.right_score > tt_fixture_result.left_score then 1 else 0 end) as won
				, sum(case when tt_fixture_result.left_id = tt_team.id and tt_fixture_result.left_score = tt_fixture_result.right_score or tt_fixture_result.right_id = tt_team.id and tt_fixture_result.right_score = tt_fixture_result.left_score then 1 else 0 end) as draw
				, sum(case when tt_fixture_result.left_id = tt_team.id and tt_fixture_result.left_score < tt_fixture_result.right_score or tt_fixture_result.right_id = tt_team.id and tt_fixture_result.right_score < tt_fixture_result.left_score then 1 else 0 end) as lost
				, count(tt_fixture_result.fixture_id) as played
				, (sum(case when tt_fixture_result.left_id = tt_team.id then tt_fixture_result.left_score else 0 end) + sum(case when tt_fixture_result.right_id = tt_team.id then tt_fixture_result.right_score else 0 end)) as points
			from tt_team
			left join tt_fixture_result on tt_fixture_result.left_id = tt_team.id or tt_fixture_result.right_id = tt_team.id
			where tt_team.id = ?
		");
		$sth->execute(array($id));
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			if (! $row['played']) {
				continue;
			}
			$row['guid'] = $this->getGuid('team', $row['name'], $row['id']);
			if ($row['points']) {
				$this->data = $row;
			}
		}
		if ($this->data) {
			return $this->data;
		} else {
			return false;
		}
	}

}