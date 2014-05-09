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


http
	scheme
example.com
	host
foo/bar/
	path
?foo=bar
	query
#foo
	hash

    
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


	/**
	 * http
	 * @var string
	 */
	public $scheme;


	/**
	 * foo.co.uk/
	 * @var string
	 */
	public $host;


	/**
	 * foo, bar
	 * @var array
	 */
	public $path;


	/**
	 * ?foo=bar
	 * @var string
	 */
	public $query;


	/**
	 * #foo
	 * @var string
	 */
	public $hash;


	public function getHost()
	{
		return $this->host;
	}


	/**
	 * determine the base url by parsing the current url and taking only whats
	 * needed
	 * @todo i get the feeling this could be simplified
	 */
	public function setHost()
	{

		// get request and script
		$host = strtolower($_SERVER['HTTP_HOST']);
		$request = strtolower($_SERVER['REQUEST_URI']);
		$script = strtolower($_SERVER['SCRIPT_NAME']);
		$script = str_replace('index.php', '', $script);

		// remove any empty
		$script = array_filter($script); 
		$script = array_values($script);

		// hostname/script/ <- install directory
		return $this->host = $host . US . implode(US, $script) . US;
	}


	/**
	 * http(s)://
	 * @return string 
	 */
	public function getScheme()
	{
		return 'http' . ($this->isSecure() ? 's' : '') . ':' . US . US;
	}


	/**
	 * checks to see if the current connection is secure
	 * checks server vars and server port, untested
	 * @return boolean 
	 */
	public function isSecure() {
		return (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}





	public function getParsed()
	{
		
	}







	public function getBase($extension = '')
	{
		$this->base . $extension;
	}


	public function validateServer()
	{
		return (array_key_exists('HTTP_HOST', $_SERVER) && array_key_exists('REQUEST_URI', $_SERVER) && array_key_exists('SCRIPT_NAME', $_SERVER));
	}


	public function setPath()
	{
		echo '<pre>';
		print_r($this);
		print_r($_SERVER);
		echo '</pre>';
		exit;
		
		$scriptName = explode(US, strtolower($serverScript));
		array_pop($scriptName); 
		$scriptName = array_filter($scriptName); 
		$scriptName = array_values($scriptName);			
		$urlParts['path'] = explode(US, $urlParts['path']);
		$urlParts['path'] = array_filter($urlParts['path']);
		$urlParts['path'] = array_values($urlParts['path']);
		foreach (array_intersect($scriptName, $urlParts['path']) as $key => $value) {
			unset($urlParts['path'][$key]);
		}
		$urlParts['path'] = array_values($urlParts['path']);		
	}


	/**
	 * remember, you dont need all the url constructs, right away
	 * just build the base url, without https / http
	 */
	public function __construct() {
		if (! $this->validateServer()) {
			exit('a required server key is missing to build the url');
		}
		$this->setHost();
		$this->setPath();
				

	



		// admin
		$urlParts['admin'] = $urlParts['base'] . 'admin/';

		// media
		$urlParts['media'] = $urlParts['base'] . 'media/';

		// current_noquery
		$url = $urlParts['base'];
		foreach ($urlParts['path'] as $segment) {
			$url .= $segment . US;
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
			$url .= $segment . US;
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
	 * builUS various url structures
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
