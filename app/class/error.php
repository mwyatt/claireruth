<?php

/**
 * Error handling
 *
 * PHP version 5
 *
 * @todo build a log file if required and add lines of errors
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Error
{

	
    private $debug;
	
	
    public function __construct($debug = 'no') {
        $this->debug = $debug;
        if ($this->debug = 'yes') {
	        error_reporting(E_ALL);
	        ini_set('display_errors', 1);
        }
		set_error_handler(array($this, 'handle'));
    }
	
	
    public function handle($errorType, $errorString, $errorFile, $errorLine) {
		switch ($this->debug) {
			case 'no':
				file_put_contents(BASE_PATH . 'error.txt', file_get_contents(BASE_PATH . 'error.txt') . '[Type ' . $errorType . '] ' . $errorString . ' | ' . $errorFile . ' [Line ' . $errorLine . ']' . "\n");
				echo 'A error has occurred. We all make mistakes. Please notify the administrator <a href="mailto:martin.wyatt@gmail.com">martin.wyatt@gmail.com</a>';
				exit;		
			case 'yes':
				// echo '[Type ' . $errorType . '] ' . $errorString . ' | ' . $errorFile . ' [Line ' . $errorLine . ']' . "\n";
				// seems to provide an alternative error message when this is disabled?
		}	
    }
}
