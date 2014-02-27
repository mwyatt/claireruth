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

class Controller_Tag extends Controller
{


	public function index() {

		// get tag data
		$modelTag = new model_tag($this->database, $this->config);
		$modelContentMeta = new model_content_meta($this->database, $this->config);
		$modelTagName = str_replace('-', ' ', $this->config->getUrl(1));
		if (! $modelTag->getDataFirst($modelTagName)) {
			$this->view->getTemplate('404');
		}
		$this->view->setObject('single_tag', $modelTag);

		// gets content meta using the id of the tag found
		if (! $modelContentMeta->readByValue('tag', $modelTag->getData('id'))) {
			$this->view->getTemplate('404');
		}

		// get content data
		$content = new model_content($this->database, $this->config);
		if (! $content->read('post', false, $modelContentMeta->getData())) {
			$this->view->getTemplate('404');
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
			->setObject('content_first', $content->getDataFirst())
			->getTemplate('content-tag');
	}
}
