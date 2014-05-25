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

class Controller_Tag extends Controller_Index
{


	public function run() {

		// set objects
		$modelTag = new model_tag($this);
		$modelContentMeta = new model_content_meta($this);
		$modelContent = new model_content($this);

		// get tag data
		$modelTagName = str_replace('-', ' ', $this->url->getPathPart(1));
		$molds = $modelTag->read(array(
			'where' => array('title' => $modelTagName)
		));
		if (! $molds) {
			$this->route('base');
		}
		$mold = $modelTag->getDataFirst();
		$this->view->setObject('tagCurrent', $mold);

		// gets content meta using the id of the tag found
		// get content data
		$molds = $modelContentMeta->read(array(
			'where' => array(
				'name' => 'tag',
				'value' => $mold->id,
			)
		));
		if (! $molds) {
			$this->route('base');
		}
		$contentIds = $modelContentMeta->getDataProperty('content_id');

		// get all content
		$molds = $modelContent->read(array(
			'where' => array(
				'id' => $contentIds,

				// @todo integrate other types
				'type' => 'post',
				'status' => 'visible'
			)
		));

		// set pagination
		$pagination = new pagination($this);
		$pagination->setTotalRows(count($molds));
		$pagination->initialise();

		// set only a slice
		$modelContent->setData(array_slice($modelContent->getData(), $pagination->getLimit(0), $pagination->getLimit(1)));
		$modelContent->bindMeta('media');
		$modelContent->bindMeta('tag');

		// view
		$this->view
			->setMeta(array(		
				'title' => 'All posts by tag name ' . $this->url->getPathPart(1)
			))
			->setObject('totalContents', count($molds))
			->setObject('pageCurrent', $pagination->getCurrentPage())
			->setObject('pagination_summary', $pagination->getSummary())
			->setObject('pagination', $pagination)
			->setObject('contents', $modelContent)
			->setObject('firstContent', $modelContent->getDataFirst())
			->getTemplate('content-tag');
	}
}
