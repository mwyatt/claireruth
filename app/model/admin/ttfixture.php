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
class Model_Admin_Ttfixture extends Model_Ttfixture
{


	public function readById($id) {
		$sth = $this->database->dbh->prepare("	
			select
				tt_fixture.id		
				, tt_division.id as division_id		
				, tt_division.name as division_name		
				, concat(player_left.first_name, ' ', player_left.last_name) as player_left_full_name
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
			left join tt_division on tt_division.id = team_left.division_id
			where tt_encounter_result.fixture_id = ?
			group by tt_encounter_result.encounter_id
			order by tt_encounter_result.encounter_id
		");
		$sth->execute(array($id));
		if (! $sth->rowCount()) {
			return false;
		}
		return $this->data = $sth->fetchAll(PDO::FETCH_ASSOC);
	}


	public function update($id) {
		$ttPlayer = new Model_Ttplayer($this->database, $this->config);
		$ttEncounterResult = new Model_Ttencounter_Result($this->database, $this->config);
		$ttEncounterPart = new Model_Ttencounter_Part($this->database, $this->config);
		$ttEncounter = new Model_Ttencounter($this->database, $this->config);
		if ($results = $ttEncounterResult->readByFixtureId($id)) {
			foreach ($results as $row => $result) {
				$partIds[] = $result['tt_encounter_part_left_id'];
				$partIds[] = $result['tt_encounter_part_right_id'];
				$rankChanges[$result['left_id']][] = $result['left_rank_change'];
				$rankChanges[$result['right_id']][] = $result['right_rank_change'];
			}
			foreach ($rankChanges as $playerId => $changes) {
				if ($change = array_sum($changes)) {
					if ($change < 0) {
						$change = abs($change);
					} else {
						$change = -$change;
					}
					$rankChanges[$playerId] = $change;
					$ttPlayer->updateRank($playerId, $change);
				} else {
					unset($rankChanges[$playerId]);
				}
			}
			$ttEncounterPart->delete($partIds);
			$ttEncounter->deleteByFixtureId($id);
			$this->resetDateFulfilled($id);
			return true;
		} else {
			return false;
		}
	}
	

	/**
	 * generates each fixture seperated by division
	 * teams must not change division beyond this point
	 * @return null
	 */
	public function create() {
		
		$sth = $this->database->dbh->query("	
			SELECT
				tt_division.id as division_id
				, tt_team.id as team_id
			FROM
				tt_division				
			LEFT JOIN tt_team ON tt_division.id = tt_team.division_id
		");
		
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$this->data[$row['division_id']][] = $row['team_id'];
		}						
				
		$sth = $this->database->dbh->prepare("
			INSERT INTO
				tt_fixture
				(team_left_id, team_right_id)
			VALUES
				(:team_left_id, :team_right_id)
		");				
				
		// loop to set team vs team fixtures
		foreach ($this->data as $division) {
		
			foreach ($division as $key => $homeTeam) {
			
				foreach ($division as $key => $awayTeam) {
		
					if ($homeTeam !== $awayTeam) {
										
						$sth->execute(array(
							':team_left_id' => $homeTeam
							, ':team_right_id' => $awayTeam
						));					
					
					}
		
				}
			}
		
		}
	}


}
