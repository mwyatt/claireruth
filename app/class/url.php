<?php


/**
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Url extends System
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


	public $parsed;


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
		$script = strtolower($_SERVER['SCRIPT_NAME']);
		$script = str_replace('index.php', '', $script);

		// remove any empty segments
		$script = explode(US, $script);
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





	public function getParsed()
	{
		return $this->parsed;
	}


	public function setParsed()
	{
		$host = strtolower($_SERVER['HTTP_HOST']);
		$request = strtolower($_SERVER['REQUEST_URI']);
		$urlParsed = parse_url($this->getScheme() . $host . $request);
		$this->parsed = $urlParsed;
	}


	public function setPath()
	{

		// get host and path
		// to intersect against eachother
		$parsed = $this->getParsed();
		$host = $this->getHost();	
		$pathParts = explode(US, $parsed['path']);
		$hostParts = explode(US, $host);
		$parts = array();

		// strip out install directory and empty keys
		// build parts array
		foreach ($pathParts as $pathPart) {
			if (in_array($pathPart, $hostParts)) {
				continue;
			}
			$parts[] = $pathPart;
		}
		$this->path = $parts;
	}


	public function setQuery()
	{
		$parsed = $this->getParsed();
		if (! array_key_exists('query', $parsed)) {
			return;
		}
		$this->query = '?' . $parsed['query'];
	}


	/**
	 * remember, you dont need all the url constructs, right away
	 * just build the base url, without https / http
	 */
	public function __construct() {

		// server validation
		$keys = array('HTTP_HOST', 'SCRIPT_NAME', 'HTTP_HOST', 'REQUEST_URI', 'SERVER_PORT');
		if (! $this->arrayKeyExists($keys, $_SERVER)) {
			exit('a required server key is missing to build the url');
		}
		$this->setParsed();
		$keys = array('scheme', 'host', 'path');
		if (! $this->arrayKeyExists($keys, $this->getParsed())) {
			exit('a key is missing from the parse_url array');
		}
		$this->setHost();
		$this->setPath();
		$this->setQuery();

echo '<pre>';
print_r($this);
echo '</pre>';
exit;


		// scheme
		// host
		// path
		// base
		// admin
		// media
		// current_noquery
		// current

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


	/**
	 * checks to see if the current connection is secure
	 * checks server vars and server port, untested
	 * @return boolean 
	 */
	public function isSecure() {
		return (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}
}
