<?php

/**
 * Teleporting Data since 07.10.12
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class View extends Model
{


	/**
	 * the name of the template loaded
	 * @var string
	 */
	public $template;


	/**
	 * session information
	 * @var object
	 */
	public $session;


	/**
	 * page meta information
	 * @var array
	 */
	public $meta = array();
	
	
	/**
	 * prepare all core objects here and register
	 */	
	public function header() {
		$this->session = new Session($this->database, $this->config);
		$options = $this->config->getoptions();
		$this->setMeta(array(
			'title' => (array_key_exists('meta_title', $options) ? $options['meta_title'] : ''),
			'keywords' => (array_key_exists('meta_keywords', $options) ? $options['meta_keywords'] : ''),
			'description' => (array_key_exists('meta_description', $options) ? $options['meta_description'] : '')
		));
		$this->setObject('options', $options);
	}

	
	/**
	 * load template file and prepare all objects for output
	 * @param  string $templateTitle 
	 * @return bool                
	 */
	public function loadTemplate($templateTitle) {			
		$path = BASE_PATH . 'app/view';

		// if (is_array($templateTitle)) {
		// 	foreach ($templateTitle as $title) {
		// 		$path .= '/' . $title;
		// 	}
		// } else {
		// 	$path = BASE_PATH . 'app/view/' . strtolower($templateTitle);
		// }
			
		$path = BASE_PATH . 'app/view/' . strtolower($templateTitle);
		$path .= '.php';
		$cache = new Cache($this->database, $this->config)	;
		if (!file_exists($path)) {
			echo 'Template ' . $path . ' does not exist.';
			return false;
		}
		$this->template = $path;

		// prepare common models
		$this->header();
		foreach ($this->objects as $title => $object) {
			$titles[] = $title; // temp
			if ($object instanceof Model) {
				if ($object->getData()) {
					$this->data[$title] = $object->getData();
				} else {
					$this->data[$title] = false;
				}
			} else {
				$this->data[$title] = $object;
			}
		}

		// debug fun
		// echo '<pre>';
		// print_r($this->session->getData());
		// print_r($this->config);
		// print_r($titles);
		// print_r($this->data);
		// echo '</pre>';
		// exit;

		header('Content-type: text/html; charset=utf-8'); 
		
		// presentation & cache
		ob_start();	
		require_once($path);
		$cache->create($templateTitle, ob_get_contents());
		ob_end_flush();	
		exit;
	}


	/**
	 * simply loads a template this has been used for the sitemap
	 * @param  string $templateTitle 
	 */
	public function loadJustTemplate($templateTitle)
	{			
		$path = BASE_PATH . 'app/view/' . strtolower($templateTitle) . '.php';
		if (!file_exists($path)) {
			echo 'Template ' . $path . ' does not exist.';
			return false;
		}
		$this->template = $path;

		foreach ($this->objects as $title => $object) {
			$titles[] = $title; // temp
			if ($object instanceof Model) {
				if ($object->getData()) {
					$this->data[$title] = $object->getData();
				} else {
					$this->data[$title] = false;
				}
			} else {
				$this->data[$title] = $object;
			}
		}

		// echo '<pre>';
		// print_r($this->session->getData());
		// print_r($this->config);
		// print_r($titles);
		// print_r($this->data);
		// echo '</pre>';
		// exit;

		require_once($path);
		exit;
	}


	/**
	 * return feedback and unset session variable
	 */
	public function getFeedback() {
		if ($message = $this->session->getUnset('feedback')) {
			return '<div class="feedback clearfix" title="Dismiss"><p>' . $message . '</p></div>';
		}
	}	
	
	
	/**
	 * grabs base path for the view folder, used for headers, footers
	 * and all includes within the view templates
	 * @return string 
	 */
	public function pathView($template = '') { 
		$path = BASE_PATH . 'app/view/';
		if ($template) {
			$path .= $template . '.php';
		}
		return $path;
	}	

	

	/**
	 * flexible url return, defualts to the base url of the website
	 * @param  string $key 
	 * @return string      
	 */
	public function url($key = 'base') {
		return $this->config->getUrl($key);
	}
	

	/**
	 * gets a users latest tweet!
	 * @param  string $user username
	 * @return string       the tweet
	 */
	public function latestTweet($user) {
		$xml = simplexml_load_file("http://twitter.com/statuses/user_timeline/$user.xml?count=1");
		echo $xml->status->text[0];
	}


	/**
	 * returns a body class using the parts of the url after the domain
	 * @return string 
	 */
	public function getBodyClass() { 
		$bodyClass = '';
		foreach ($this->config->getUrl('path') as $path) {
			$bodyClass .= $path . '-';
		}
		return $bodyClass = rtrim($bodyClass, -1);
	}


	/**
	 * sets the meta for a common page
	 * title
	 * description
	 * keywords
	 * @param array $metas 
	 */
	public function setMeta($metas) {		
		foreach ($metas as $key => $meta) {
			$titleAppend = '';
			if ($key == 'title') {
				$titleAppend = ' | ' . $this->config->getOption('meta_title');
			}
			if (array_key_exists($key, $this->meta)) {
				if (! $this->meta[$key]) {
					$this->meta[$key] = $metas[$key] . $titleAppend;
				}
			} else {
				$this->meta[$key] = $metas[$key] . $titleAppend;
			}
		}
		return $this;
	}


	/**
	 * returns requested meta key
	 * @param  string $key meta key
	 * @return bool or string
	 */
	public function getMeta($key) {
		if (array_key_exists($key, $this->meta))
			return $this->meta[$key];
		return false;
	}
} 