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
		'content' => ''
	);

	
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

		// will be filled array if any missing keys
		if (array_diff_key($this->requiredSendProperties, $properties)) {
			return;
		}

		// make more usable as object
		$properties = $this->convertArrayToObject($properties);

		// core headers for mail
		$this->setHeaders($properties);

		// send it!
		if (mail($properties->to, $properties->subject, $properties->content, $this->headers)) {

			// create database entry
			$mold = new Mold_Mail();
			$mold->to = $properties->to;
			$mold->from = $this->addressFrom;
			$mold->subject = $properties->subject;
			$mold->content = $properties->content;
			$mold->time = time();
			$model = new Model_Mail($this->database, $this->config);
			$model->create(array($mold));
			return true;
		}
	}
}






// 	class Palette
// 	{


// 		/**
// 		 * storage of scss palette data
// 		 * @var string
// 		 */
// 		public $scss;
		

// 		/**
// 		 * grabs sass info and stores
// 		 * @param string $scssPath path to scss file
// 		 */
// 		public function __construct($scssPath)
// 		{
// 			$this->scss = file_get_contents(BASE_PATH . $scssPath);
// 		}


// 		/**
// 		 * extracts hex value of a color in the palette.scss file
// 		 * @param  string $colorTag 
// 		 * @return string           
// 		 */
// 		public function getHex($colorTag)
// 		{
// 			if (! $this->scss) {
// 				return false;
// 			}
// 			$colorTag = str_replace('_', '-', $colorTag);
// 			$colorVariableName = '$' . $colorTag . ': ';
// 			$color = substr(
// 				$this->scss
// 				, strpos($this->scss, $colorVariableName)
// 				, strlen($colorTag) + 10
// 			);
// 			$color = str_replace($colorVariableName, '', $color);
// 			return $color;
// 		}
// 	}
// }
// // the palette
// $palette = new palette('/sass/_palette.scss');

// // colors
// $variables = array(
// 	'color_primary'
// 	, 'color_secondary'
// 	, 'color_tertiary'
// 	, 'color_text'
// 	, 'color_background'
// );

// // assign vars
// foreach ($variables as $variable) {
// 	$sassVariable[$variable] = $palette->getHex($variable);
// }
