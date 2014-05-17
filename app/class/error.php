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
class Error extends System
{

	
    private $reporting;
	

    public function setReporting($value)
    {
    	$this->reporting = $value;
    }


    public function getReporting()
    {
    	return $this->reporting;
    }


    public function initialise()
    {
        $this->setReporting($this->url->isLocal());
        if ($this->getReporting()) {
	        ini_set('display_errors', 1);
        }
        error_reporting(30711);
		set_error_handler(array($this, 'handle'));
    }


    public function handle($errorType, $errorString, $errorFile, $errorLine) {  	
		switch ($this->reporting) {
			case false:

				// put error info and echo friendly schpiel
				file_put_contents(BASE_PATH . 'error.txt', file_get_contents(BASE_PATH . 'error.txt') . '[Type ' . $errorType . '] ' . $errorString . ' | ' . $errorFile . ' [Line ' . $errorLine . '] [Date ' . date('d/m/Y', time()) . ']' . "\n");
				echo 'A error has occurred. We all make mistakes. Please notify the administrator <a href="mailto:martin.wyatt@gmail.com">martin.wyatt@gmail.com</a>';
			case true:

				// display error(s)
				echo '[Type ' . $errorType . '] ' . $errorString . ' | ' . $errorFile . ' [Line ' . $errorLine . ']' . "\n";

				// trying this out
				echo '<pre>';
				print_r(debug_print_backtrace());
				echo '</pre>';
		}	
		exit;
    }
}
