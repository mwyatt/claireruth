<?php
/*
    [scheme] => http
    [host] => localhost
    [path] => Array
        (
        )

    [base] => http://localhost/claireruth/
    [admin] => http://localhost/claireruth/admin/
    [media] => http://localhost/claireruth/media/
    [current_noquery] => http://localhost/claireruth/
    [current] => http://localhost/claireruth/
    [back] => http://localhost/claireruth/
 */
/**
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Url
{


	public $base;


	public $parts;


	public $secure;


	public $extAdmin = 'admin';


	public $extMedia = 'media';



	/**
	 * http(s)://
	 * @return string 
	 */
	public function getScheme()
	{
		return 'http' . ($this->isSecure() ? 's' : '') . ':' . DS . DS;
	}


	/**
	 * checks to see if the current connection is secure
	 * checks server vars and server port, untested
	 * @return boolean 
	 */
	public function isSecure() {
		return (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}


	public function getHost()
	{
		return $_SERVER['HTTP_HOST'] . DS;
	}


	public function getParsed()
	{
		
	}


	/**
	 * determine the base url by parsing the current url and taking only whats
	 * needed
	 * @todo i get the feeling this could be simplified
	 */
	public function setBase()
	{
		$request = str_replace('.', '', $_SERVER['REQUEST_URI']);
		$script = $_SERVER['SCRIPT_NAME'];
		$baseHost = $this->getScheme() . $this->getHost();
		$base = $baseHost . $request;
		$base = strtolower($base);
		$parts = parse_url($base);
		$script = explode('/', strtolower($script));
		array_pop($script);
		$script = array_filter($script); 
		$script = array_values($script);
		foreach ($script as $section) {
			$baseHost .= $section . DS;
		}
		return $this->base = $baseHost;
	}


	public function getBase($extension = '')
	{
		$this->base . $extension;
	}


	/**
	 * remember, you dont need all the url constructs, right away
	 * just build the base url, without https / http
	 */
	public function __construct() {
		$this->setBase();

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
		echo '<pre>';
		print_r($urlParts);
		echo '</pre>';
		exit;
		
		return $this;
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


	}	



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
	 * sets the url array
	 * @param string $value 
	 */
	public function setUrl($value = '')
	{
		$this->url = $value;
	}

}
