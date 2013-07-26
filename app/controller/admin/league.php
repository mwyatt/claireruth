<?php

/**
 * admin
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Controller_Admin_League extends Controller
{


	public function index() {
		$division = new Model_Ttdivision($this->database, $this->config);
		$division->read();
		$user = new Model_Mainuser($this->database, $this->config);
		$this->view->setObject($division);
		$this->view->loadTemplate('admin/league');
	}


	public function player() {
		$userAction = new model_mainuser_action($this->database, $this->config);
		$player = new Model_Ttplayer($this->database, $this->config);
		$division = new Model_Ttdivision($this->database, $this->config);
		if (array_key_exists('form_update', $_POST)) {
			$player->update($_GET['edit']);
			$userAction->create($this->session->get('user', 'id'), 'update', 'player ' . $_POST['first_name'] . ' ' . $_POST['last_name'] . $_POST['edit']);
			$this->route('current');
		}
		if (array_key_exists('form_create', $_POST)) {
			$id = $player->create();
			$userAction->create($this->session->get('user', 'id'), 'create', 'player ' . $_POST['first_name'] . ' ' . $_POST['last_name'] . $id);
			$this->route('base', 'admin/league/' . $this->config->getUrl(2) . '/?edit=' . $id);
			$this->route('current');
		}
		if (array_key_exists('edit', $_GET)) {
			if (! $player->readById(array($_GET['edit']))) {
				$this->route('current_noquery');
			}
			$division->read();
			$team = new Model_Ttteam($this->database, $this->config);
			$team->readByDivision($player->get('division_id'));
			$this->view	
				->setObject($division)
				->setObject($team)
				->setObject($player)
				->loadTemplate('admin/league/player-create-update');
		}
		if (array_key_exists('delete', $_GET)) {
			$player->deleteById($_GET['delete']);
			$userAction->create($this->session->get('user', 'id'), 'delete', 'player ' . $id);
			$this->route('current_noquery');
		}
		if ($this->config->getUrl(3) == 'new') {
			$division->read();
			$this->view->setObject($division);
			$this->view->loadTemplate('admin/league/player-create-update');
		}
		$player->read();
		$this->view
			->setObject($player)
			->loadTemplate('admin/league/player');
	}


	public function team() {
		$userAction = new model_mainuser_action($this->database, $this->config);
		$team = new Model_Ttteam($this->database, $this->config);
		$weekday = new Model_Ttteam($this->database, $this->config);
		$division = new Model_Ttdivision($this->database, $this->config);
		$venue = new Model_Ttvenue($this->database, $this->config);
		$player = new Model_Ttplayer($this->database, $this->config);
		$secretaries = new Model_Ttplayer($this->database, $this->config);
		if (array_key_exists('form_update', $_POST)) {
			$team->update($_GET['edit']);
			$userAction->create($this->session->get('user', 'id'), 'update', 'team ' . $_POST['name']);
			$this->route('current');
		}
		if (array_key_exists('form_create', $_POST)) {
			$id = $team->create();
			$userAction->create($this->session->get('user', 'id'), 'create', 'team ' . $_POST['name']);
			$this->route('base', 'admin/league/' . $this->config->getUrl(2) . '/?edit=' . $id);
		}
		if (array_key_exists('edit', $_GET)) {
			if ($team->readById(array($_GET['edit']))) {
				$division->read();
				$weekday->readWeekDays();
				$venue->read();
				$player->readByTeam($_GET['edit']);
				$secretaries->readSecretaries();
				$this->view	
					->setObject($team)
					->setObject($venue)
					->setObject($player)
					->setObject('secretaries', $secretaries->getData())
					->setObject('home_nights', $weekday)
					->setObject($division)
					->loadTemplate('admin/league/team-create-update');
			}
			$this->route('current_noquery');
		}
		if (array_key_exists('delete', $_GET)) {
			$team->delete($_GET['delete']);
			$userAction->create($this->session->get('user', 'id'), 'delete', 'team ' . $_GET['delete']);
			$this->route('current_noquery');
		}
		if ($this->config->getUrl(3) == 'new') {
			$division->read();
			$team->readWeekDays();
			$venue->read();
			$this->view
				->setObject($venue)
				->setObject('home_nights', $team)
				->setObject($division);
			$this->view->loadTemplate('admin/league/team-create-update');
		}
		$team->read();
		$this->view
			->setObject($team)
			->loadTemplate('admin/league/team');
	}


	public function fixture() {
		$userAction = new model_mainuser_action($this->database, $this->config);
		$encounterStructure = new Model_Ttfixture($this->database, $this->config);
		$adminFixture = new Model_Admin_Ttfixture($this->database, $this->config);
		$fixture = new Model_Ttfixture($this->database, $this->config);
		$division = new Model_Ttdivision($this->database, $this->config);
		$division->read();
		$encounterStructure->data = $encounterStructure->getEncounterStructure();
		if (array_key_exists('form_fulfill', $_POST)) {
			$fixture->fulfill();
			$userAction->create($this->session->get('user', 'id'), 'fulfill', 'fixture fulfilled ' . $_POST['team']['left'] . ' vs ' . $_POST['team']['right']);
			$this->route('current');
		}
		if (array_key_exists('form_update', $_POST)) {
			if ($adminFixture->update($_GET['edit'])) {
				$adminFixture->fulfill();
				$userAction->create($this->session->get('user', 'id'), 'update', 'fixture ' . $_GET['edit']);
			}
			$this->route('current');
		}
		if (array_key_exists('edit', $_GET)) {
			if (! $adminFixture->readById($_GET['edit'])) {
				$this->route('current_noquery');
			}
			$fixtureSingle = new Model_Blank($this->database, $this->config);
			$fixtureSingle->setData($adminFixture->data[0]);
			$this->view	
				->setObject('fixture', $fixtureSingle)
				->setObject($adminFixture)
				->setObject('encounter_structure', $encounterStructure)
				->setObject($division)
				->loadTemplate('admin/league/fixture-create-update');
		}
		if ($this->config->getUrl(3) == 'fulfill') {
			$this->view
				->setObject('encounter_structure', $encounterStructure)
				->setObject($division)
				->loadTemplate('admin/league/fixture-create-update');
		}
		$fixture->read();
		$this->view
			->setObject($division)
			->setObject($fixture)
			->loadTemplate('admin/league/fixture');
	}

	
}
	