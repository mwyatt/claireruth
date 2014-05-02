<?php

/**
 * functions here can be repeated for ease of use in various areas, ajax
 * normal control etc
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class View_Header extends View
{


	public function getMainMenu()
	{
		$menu = array(
			0 => array(
				'name' => 'Home',
				'url' => ''
			),
			1 => array(
				'name' => 'About Me',
				'url' => 'page/about-me/'
			),
			2 => array(
				'name' => 'Posts',
				'url' => 'post/'
			),
			3 => array(
				'name' => 'Contact Me',
				'url' => 'page/contact/'
			)
		);
		return $menu;
	}
} 
