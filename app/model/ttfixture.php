<?php

/**
 * ttFixture
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Ttfixture extends Model
{


	public static $encounterStructure = array(
		0 => array(1, 2)
		, 1 => array(3, 1)
		, 2 => array(2, 3)
		, 3 => array(3, 2)
		, 4 => array(1, 3)
		, 5 => array('doubles', 'doubles')
		, 6 => array(2, 1)
		, 7 => array(3, 3)
		, 8 => array(2, 2)
		, 9 => array(1, 1)
	);
	
	
	/**
	 * return $encounterParts for output on scoresheet
	 */
	public function getEncounterStructure() {

		return self::$encounterStructure;

	}


	public function readTotalByDivision($id) {
		$sth = $this->database->dbh->prepare("
			select
				count(tt_fixture.id)
			from tt_fixture
			left join tt_team as team_left on team_left.id = tt_fixture.team_left_id
			left join tt_team as team_right on team_right.id = tt_fixture.team_right_id
			left join tt_division on team_left.division_id = tt_division.id
			where team_left.division_id = ?
			group by tt_fixture.id
		");				
		$sth->execute(array($id));
		$this->data = $sth->rowCount();
		return $sth->rowCount();
	}

	
	/**
	 * selects all fixtures
	 * @return null 
	 */
	public function read() {	
		$sth = $this->database->dbh->query("	
			select
				tt_fixture.id
				, tt_fixture.date_fulfilled
				, team_left.name as team_left_name
				, team_right.name as team_right_name
				, sum(encounter_part_left.player_score) as score_left
				, sum(encounter_part_right.player_score) as score_right
				, tt_division.id as division_id
			from tt_fixture
			left join tt_team as team_left on team_left.id = tt_fixture.team_left_id
			left join tt_team as team_right on team_right.id = tt_fixture.team_right_id
			left join tt_encounter on tt_encounter.fixture_id = tt_fixture.id
			left join tt_encounter_part as encounter_part_left on encounter_part_left.id = tt_encounter.part_left_id
			left join tt_encounter_part as encounter_part_right on encounter_part_right.id = tt_encounter.part_right_id
			left join tt_division on team_left.division_id = tt_division.id
			group by tt_fixture.id
		");
		if ($sth->rowCount()) {
			$this->data = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		return;
	}	


	public function readFilled() {	
		$sth = $this->database->dbh->query("	
			select
				tt_fixture.id
				, tt_fixture.date_fulfilled
				, team_left.name as team_left_name
				, team_right.name as team_right_name
				, sum(encounter_part_left.player_score) as score_left
				, sum(encounter_part_right.player_score) as score_right
				, tt_division.id as division_id
			from tt_fixture
			left join tt_team as team_left on team_left.id = tt_fixture.team_left_id
			left join tt_team as team_right on team_right.id = tt_fixture.team_right_id
			left join tt_encounter on tt_encounter.fixture_id = tt_fixture.id
			left join tt_encounter_part as encounter_part_left on encounter_part_left.id = tt_encounter.part_left_id
			left join tt_encounter_part as encounter_part_right on encounter_part_right.id = tt_encounter.part_right_id
			left join tt_division on team_left.division_id = tt_division.id
			where tt_fixture.date_fulfilled is not null
			group by tt_fixture.id
		");
		foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$row['guid'] = $this->getGuid('fixture', $row['team_left_name'] . '-' . $row['team_right_name'], $row['id']);
			$this->data[] = $row;
		}
		return $this;
	}	

	
	/**
	 * fullfills the fixture, will be recalled when 'updating' a fixture
	 * @return bool sessison set with message
	 */
	public function fulfill() {
		if (! $this->validatePost(array('division_id', 'team', 'player', 'encounter'))) {
			$this->session->set('feedback', 'Please fill all required fields');
			return false;
		}
		$sthFixture = $this->database->dbh->prepare("	
			select
				tt_fixture.id
				, tt_fixture.team_left_id
				, team_left.name as team_left_name
				, tt_fixture.team_right_id
				, team_right.name as team_right_name
				, tt_fixture.date_fulfilled
			from tt_fixture
			left join tt_team as team_left on tt_fixture.team_left_id = team_left.id
			left join tt_team as team_right on tt_fixture.team_right_id = team_right.id
			where tt_fixture.team_left_id = ? and tt_fixture.team_right_id = ?
			group by tt_fixture.id
		");
		$sthEncounterPart = $this->database->dbh->prepare("
			insert into tt_encounter_part (
				player_id
				, player_score
				, player_rank_change
				, status
			) values (
				:player_id
				, :player_score
				, :player_rank_change
				, :status
			)
		");				
		$sthEncounter = $this->database->dbh->prepare("
			insert into tt_encounter (
				part_left_id
				, part_right_id
				, fixture_id
			) values (
				:part_left_id
				, :part_right_id
				, :fixture_id
			)
		");	
		$sthFixture->execute(array($_POST['team']['left'], $_POST['team']['right']));
		if ($fixture = $sthFixture->fetch(PDO::FETCH_ASSOC)) {
			if ($fixture['date_fulfilled']) {
				$this->session->set('feedback', 'This fixture has already been filled on ' . date('D jS F Y', $fixture['date_fulfilled']));
				return false;
			}
		} else {
			$this->session->set('feedback', 'This fixture does not exist');
			return false;
		}
		$ttPlayer = new Model_Ttplayer($this->database, $this->config);
		$players = $ttPlayer->readById(array_merge($_POST['player']['left'], $_POST['player']['right']));
		foreach ($this->getEncounterStructure() as $key => $playerPositions) {
			$playerLeft = false;
			$playerRight = false;
			$scoreLeft = false;
			$scoreRight = false;
			$doubles = false;
			$exclude = false;
			if ($key == 5) {
				$doubles = true;
			} else {
				if (array_key_exists($_POST['player']['left'][$playerPositions[0]], $players)) {
					$playerLeft = $players[$_POST['player']['left'][$playerPositions[0]]];
				}
				if (array_key_exists($_POST['player']['right'][$playerPositions[1]], $players)) {
					$playerRight = $players[$_POST['player']['right'][$playerPositions[1]]];
				}
				if (array_key_exists('exclude', $_POST['encounter'][$key]) || ! $playerLeft || ! $playerRight) {
					if (! $playerLeft) {
						$scoreLeft = 0;
						$scoreRight = 3;
					}
					if (! $playerRight) {
						$scoreLeft = 3;
						$scoreRight = 0;
					}
					$exclude = true;
				}
			}
			$scoreLeft = $_POST['encounter'][$key]['left'];
			$scoreRight = $_POST['encounter'][$key]['right'];
			if ($exclude) {
				$sthEncounterPart->execute(array(
					':player_id' => $playerLeft['id']
					, ':player_score' => $scoreLeft
					, ':player_rank_change' => '0'
					, ':status' => 'exclude'
				));
				$encounterPartId['left'] = $this->database->dbh->lastInsertId();
				$sthEncounterPart->execute(array(
					':player_id' => $playerRight['id']
					, ':player_score' => $scoreRight
					, ':player_rank_change' => '0'
					, ':status' => 'exclude'
				));		
				$encounterPartId['right'] = $this->database->dbh->lastInsertId();
			} elseif ($doubles) {
				$sthEncounterPart->execute(array(
					':player_id' => false
					, ':player_score' => $scoreLeft
					, ':player_rank_change' => false
					, ':status' => 'doubles'
				));		
				$encounterPartId['left'] = $this->database->dbh->lastInsertId();
				$sthEncounterPart->execute(array(
					':player_id' => false
					, ':player_score' => $scoreRight
					, ':player_rank_change' => false
					, ':status' => 'doubles'
				));						
				$encounterPartId['right'] = $this->database->dbh->lastInsertId();
			} else {
				if ($scoreLeft > 2) {
					$winner = true;
				} else {
					$winner = false;
				}
				$rankChange = $ttPlayer->getRankChange($playerLeft['rank'], $playerRight['rank'], $winner);
				$ttPlayer->updateRank($playerLeft['id'], $rankChange['left']);
				$ttPlayer->updateRank($playerLeft['id'], $rankChange['right']);
				$sthEncounterPart->execute(array(
					':player_id' => $playerLeft['id']
					, ':player_score' => $scoreLeft
					, ':player_rank_change' => $rankChange['left']
					, ':status' => false
				));		
				$encounterPartId['left'] = $this->database->dbh->lastInsertId();
				$sthEncounterPart->execute(array(
					':player_id' => $playerRight['id']
					, ':player_score' => $scoreRight
					, ':player_rank_change' => $rankChange['right']
					, ':status' => false
				));	
				$encounterPartId['right'] = $this->database->dbh->lastInsertId();
			}
			$sthEncounter->execute(array(
				':part_left_id' => $encounterPartId['left']
				, ':part_right_id' => $encounterPartId['right']
				, ':fixture_id' => $fixture['id']
			));
			$rowSummary[$key]['playerLeft'] = $playerLeft;
			$rowSummary[$key]['playerRight'] = $playerRight;
			$rowSummary[$key]['scoreLeft'] = $scoreLeft;
			$rowSummary[$key]['scoreRight'] = $scoreRight;
			$rowSummary[$key]['doubles'] = $doubles;
			$rowSummary[$key]['exclude'] = $exclude;
		}
		$sthFixture = $this->database->dbh->prepare("
			update tt_fixture
			set date_fulfilled = ?
			where id = {$fixture['id']}
		");
		$sthFixture->execute(array(time()));	
		$this->session->set('fixture_overview', $rowSummary);
		$this->session->set('feedback', 'Fixture Fulfilled. <a href="' . $this->config->getUrl('back') . '">Back to list</a>');
		return true;
	}	

	
	public function resetDateFulfilled($id) {
		$sth = $this->database->dbh->prepare("
			update tt_fixture
			set date_fulfilled = ?
			where id = {$id}
		");
		$sth->execute(array(null));
	}


	public function readSingleResult($fixtureId) {
		$sth = $this->database->dbh->prepare("	
			select
				concat(player_left.first_name, ' ', player_left.last_name) as player_left_full_name
				, concat(player_right.first_name, ' ', player_right.last_name) as player_right_full_name
				, tt_encounter_result.left_id as player_left_id
				, tt_encounter_result.right_id as player_right_id
				, tt_encounter_result.left_rank_change
				, tt_encounter_result.right_rank_change
				, tt_encounter_result.left_score as player_left_score
				, tt_encounter_result.right_score as player_right_score
				, team_left.id as team_left_id
				, team_right.id as team_right_id
				, team_left.name as team_left_name
				, team_right.name as team_right_name
				, tt_fixture_result.left_score as team_left_score
				, tt_fixture_result.right_score as team_right_score
				, tt_encounter_result.status
				, tt_fixture.date_fulfilled		
			from tt_encounter_result
			left join tt_player as player_left on player_left.id = tt_encounter_result.left_id
			left join tt_player as player_right on player_right.id = tt_encounter_result.right_id
			left join tt_fixture on tt_fixture.id = tt_encounter_result.fixture_id
			left join tt_fixture_result on tt_fixture_result.fixture_id = tt_encounter_result.fixture_id
			left join tt_team as team_left on team_left.id = tt_fixture.team_left_id
			left join tt_team as team_right on team_right.id = tt_fixture.team_right_id
			where tt_encounter_result.fixture_id = :fixtureId
			group by tt_encounter_result.encounter_id
			order by tt_encounter_result.encounter_id
		");
		$sth->execute(array(':fixtureId' => $fixtureId));
		if (!$sth->rowCount()) return false;
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$row['player_left_guid'] = $this->getGuid('player', $row['player_left_full_name'], $row['player_left_id']);
			$row['player_right_guid'] = $this->getGuid('player', $row['player_right_full_name'], $row['player_right_id']);
			$row['team_left_guid'] = $this->getGuid('team', $row['team_left_name'], $row['team_left_id']);
			$row['team_right_guid'] = $this->getGuid('team', $row['team_right_name'], $row['team_right_id']);
			$this->data[] = $row;
		}
		return true;
	}


	public function readResult($divisionId) {
		$sth = $this->database->dbh->prepare("	
			select
				tt_fixture_result.fixture_id as id
				, team_left.id as team_left_id
				, team_left.name as team_left_name
				, tt_fixture_result.left_score
				, team_right.name as team_right_name
				, team_right.id as team_right_id
				, tt_fixture_result.right_score
				, tt_fixture.date_fulfilled
			from tt_fixture_result
			left join tt_team as team_left on team_left.id = tt_fixture_result.left_id
			left join tt_team as team_right on team_right.id = tt_fixture_result.right_id
			left join tt_division on team_left.division_id = tt_division.id
			left join tt_fixture on tt_fixture.id = tt_fixture_result.fixture_id
			where team_left.division_id = ?
			group by tt_fixture_result.fixture_id
			order by tt_fixture.date_fulfilled desc
		");
		$sth->execute(array($divisionId));
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$row['guid'] = $this->getGuid('fixture', $row['team_left_name'] . '-' . $row['team_right_name'], $row['id']);
			$this->data[] = $row;
		}
		return $this;
	}


	public function readByTeam($id) {
		$sth = $this->database->dbh->prepare("
			select
				tt_fixture.id
				, tt_fixture.date_fulfilled
				, team_left.name as team_left_name
				, team_right.name as team_right_name
				, tt_fixture_result.left_score as team_left_score
				, tt_fixture_result.right_score as team_right_score
			from tt_fixture
			left join tt_fixture_result on tt_fixture.id = tt_fixture_result.fixture_id
			left join tt_team as team_left on team_left.id = tt_fixture.team_left_id
			left join tt_team as team_right on team_right.id = tt_fixture.team_right_id
			where team_left.id = ? or team_right.id = ?
			group by tt_fixture.id
			order by tt_fixture.date_fulfilled desc
		");				
		$sth->bindParam(1, $id, PDO::PARAM_INT);
		$sth->bindParam(2, $id, PDO::PARAM_INT);
		$sth->execute();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$row['guid'] = $this->getGuid('fixture', $row['team_left_name'] . '-' . $row['team_right_name'], $row['id']);
			$rows[] = $row;
		}
		if ($sth->rowCount()) {
			return $this->data = $rows;
		} 
		return false;
	}

	
}
