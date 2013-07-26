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
class Model_Ttencounter_Result extends Model
{


	public function readByFixtureId($id) {
		$sth = $this->database->dbh->prepare("
			select
				tt_encounter_result.encounter_id
				, tt_encounter_result.tt_encounter_part_left_id
				, tt_encounter_result.tt_encounter_part_right_id
				, tt_encounter_result.left_id
				, tt_encounter_result.right_id
				, tt_encounter_result.left_score
				, tt_encounter_result.right_score
				, tt_encounter_result.left_rank_change
				, tt_encounter_result.right_rank_change
				, tt_encounter_result.fixture_id
				, tt_encounter_result.status
			from tt_encounter_result
			where fixture_id = ?
		");				
		$sth->execute(array($id));	
		if ($sth->rowCount()) {
			return $this->data = $sth->fetchAll(PDO::FETCH_ASSOC);
		} 
		return false;
	}


	public function readByPlayerId($id, $limit = 0) {
		$sth = $this->database->dbh->prepare("
			select
				tt_encounter_result.encounter_id
				, tt_encounter_result.tt_encounter_part_left_id
				, tt_encounter_result.tt_encounter_part_right_id
				, player_left.id as player_left_id
				, concat(player_left.first_name, ' ', player_left.last_name) as player_left_full_name
				, player_right.id as player_right_id
				, concat(player_right.first_name, ' ', player_right.last_name) as player_right_full_name
				, tt_encounter_result.left_score
				, tt_encounter_result.right_score
				, tt_encounter_result.left_rank_change
				, tt_encounter_result.right_rank_change
				, tt_encounter_result.fixture_id
			from tt_encounter_result
			left join tt_player as player_left on player_left.id = tt_encounter_result.left_id
			left join tt_player as player_right on player_right.id = tt_encounter_result.right_id
			where tt_encounter_result.left_id = ? and tt_encounter_result.status = '' or tt_encounter_result.right_id = ? and tt_encounter_result.status = ''
			group by tt_encounter_result.encounter_id
			order by tt_encounter_result.encounter_id desc
			" . ($limit ? ' limit ? ' : '') . "
		");				
		$sth->bindParam(1, $id, PDO::PARAM_INT);
		$sth->bindParam(2, $id, PDO::PARAM_INT);
		if ($limit) {
			$sth->bindValue(3, (int) $limit, PDO::PARAM_INT);
		}
		$sth->execute();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$row['player_left_guid'] = $this->getGuid('player', $row['player_left_full_name'], $row['player_left_id']);
			$row['player_right_guid'] = $this->getGuid('player', $row['player_right_full_name'], $row['player_right_id']);
			$rows[] = $row;
		}
		if ($sth->rowCount()) {
			return $this->data = $rows;
		} 
		return false;
	}

	
}
