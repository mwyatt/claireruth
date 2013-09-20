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

class Controller_Front_Post extends Controller
{


	public function index() {
		$content = new Model_Maincontent($this->database, $this->config);
		if ($this->config->getUrl(1)) {
			if (! $content->read('post', false, $this->getId($this->config->getUrl(1)))) {
				$this->route('404');
			}
			$this->view
				->setMeta(array(		
					'title' => $content->get('title')
				))
				->setObject('row_content', $content->getData())
				->loadTemplate('content-single');
		}
		$content->read('post');
		$this->view
			->setMeta(array(		
				'title' => 'All posts'
			))
			->setObject('row_contents', $content->getData())
			->loadTemplate('content');
	}

	
}
