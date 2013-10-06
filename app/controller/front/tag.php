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
		if (! $tag->readSingle($this->config->getUrl(1))) {
			$this->view->loadTemplate('404');
		}
		$content = new model_content($this->database, $this->config);
		if (! $content->read('post', false, $tag->getData())) {
			$this->view->loadTemplate('404');
		}

		// build friendly tag name
		$tagName = explode('-', $this->config->getUrl(1));
		$tagName = implode(' ', $tagName);
		$tagName = ucwords($tagName);

		// view
		$this->view
			->setMeta(array(		
				'title' => 'All posts by tag name ' . $this->config->getUrl(1)
			))
			->setObject('tag_name', $tagName)
			->setObject($content)
			->loadTemplate('content-tag');
	}
}
