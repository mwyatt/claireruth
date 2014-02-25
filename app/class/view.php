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
	 * page meta information
	 * @var array
	 */
	public $meta = array();
	
	
	/**
	 * prepare all core objects here and register
	 */	
	public function header() {
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
		$path = $this->pathView($templateTitle);

		// check path is valid
		if (! file_exists($path)) {
			echo 'Template ' . $path . ' does not exist.';
			exit;
		}

		// prepare common models
		$this->header();

		// push variables into global scope
		$this->buildObjects();

		// build scoped objects
		// these will be accessible directly in view templates
		$debugTitles = array();
		foreach ($this->objects as $title => $object) {
			$dataStorage = false;
			$camelTitle = $this->delimiterToCamel($title);

			// is a model with data property
			if (is_object($object) && property_exists($object, 'data')) {
				$dataStorage = $object->getData();

			// is an array or variable
			} else {
				$dataStorage = $object;
			}

			// set the global variable
			$$camelTitle = $dataStorage;

			// set for debugging
			if ($this->isDebug($this)) {
				$debugTitles[$camelTitle] = $dataStorage;
			}
		}

		// debugging
		if ($this->isDebug($this)) {
			echo '<pre>';
			echo '<h3>$variables</h3>';
			print_r($debugTitles);
			echo '<h3>this->data</h3>';
			print_r($this->data);
			echo '</pre>';
			exit;
		}

		// content type
		// @todo make this pull from db?
		header('Content-type: text/html; charset=utf-8'); 
		
		// start buffer
		ob_start();	

		// include template
		require_once($path);

		// store previous output + new output
		$this->setData($this->getData() . ob_get_contents());

		// flush buffer
		ob_end_flush();
	}


	/**
	 * will create an array within data of all pushed data
	 * will always be set, false if no data present
	 */
	public function buildObjects()
	{

		// fly through all set objects and setup in $data array
		foreach ($this->objects as $title => $object) {

			// is a model with data property
			if (is_object($object) && property_exists($object, 'data')) {
				if ($object->getData()) {
					$this->data[$title] = $object->getData();
				} else {
					$this->data[$title] = false;
				}

			// is an array or variable
			} else {
				$this->data[$title] = $object;
			}
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
} 
