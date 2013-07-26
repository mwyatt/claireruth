<?php

/**
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Model_Ttfixture_Result extends Model
{


	/**
	 * @todo make this take raw data from ttfixture result and not
	 * join encounter retults
	 * @param  int  $id    
	 * @param  integer $limit 
	 * @return bool         
	 */
	public function readByPlayerId($id, $limit = 0) {
		$sth = $this->database->dbh->prepare("
			select
				tt_fixture.id
				, tt_fixture.date_fulfilled
				, team_left.name as team_left_name
				, team_right.name as team_right_name
				, tt_fixture_result.left_score as team_left_score
				, tt_fixture_result.right_score as team_right_score
			from tt_fixture_result
			left join tt_fixture on tt_fixture_result.fixture_id = tt_fixture.id
			left join tt_team as team_left on team_left.id = tt_fixture_result.left_id
			left join tt_team as team_right on team_right.id = tt_fixture_result.right_id
			left join tt_encounter_result on tt_fixture.id = tt_encounter_result.fixture_id
			where tt_encounter_result.left_id = ? or tt_encounter_result.right_id = ?
			group by tt_fixture.id
			" . ($limit ? ' limit ? ' : '') . "
		");				
		$sth->bindParam(1, $id, PDO::PARAM_INT);
		$sth->bindParam(2, $id, PDO::PARAM_INT);
		if ($limit) {
			$sth->bindValue(3, (int) $limit, PDO::PARAM_INT);
		}
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


	public function readByTeam($id) {
		$sth = $this->database->dbh->prepare("
			select
				tt_fixture.id
				, tt_fixture.date_fulfilled
				, team_left.name as team_left_name
				, team_right.name as team_right_name
				, tt_fixture_result.left_score as team_left_score
				, tt_fixture_result.right_score as team_right_score
			from tt_fixture_result
			left join tt_fixture on tt_fixture_result.fixture_id = tt_fixture.id
			left join tt_team as team_left on team_left.id = tt_fixture_result.left_id
			left join tt_team as team_right on team_right.id = tt_fixture_result.right_id
			where team_left.id = ? or team_right.id = ?
			group by tt_fixture.id
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
