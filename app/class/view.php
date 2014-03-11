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
	 * flag to detect if this is the last template load
	 * @var boolean
	 */
	public $render = false;


	/**
	 * page meta information
	 * @var array
	 */
	public $meta = array();
	

	/**
	 * stores output html, can be combined when loading multiple templates
	 * @var string
	 */
	public $data = '';


	/**
	 * if any meta has been missed, merge with the defaults
	 */
	public function setMetaDefaults()
	{
		$this->setMeta(array(
			'title' => $this->config->getOption('meta_title'),
			'keywords' => $this->config->getOption('meta_keywords'),
			'description' => $this->config->getOption('meta_description')
		));
	}


	public function setRender($value)
	{
		return $this->render = $value;
	}


	public function getRender()
	{
		return $this->render;
	}


	/**
	 * sets header and outputs the data
	 */
	public function render()
	{

		// default header
		header('Content-type: text/html; charset=utf-8'); 

		// output
		echo $this->getData();
	}

	
	/**
	 * load template file and prepare all objects for output
	 * @param  string $templateTitle 
	 */
	public function getTemplate($templateTitle) {
		$path = $this->pathView($templateTitle);
		if (! file_exists($path)) {
			return;
		}
		$this->setMetaDefaults();
		$this->setObject('options', $this->config->getoptions());

		// push variables into data variable
		extract($this->convertObjectsToData());

		// debugging
		if ($this->isDebug($this)) {
			echo '<pre>';
			print_r($this->convertObjectsToData());
			echo '</pre>';
			echo '<hr>';
			exit;
		}
		
		// start output buffer
		ob_start();

		// render template using extracted variables
		include($path);
		$content = ob_get_contents();

		// destroy output buffer
		ob_end_clean();

		// add this data to existing
		$this->setData($this->getData() . $content);

		// return just loaded template result
		return $content;
	}


	/**
	 * loads template and sets render status to true
	 * @param  string $templateTitle 
	 * @return bool                
	 */
	public function renderTemplate($templateTitle)
	{
		$this->getTemplate($templateTitle);
		return $this->setRender(true);
	}


	/**
	 * will create an array within data of all pushed data
	 * will always be set, false if no data present
	 * must be a model | array or variable
	 * converts keys to camelcase
	 */
	public function convertObjectsToData()
	{
		$convertedObjects = array();
		foreach ($this->objects as $title => $object) {
			$camelTitle = $this->delimiterToCamel($title);

			// array / variable
			if (! is_object($object)) {
				$convertedObjects[$camelTitle] = $object;
				continue;
			}

			// must be a model
			if (method_exists($object, 'getData')) {

				// empty model
				if (! $object->getData()) {
					$convertedObjects[$camelTitle] = false;
					continue;
				}

				// filled model
				$convertedObjects[$camelTitle] = $object->getData();
				continue;
			}

			// mold
			$convertedObjects[$camelTitle] = $object;
		}
		return $convertedObjects;
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
	 * appends admin to the path
	 * @param  string $template 
	 * @return string           
	 */
	public function pathAdminView($template = '') { 
		return $this->pathView('admin/' . $template);
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


	/**
	 * looks at an array and creates a string e.g. '3 Items'
	 * @param  array  $items 
	 * @param  string $label 
	 * @return string        
	 */
	public function appendS($items = array(), $label = 'item')
	{
		return count($items) . ' ' . $label . (count($items) > 1 ? 's' : '');
	}


	/**
	 * appends thumbnail information if it is an image
	 * @todo port to view
	 * @param array $result modified row
	 */
	public function getMediaThumb($result)
	{
		if ($result->type != 'application/pdf') {
			$result->thumb = new stdClass();
			$result->thumb->{'300'} = $this->buildUrl(array('thumb/?src=' . $this->config->getUrl('base') . $result->path . '&w=300&h=130'), false);
			$result->thumb->{'150'} = $this->buildUrl(array('thumb/?src=' . $this->config->getUrl('base') . $result->path . '&w=150&h=120'), false);
			$result->thumb->{'350'} = $this->buildUrl(array('thumb/?src=' . $this->config->getUrl('base') . $result->path . '&w=350&h=220'), false);
			$result->thumb->{'760'} = $this->buildUrl(array('thumb/?src=' . $this->config->getUrl('base') . $result->path . '&w=760&h=540'), false);
		}
		return $result;
	}


	/**
	 * returns nothing or the array key
	 * @param  string $needle   
	 * @param  array $haystack 
	 * @return string|bool|int|null           
	 */
	public function get($needle, $haystack) {	
		if (! array_key_exists($needle, $haystack)) {
			return;
		}
		return $haystack[$needle];
	}


	/**
	 * provides a bool response to whether the user is using a admin url
	 * @return boolean 
	 */
	public function isAdmin()
	{
		return $this->url(0) == 'admin';
	}


	
} 
