<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Controller_Ajax_Content extends Controller_Ajax
{


	/**
	 * generates the slug of all content items, this will be done
	 * automatically when creating one in the admin
	 */
	public function generateSlug()
	{
		$modelContent = new model_content($this);
		$modelContent->read();
		foreach ($modelContent->getData() as $mold) {
			if ($mold->slug) {
				continue;
			}
			$mold->slug = $this->urlFriendly($mold->title);
			if ($modelContent->update($mold, array('where' => array('id' => $mold->id)))) {
				echo 'updated slug of content ' . $mold->title . ', slug name = ' . $mold->slug . '<hr>';
			}
		}
	}
}
