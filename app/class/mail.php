<?php

/**
 * Send Out Mail
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Mail extends View
{


	public $headers;


	public $toAddress;


	public $fromAddress = 'localhost@localhost.com';


	public $subject;


	public $template;

	
	public function getStyles() {
		$style['container'] = ''
			. 'padding: 10px;'
			. 'width: 100%;'
			. 'height: 100%;';
		$style['body'] = ''
			. 'margin: 0;'
			. 'padding: 0;'
			. 'background: #fff;'
			. 'color: #000;'
			. 'border: none;'
			. 'font-family: arial, sans-serif;';
		$style['a'] = ''
			. 'color: #ff5512;'
			. 'text-decoration: underline;';
		$style['h1'] = ''
			. 'font-size: 16px;'
			. 'margin: 0 0 16px;'
			. 'color: #333;';
		$style['p'] = ''
			. 'font-size: 16px;'
			. 'margin: 0 0 16px;'
			. 'color: #666;';
		return $style;
	}


	/**
	 * Sets Headers
	 * @returns true on send mail success false on failure
	 */	
	public function setHeaders()
	{
		// $headers = 'From: ' . $this->fromAddress;
		// $headers .= 'Reply-To: ' . $this->fromAddress . "\n";
		// $headers .= 'X-Mailer: PHP/' . phpversion() . "\n";
		// $headers .= 'MIME-Version: 1.0' . "\n";
		// $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		$headers = "From: " . $this->fromAddress;
		$headers .= "Reply-To: ". $this->fromAddress . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$this->headers = $headers;
		// echo '<pre>';
		// print_r($this->headers);
		// echo '</pre>';
		// exit;
		
		return $this;
	}

	
	public function getBasePath() {
		return BASE_PATH . 'app/view/mail/';
	}


	/**
	 * Sends Mail
	 * @returns true on send mail success false on failure
	 */	
	public function send($toAddress, $subject, $templateTitle, $data = false)
	{
		$session = new session();
		$this->toAddress = $toAddress;
		$this->subject = $subject;
		$path = BASE_PATH . 'app/view/mail/' . strtolower($templateTitle) . '.php';
		if (file_exists($path)) {
			$data['style'] = $this->getStyles();
			// parse template html
			ob_start();	
			require_once($path);
			$this->template = ob_get_contents();
			ob_end_clean();
		}
		$this->setHeaders();
		if ($this->toAddress && $this->fromAddress && $this->subject && $this->template) {
			// test output as plaintext
			// header("Content-Type: text/plain");
			// echo $this->template;
			// exit;
			return mail(
				$this->toAddress
				, $this->subject
				, $this->template
				, $this->headers
			);
			// echo 'Mail Successfully Sent to '.$this->toAddress;
		} else {
			// echo 'Failed to Send Mail';
			return false;
		}
	}


}
