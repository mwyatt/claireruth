<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Mold_Content extends Mold
{


	public $title;


	public $slug;

	
	public $html;

	
	public $type;

	
	public $time_published;

	
	public $status;
	

	public $user_id;


	/**
	 * @var object
	 */
	public $user;


	public $tag = array();


	public $media = array();
}
