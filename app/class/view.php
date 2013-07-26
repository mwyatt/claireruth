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


	public $template;
	public $session;
	public $meta = array();
	
	
	/**
	 * prepare all core objects here and register
	 */	
	public function header() {
		$this->session = new Session($this->database, $this->config);
		$options = $this->config->getoptions();
		$this->setMeta(array(
			'title' => $this->get($options, 'meta_title'),
			'keywords' => $this->get($options, 'meta_keywords'),
			'description' => $this->get($options, 'meta_description')
		));
		$this->setObject('options', $options);
	}

	
	/**
	 * load template file and prepare all objects for output
	 * @param  string $templateTitle 
	 * @return bool                
	 */
	public function loadTemplate($templateTitle)
	{			
		$path = BASE_PATH . 'app/view';
		if (is_array($templateTitle)) {
			foreach ($templateTitle as $title) {
				$path .= '/' . $title;
			}
		} else {
			$path = BASE_PATH . 'app/view/' . strtolower($templateTitle);
		}
		$path .= '.php';
		$cache = new Cache($this->database, $this->config)	;
		if (!file_exists($path)) {
			echo 'Template ' . $path . ' does not exist.';
			return false;
		}
		$this->template = $path;
		
		// $this->config->url['history'] = $session->getPreviousUrl($this->config->url['current']);

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
	 */	
	public function pathView() { 
		return BASE_PATH . 'app/view/';
	}	
	

	public function url($key = 'base') {
		return $this->config->getUrl($key);
	}
	
	/**
	 * return base url
	 */	
	public function urlHome() { 
		return $this->config->getUrl('base');
	}	

	
	public function urlSegment($index) { 
		return $this->config->getUrl($index);
	}	
	
	
	/**
	 * return current url
	 */
	public function urlCurrent() {
		return $this->config->getUrl('current');
	}	
	
	
	public function urlPrevious() {
		return $this->config->getUrl('history');
	}	
	
	
	/**
	 * pull /asset/
	 */
	public function asset($ext = null) { 
		$base = $this->getUrl('base').'asset/';
		return ($ext == null ? $base : $base.$ext);
	}
	
	
	/**
	 * builds image url using filename
	 * @param  string $fileName from mainMedia data results
	 * @return string           url
	 */
	public function media($fileName) {

		if (is_file(BASE_PATH . 'img/upload/' . $fileName))
			return $this->config->getUrl('base') . 'img/upload/' . $fileName;
		else
			return 'http://placehold.it/200x200/';

	}
	

	/**
	 * performs explode() on a string with the given delimiter
	 * and trims all whitespace for the elements
	 */
	function explodeTrim($str, $delimiter = ',') { 
	    if ( is_string($delimiter) ) { 
	        $str = trim(preg_replace('|\\s*(?:' . preg_quote($delimiter) . ')\\s*|', $delimiter, $str)); 
	        return explode($delimiter, $str); 
	    } 
	    return $str; 
	} 


	public function latestTweet($user) {
		$xml = simplexml_load_file("http://twitter.com/statuses/user_timeline/$user.xml?count=1");
		echo $xml->status->text[0];
	}


	public function urlTag($ext = null) { 
		$base = $this->getUrl('base').$this->getUrl(1).'/tags/';
		return ($ext == null ? $base : $base.$ext);
	}


	public function logoMvc() {

		// logic here to add or remove a class and title="Open Homepage"
		
		$html =   '<a href="'.$this->urlHome().'">'
				. '<img src="'.$this->urlMedia('i/logo.png').'">'
				. '</a>';
		echo $html;
	}


	// Returns body class
	public function bodyClass() { 

		$i = 1;
		$class = '';

		while ($this->getUrl($i)) {
			$class .= $this->getUrl($i) . ' ';
			$i ++;
		}

		return trim($class);


		if (!$this->getUrl(1))
			return 'home';
//		$val = ( ? $this->getUrl(1) : );
//		return ' class="'.$val.'"';
	}


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


	public function displayAverage($average) {
		if (! $average) {
			return '';
		}
		return $average . '&#37;';
	}
	

} 