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

class Controller_Front_Division extends Controller
{


	public function initialise() {
		$division = new Model_Ttdivision($this->database, $this->config);
		if (! $division->readByName($this->config->getUrl(1)) || ! $this->config->getUrl(1)) {
			$this->route('base');
		}
		$this->view
			->setObject('division', $division->getData());
	}


	public function merit() {
		$division = $this->view->getObject('division');
		$player = new Model_Ttplayer($this->database, $this->config);
		$player->readMerit($division['id']);
		$this->view
			->setMeta(array(		
				'title' => $division['name'] . ' division merit table'
			))
			->setObject($player)
			->loadTemplate('merit');
	}


	public function league() {
		$division = $this->view->getObject('division');
		$team = new Model_Ttteam($this->database, $this->config);
		$team->readLeague($division['id']);
		$this->view
			->setMeta(array(		
				'title' => $division['name'] . ' division league table'
			))
			->setObject($team)
			->loadTemplate('league');
	}


	public function fixture() {
		$division = $this->view->getObject('division');
		$fixture = new Model_Ttfixture($this->database, $this->config);
		$fixture->readResult($division['id']);
		$this->view
			->setMeta(array(		
				'title' => $division['name'] . ' division fixtures'
			))
			->setObject($fixture)
			->loadTemplate('fixture');
	}


	/**
	 * divisional overview, skips to merit, league if required
	 * @todo improve the way this moves to the next url segment
	 */
	public function index() {
		if ($segment = $this->config->getUrl(2)) {
			if (method_exists($this, $segment)) {
				$this->$segment();
			}
		}
		$division = $this->view->getObject('division');
		$player = new Model_Ttplayer($this->database, $this->config);
		$team = new Model_Ttteam($this->database, $this->config);
		$fixture = new Model_Ttfixture($this->database, $this->config);
		$player->readTotalByDivision($division['id']);
		$team->readTotalByDivision($division['id']);
		$fixture->readTotalByDivision($division['id']);
		$this->view
			->setObject('total_players', $player->getData())
			->setObject('total_teams', $team->getData())
			->setObject('total_fixtures', $fixture->getData());
		$player = new Model_Ttplayer($this->database, $this->config);
		$team = new Model_Ttteam($this->database, $this->config);
		$fixture = new Model_Ttfixture($this->database, $this->config);
		if ($player->readMerit($division['id'])) {
			$this->view
				->setObject('player', array_slice($player->getData(), 0, 3));
		}
		if ($team->readLeague($division['id'])) {
			$this->view
				->setObject('team', array_slice($team->getData(), 0, 3));
		}
		$this->view
			->setMeta(array(		
				'title' => $division['name'] . ' division overview'
			))
			->loadTemplate('division');
	}

	
}
