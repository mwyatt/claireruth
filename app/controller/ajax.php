<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Controller_Ajax extends Controller
{

	
	public function index() {
		$this->route('base');
	}


	/**
	 * use the content id to build the lurrrvee button
	 * @param  integer $contentId 
	 */
	public function love($contentId = 0, $plusOne = false)
	{

		// validate required get
		if (array_key_exists('content_id', $_GET)) {
			$contentId = $_GET['content_id'];
		} else {
			return;
		}
		if (array_key_exists('plus_one', $_GET)) {
			$sessionLove = new session_love($this->database, $this->config);

			// return if session has a record of this being loved
			if (! $sessionLove->add($contentId)) {
				return;
			}
			$plusOne = true;
		}

		// setup object
		$contentMeta = new model_content_meta($this->database, $this->config, 'love');

		// create meta entry if doesnt exist
		// default 1
		if (! $contentMeta->readByContentId($contentId)) {
			$contentMeta->create(array(
				'content_id' => $contentId
				, 'name' => 'love'
				, 'value' => ($plusOne ? 1 : 0)
			));
		}

		// increment counter
		if ($plusOne && $contentMetaData = $contentMeta->getDataFirst()) {
			$loveCount = $contentMetaData['value'] ++;
			
			// ++
			$contentMeta->update(
				array('status' => (array_key_exists('status', $_POST) ? $_POST['status'] : 'hidden'))
				, array('content_id' => $contentId)
			);
		}

		// reads meta entry, will always be there and up to date
		$contentMeta->readByContentId($contentId);
echo '<pre>';
print_r($contentMeta->getDataFirst());
echo '</pre>';
exit;

		// view
		$this->view
			->setObject('contentMeta', $contentMeta->getDataFirst())
			->loadTemplate('ajax');
	}
}
