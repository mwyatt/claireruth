<?php

/**
 * @package	~unknown~
 * @author 	Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Model_Search extends Model
{

	/**
	  *	@return true on matching word or false
	  */
	public static function title($data)
	{		

		$data['title'] = strtolower($data['title']);
		$_POST['search'] = strtolower($_POST['search']);

		$titleWords = explode(' ', $data['title']); // split via ' '
		$words = explode(' ', $_POST['search']); // split via ' '

		foreach ($titleWords as $titleWord) {
			foreach ($words as $searchWord) {
			
				// each word matches and !== null
				if (
					($titleWord)
					&& ($searchWord)
					&& (strpos($titleWord, $searchWord) !== false)
				) {
					return true;
				}
			
			}
		}
		return false;
	}	


	/**
	 * search tables using search string
	 * team
	 * player
	 * @param  string $query 
	 * @return array               
	 */
	public function read($query, $limit = 0) {
		if (! $query) {
			return false;
		}
		$matches = array();
		$query = htmlspecialchars($query);
		$words = explode(' ', $query);
		$sth = $this->database->dbh->prepare("	
			select
				tt_player.id
				, concat(tt_player.first_name, ' ', tt_player.last_name) as name
			from tt_player
			where
				tt_player.first_name like ?
				or tt_player.last_name like ?
		");
		foreach ($words as $word) {
			$sth->execute(array(
				'%' . $word . '%'
				, '%' . $word . '%'
			));
			while ($match = $sth->fetch(PDO::FETCH_ASSOC)) {
				$matches['player_' . $match['id']] = $match;
				$matches['player_' . $match['id']]['guid'] = $this->getGuid('player', $match['name'], $match['id']);
				$matches['player_' . $match['id']]['type'] = 'player';
			}
		}
		$sth = $this->database->dbh->prepare("	
			select
				tt_team.id
				, tt_team.name
			from tt_team
			where tt_team.name like ?
		");
		foreach ($words as $word) {
			$sth->execute(array(
				'%' . $word . '%'
			));
			while ($match = $sth->fetch(PDO::FETCH_ASSOC)) {
				$matches['team_' . $match['id']] = $match;
				$matches['team_' . $match['id']]['guid'] = $this->getGuid('team', $match['name'], $match['id']);
				$matches['team_' . $match['id']]['type'] = 'team';
			}
		}
		if ($limit) {	
			$matches = array_slice($matches, 0, $limit);
		}
		$this->setData($matches);
		return true;
	}

	
}