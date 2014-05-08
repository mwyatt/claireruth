<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Url
{


	/**
	 * returns url key or path scheme
	 */		
	public function getUrl($key = false) {	
		if (gettype($key) == 'integer') {
			if (array_key_exists('path', $this->url)) {
				if (array_key_exists($key, $this->url['path'])) {
					return $this->url['path'][$key];
				}
			}
			return false;
		}
		if (gettype($key) == 'string') {
			if (array_key_exists($key, $this->url))
				return $this->url[$key];
			return false;				
		}		
		return $this->url;
	}


	public function getUrlCeil()
	{
		$url = $this->config->getUrl('path');
		end($url);
		$urlKey = key($url);
		return ($urlKey ? $urlKey : 0);
	}
	
	
	/**
	 * builds various url structures
	 * base
	 * admin
	 * path
	 * current
	 * current_noquery
	 * back (depreciated)
	 * @todo  could be compressed further
	 * @return object
	 */
	public function initiateUrl() {

		// base vars
		$urlParts = array();
		$serverHost = $_SERVER['HTTP_HOST'];
		$serverRequest = $_SERVER['REQUEST_URI'];
		$serverScript = $_SERVER['SCRIPT_NAME'];
		$scheme = 'http://';

		// init url
		$url = $scheme . $serverHost . str_replace('.', '', $serverRequest);
		$url = strtolower($url);
		$urlParts = parse_url($url);

		// find out and build path array, 0, 1, 2
		if (array_key_exists('path', $urlParts)) {
			$scriptName = explode('/', strtolower($serverScript));
			array_pop($scriptName); 
			$scriptName = array_filter($scriptName); 
			$scriptName = array_values($scriptName);			
			$urlParts['path'] = explode('/', $urlParts['path']);
			$urlParts['path'] = array_filter($urlParts['path']);
			$urlParts['path'] = array_values($urlParts['path']);
			foreach (array_intersect($scriptName, $urlParts['path']) as $key => $value) {
				unset($urlParts['path'][$key]);
			}
			$urlParts['path'] = array_values($urlParts['path']);		
		}		

		// build base url
		$scriptName = explode('/', strtolower($serverScript));
		array_pop($scriptName);
		$scriptName = array_filter($scriptName); 
		$scriptName = array_values($scriptName);
		$url = $scheme . $urlParts['host'] . '/';
		foreach ($scriptName as $section) {
			$url .= $section . '/';
		}
		$urlParts['base'] = $url;

		// admin
		$urlParts['admin'] = $urlParts['base'] . 'admin/';

		// media
		$urlParts['media'] = $urlParts['base'] . 'media/';

		// current_noquery
		$url = $urlParts['base'];
		foreach ($urlParts['path'] as $segment) {
			$url .= $segment . '/';
		}
		$urlParts['current_noquery'] =  $url;

		// current
		$urlParts['current'] = $scheme . $serverHost . $serverRequest;

		// previous url
		// may be obsolete when the history session function is created
		$url = $urlParts['base'];
		$segments = $urlParts['path'];
		array_pop($segments);
		foreach ($segments as $segment) {
			$url .= $segment . '/';
		}
		$urlParts['back'] = $url;

		// set the url
		$this->setUrl($urlParts);
		return $this;
	}	


	/**
	 * sets the url array
	 * @param string $value 
	 */
	public function setUrl($value = '')
	{
		$this->url = $value;
	}

}
