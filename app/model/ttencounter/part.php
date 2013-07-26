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
class Model_Ttencounter_Part extends Model
{		

	public function delete($ids) {
		$sth = $this->database->dbh->prepare("
			delete from tt_encounter_part
			where id = ?
		");				
		foreach ($ids as $id) {
			$sth->execute(array($id));	
		}
		return $sth->rowCount();
	}


	public function readRankChange($playerId = false)
	{	
		$sth = $this->database->dbh->prepare("
			select id, sum(tt_encounter_part.player_rank_change) as player_rank_change
			from tt_encounter_part
			where tt_encounter_part.status = '' and tt_encounter_part.player_id = ?	
		");
		$sth->execute(array($playerId));	
		$this->data = $sth->fetch(PDO::FETCH_ASSOC);
	}	


	public function read($playerId = false, $limit = false)
	{	

		$sql = "select tt_encounter_part.player_rank_change from tt_encounter_part";

		if ($playerId) {
			$sql .= " where tt_encounter_part.player_id = :playerId ";
			$execution[':playerId'] = $playerId;
		}

		if ($limit) {
			$sql .= " limit $limit ";
		}

		$sth = $this->database->dbh->prepare($sql);
		$sth->execute($execution);	

		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$this->data[] = $row;
		}

	}	


	/**
	 * pulls out a full list of the entire player performance list, ordered by
	 * the player ranking change. the player with the most ranking points
	 * is the one who has beaten the most players with the most points
	 */
	public function readPerformance()
	{	
		$sth = $this->database->dbh->query("
			select
				tt_player.id as player_id
				, sum(tt_encounter_part.player_rank_change) as player_rank_change
				, concat(tt_player.first_name, ' ', tt_player.last_name) as player_name
				, tt_team.id as team_id
				, tt_team.name as team_name
			from tt_encounter_part
			left join tt_player on tt_player.id = tt_encounter_part.player_id
			left join tt_team on tt_team.id = tt_player.team_id
			where tt_encounter_part.status = ''
			group by tt_player.id
			order by player_rank_change desc
		");
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {	
			$row['player_guid'] = $this->getGuid('player', $row['player_name'], $row['player_id']);
			$row['team_guid'] = $this->getGuid('team', $row['team_name'], $row['team_id']);
			$this->data[] = $row;
		}
		
	}	


}