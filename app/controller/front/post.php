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
		$tag = new model_content_tag($this->database, $this->config);

		// build general month and tag widgets
		$content->readByMonth();
		echo '<pre>';
		print_r($content);
		echo '</pre>';
		exit;
		
		$tag->read();
		$this->view
			->setObject($tag)
			->setObject('month', $content);

		// reset
		$content = new model_content($this->database, $this->config);

		// single post
		if ($this->config->getUrl(1)) {
			if (! $content->readSingle($this->config->getUrl(0), $this->getId($this->config->getUrl(1)))) {
				$this->route('404');
			}
			$this->view
				->setMeta(array(		
					'title' => $content->get('title')
				))
				->setObject($content)
				->loadTemplate('content-single');
		}

		// all posts
		$pagination = new pagination($this->database, $this->config, 'content');
		$content->read($this->config->getUrl(0), $pagination->getLimit());
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
