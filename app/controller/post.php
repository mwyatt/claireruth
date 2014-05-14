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

class Controller_Post extends Controller
{


	public function index() {
		$modelContent = new model_content($this);

		// single
		if ($this->url->getPathPart(1)) {
			if (! $modelContent->read(array(
				'where' => array(
					'slug' => $this->url->getPathPart(1)
				)
			))) {
				$this->route('base');
			}
			$mold = $modelContent->getDataFirst();
			$modelContent->bindMeta('media');
			$modelContent->bindMeta('tag');
			$this->view
				->setMeta(array(		
					'title' => $mold->title
				))
				->setObject('contents', $modelContent)
				->renderTemplate('content-single');
		} else {

			// all
			$pagination = new pagination($this);
			$pagination->initialise('content');
			$modelContent->read(array(
				'where' => array(
					'type' => $this->url->getPathPart(0),
					'status' => 'visible'
				),
				'limit' => $pagination->getLimit(),
				'order_by' => 'time_published desc'
			));
			$modelContent->bindMeta('media');
			$modelContent->bindMeta('tag');
			$firstContent = $modelContent->getData();
			$this->view
				->setMeta(array(		
					'title' => 'All posts'
				))
				->setObject('pageCurrent', $pagination->getCurrentPage())
				->setObject('first_content', current($firstContent))
				->setObject($pagination)
				->setObject('contents', $modelContent)
				->renderTemplate('content');
		}
	}
}
