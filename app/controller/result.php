<?php

/**
 * player
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Controller_Result extends Controller
{


	public function index($action) {
		$ttDivision = new Model_Ttdivision($this->database, $this->config);
		$ttDivision->read();
		if ($action) {
			foreach ($ttDivision->getData() as $division) {
				if ($action == strtolower($division['name'])) {
					$ttPlayer = new Model_Ttplayer($this->database, $this->config);
					$ttTeam = new Model_Ttteam($this->database, $this->config);
					$ttFixture = new Model_Ttfixture($this->database, $this->config);
					$ttEncounterPart = new Model_Ttencounterpart($this->database, $this->config);
					$ttDivision->setData($division);
					$this->view->setObject($ttDivision);
					if ($this->config->getUrl(2)) {
						if ($this->config->getUrl(2) == 'merit') {
							$ttPlayer->readMerit($ttDivision->get('id'));
							$this->view
								->setObject($ttPlayer)
								->loadTemplate('merit');
						}
						if ($this->config->getUrl(2) == 'league') {
							$ttTeam->readLeague($ttDivision->get('id'));
							$this->view
								->setObject($ttTeam)
								->loadTemplate('league');
						}
						if ($this->config->getUrl(2) == 'fixture') {
							$ttFixture->readResult($ttDivision->get('id'));
							$this->view
								->setObject($ttFixture)
								->loadTemplate('fixture');
						}
					}
					$ttPlayer->read();
					$this->view
						->setObject($ttPlayer)
						->loadTemplate('division');
				}
			}
		}
		$this->config->getObject('route')->home();
	}

	
}
