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

class Controller_Front_Tag extends Controller
{


	public function index() {
		$tag = new model_content_tag($this->database, $this->config);
		if (! $tag->readContent($this->config->getUrl(1))) {
			$this->route('404');
		}
		$content = new model_content($this->database, $this->config);
		if (! $content->read('post', false, $tag->getData())) {
			$this->route('404');
		}
		$this->view
			->setMeta(array(		
				'title' => 'All posts by tag name ' . $this->config->getUrl(1)
			))
			->setObject($content)
			->loadTemplate('content-tag');
	}
}
