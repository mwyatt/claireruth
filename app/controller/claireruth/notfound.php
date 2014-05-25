<?php

/**
 * Controller
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
 
class Controller_Notfound extends Controller_Index
{


	public function run()
	{
		echo '<pre>';
		print_r('Controller_Notfound');
		echo '</pre>';
		exit;
		
	}
}
