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

class Controller_Front_Month extends Controller
{


	public function index() {
		$content = new model_content($this->database, $this->config);
		
		// is the date acceptable
		if (! $content->readByMonth(array($this->config->getUrl(1)))) {
			$this->route('404');
		}

		// make month pretty
		$monthYear = explode('-', $this->config->getUrl(1));
		$monthYear = ucfirst(current($monthYear)) . ' ' . next($monthYear);

		// view
		$this->view
			->setMeta(array(		
				'title' => 'All posts from ' . $monthYear
			))
			->setObject('month_year', $monthYear)
			->setObject($content)
			->loadTemplate('month');
	}
}
