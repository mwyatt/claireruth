<?php

/**
 * collection of allowed ajax requests
 * @todo expand so that admin are seperate, modular not methods
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Controller_Ajax extends Controller_Index
{


	/**
	 * ensure passed keys are set
	 * e.g. $this->validateRequiredKeys($_GET, array('first_name'));
	 * @param  array $globalVariable $_GET, $_POST
	 * @param  array $requiredKeys   
	 * @return bool                 
	 */
	public function validateKeys($globalVariable, $requiredKeys)
	{

		// any not found, return
		foreach ($requiredKeys as $requiredKey) {
			if (! array_key_exists($requiredKey, $globalVariable)) {
				return;
			}
		}

		// all keys must be present
		return true;
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

		// session validation
		if (array_key_exists('plus_one', $_GET)) {
			$sessionLove = new session_love($this);

			// return if session has a record of this being loved
			if (! $sessionLove->add($contentId)) {
				return;
			}
			$plusOne = true;
		}

		// setup object
		$contentMeta = new model_content_meta($this);
		$contentMetaSelect = "
			content_meta.id
			, content_meta.content_id
			, content_meta.name
			, content_meta.value
		";
		$contentMetaWhere = array(
			'content_id' => $contentId
			, 'name' => 'love'
		);

		// create meta entry if doesnt exist
		// default 1
		if (! $contentMeta->read($contentMetaSelect, $contentMetaWhere)) {
			$contentMeta->create(array(
				'content_id' => $contentId
				, 'name' => 'love'
				, 'value' => ($plusOne ? 1 : 0)
			));
		}

		// increment counter
		if ($plusOne) {
			$loveCount = $contentMeta->getDataFirst('value') + 1;
			
			// ++
			$contentMeta->update(
				array('value' => $loveCount)
				, $contentMetaWhere
			);
		}

		// reads meta entry, will always be there and up to date
		$contentMeta->read($contentMetaSelect, $contentMetaWhere);

		// view
		$this->view
			->setObject($contentMeta)
			->getTemplate('_love');
	}


	/**
	 * clears the currently set feedback in the session
	 */
	public function dismiss()
	{
		$sessionFeedback = new session_feedback($this);
		exit($sessionFeedback->delete());
	}
}
