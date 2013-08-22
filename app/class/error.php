<?php

/**
 * Error handling
 *
 * PHP version 5
 *
 * @todo build a log file if required and add lines of errors
 * @package	eastlancstt
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Error
{

	
    private $debug;
	
	
    public function __construct($debug = false) {
        $this->debug = $debug;
        if ($this->debug == 'yes') {
	        ini_set('display_errors', 1);
        }
        error_reporting(30711);
		set_error_handler(array($this, 'handle'));
    }
	
	
    public function handle($errorType, $errorString, $errorFile, $errorLine) {
		switch ($this->debug) {
			case false:
				file_put_contents(BASE_PATH . 'error.txt', file_get_contents(BASE_PATH . 'error.txt') . '[Type ' . $errorType . '] ' . $errorString . ' | ' . $errorFile . ' [Line ' . $errorLine . ']' . "\n");
				echo 'A error has occurred. We all make mistakes. Please notify the administrator <a href="mailto:martin.wyatt@gmail.com">martin.wyatt@gmail.com</a>';
				exit;		
			case true:
				echo '[Type ' . $errorType . '] ' . $errorString . ' | ' . $errorFile . ' [Line ' . $errorLine . ']' . "\n";
				// switch ($errorType) {
				// 	case 2:
				// 		print_r ($error);
				// 		break;						
				// 	case 8:
				// 		print_r ($error);
				// 		break;
				// 	case 256:
				// 		print_r ($error);
				// 		break;
				// 	case 512:
				// 		print_r ($error);
				// 		break;
				// 	case 1024:
				// 		print_r ($error);
				// 		break;
				// 	case 4096:
				// 		print_r ($error);
				// 		break;	
				// }
				// break;
		}	
    }
}
