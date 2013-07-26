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
class Model_Mainuser_Action extends Model
{


	public function read() {
		$sth = $this->database->dbh->prepare("
			select
				main_user_action.id
				, main_user_action.description
				, main_user_action.user_id
				, main_user_action.time
				, main_user_action.action
			from main_user_action
			left join main_user on main_user_action.user_id = main_user.id
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


	public function readById($ids) {
		$sth = $this->database->dbh->prepare("
			select
				main_user_action.id
				, main_user_action.description
				, main_user_action.user_id
				, main_user_action.time
				, main_user_action.action
			from main_user_action
			left join main_user on main_user_action.user_id = main_user.id
			where main_user_action.user_id = ?
		");				
		foreach ($ids as $id) {
			$sth->execute(array($id));
		}
		$this->data = $sth->fetch(PDO::FETCH_ASSOC);
		return $sth->rowCount();
	}


	public function create($userId, $action, $description) {
		$sth = $this->database->dbh->prepare("
			insert into main_user_action (
				description
				, user_id
				, action
			) values (
				?
				, ?
				, ?
			)
		");				
		$sth->execute(array(
			$description
			, $userId
			, $action
		));
		if ($sth->rowCount()) {
			return true;
		} 
		return false;
	}


}
