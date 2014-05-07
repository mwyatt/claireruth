<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Mail extends Config
{


	/**
	 * mail headers stored here, processed at setHeaders
	 * @var string
	 */
	public $headers;


	/**
	 * full address of the sender, should this come from the database?
	 * @var string
	 */
	public $addressFrom = 'localhost@localhost.com';


	/**
	 * validates the incoming properties array when sending mail
	 * not really needed?
	 * @var array
	 */
	public $requiredSendProperties = array(
		'to' => '',
		'subject' => '',
		'template' => ''
	);


	/**
	 * @var object
	 */
	public $view;


	/**
	 * used to allow setting of the mail pallete to the parser
	 * @param object $database 
	 * @param object $config   
	 * @param object $view     
	 */
	public function __construct($database, $config, $view) {

		// system objects
		parent::__construct($database, $config);
		$this->view = $view;

		// pallete
		$mailPallete = new Mail_Pallete($this);
		$mailPallete->setSassStyles();
		$mailPallete->setStyles();
		$this->view->setObject('styles', $mailPallete);
	}


	
	/**
	 * builds header string for mail function
	 * @param object $properties 
	 */
	public function setHeaders($properties)
	{
		$headerSections = array(
			'From: ' . $this->addressFrom,
			'Reply-To: '. $this->addressFrom,
			'MIME-Version: 1.0',
			'Content-Type: text/html; charset=ISO-8859-1'
		);
		$this->headers = implode("\r\n", $headerSections);
	}


	/**
	 * configures headers and sends mail out
	 * @param  array  $properties see requiredSendProperties for rules
	 * @return bool
	 */
	public function send($properties = array())
	{

		// make more usable as object
		$properties = $this->convertArrayToObject($properties);

		// core headers for mail
		$this->setHeaders($properties);

		// build html
		$templateHtml = $this->view->getTemplate($properties->template);

		// debug
		if ($this->isDebug($this)) {
			echo '<pre>';
			print_r($templateHtml);
			echo '</pre>';
			exit;
		}

		// send it!
		if (mail($properties->to, $properties->subject, $templateHtml, $this->headers)) {

			// create database entry
			$mold = new Mold_Mail();
			$mold->addressed_to = $properties->to;
			$mold->addressed_from = $this->addressFrom;
			$mold->subject = $properties->subject;
			$mold->content = $templateHtml;
			$mold->time = time();
			$model = new Model_Mail($this);
			$model->create(array($mold));
			return true;
		}
	}
}
