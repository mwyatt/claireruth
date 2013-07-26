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

class Controller_Front_Minutes extends Controller
{


	public function index() {
		$minuteCollection = array();
		$minutes = new Model_Maincontent($this->database, $this->config);
		$media = new model_mainmedia($this->database, $this->config);
		$minutes->readByType('minutes');
		foreach ($minutes->getData() as $minute) {
			if (! array_key_exists('media', $minute)) {
				continue;
			}
			$media->readById(array($minute['media']));
			$minute['media'] = $media->getData();
			$minuteCollection[] = $minute;
		}
		$minutes->setData($minuteCollection);
		$information = current($minutes->getData());
		$this->view
			->setMeta(array(		
				'title' => 'Minutes'
			))
			->setObject('information', $information)
			->setObject($minutes)
			->loadTemplate('minutes');
	}

	
}
