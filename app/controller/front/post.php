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
		$content = new model_content($this->database, $this->config);
		if ($this->config->getUrl(1)) {
			if (! $content->read('post', false, $this->getId($this->config->getUrl(1)))) {
				$this->route('404');
			}
			$this->view
				->setMeta(array(		
					'title' => $content->get('title')
				))
				->setObject($content)
				->loadTemplate('content-single');
		}
		$pagination = new pagination($this->database, $this->config, 'content');
		$content->read('post', $pagination->getLimit());
		$firstContent = $content->getData();
		$this->view
			->setMeta(array(		
				'title' => 'All posts'
			))
			->setObject('first_content', current($firstContent))
			->setObject($pagination)
			->setObject($content)
			->loadTemplate('content');
	}
}
