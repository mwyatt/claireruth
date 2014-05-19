<?php


/**
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Url extends Helper
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


	/**
	 * filled with handy urls which are required in many places
	 * @var array
	 */
	public $cache;


	/**
	 * segmented path for controller use
	 * @var array
	 */
	public $parsed;


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
		$this->setScheme();
		$this->setCache();
	}


	/**
	 * @return string 
	 */
	public function getHost()
	{
		return $this->host;
	}


	/**
	 * probes the host portion of url for localhost
	 * if found returns true
	 * @return boolean 
	 */
	public function isLocal()
	{
		if (strpos($this->getHost(), 'localhost') === false) {
			return;
		}
		return true;
	}


	/**
	 * @return string 
	 */
	public function getQuery()
	{
		return $this->query;
	}


	/**
	 * builds a handy cached array for quick access to the common url patterns
	 * this could possibly be done more dynamically..
	 * @todo solve http(s) issue, how to define when and how you want it?
	 */
	public function setCache()
	{
		$scheme = $this->getScheme();
		$host = $this->getHost();
		$query = '?' . $this->getQuery();
		$path = implode(US, $this->getPath()) . US;
		$this->cache = array(
			'base' => $scheme . $host,
			'admin' => $scheme . $host . 'admin' . US,
			'media' => $scheme . $host . 'media' . US,
			'current' => $scheme . $host . $path . $query,
			'current_sans_query' => $scheme . $host . $path
		);
	}


	/**
	 * @param  string $key 
	 * @return string       
	 */
	public function getCache($key = false)
	{
		if (! array_key_exists($key, $this->cache)) {
			return;
		}
		return $this->cache[$key];
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
	public function getScheme($secure = false)
	{
		$scheme = 'http';
		if ($secure || $this->isSecure()) {
			$scheme .= 's';
		}
		return  $scheme . ':' . US . US;
	}


	/**
	 * @return array 
	 */
	public function getParsed()
	{
		return $this->parsed;
	}


	/**
	 * builds array based upon parse_url
	 */
	public function setParsed()
	{
		$host = strtolower($_SERVER['HTTP_HOST']);
		$request = strtolower($_SERVER['REQUEST_URI']);
		$urlParsed = parse_url($this->getScheme() . $host . $request);
		$this->parsed = $urlParsed;
	}


	/**
	 * builds array of current path for use in controllers
	 */
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


	/**
	 * set the query based on parsed finds
	 */
	public function setQuery()
	{
		$parsed = $this->getParsed();
		if (! array_key_exists('query', $parsed)) {
			return;
		}
		$this->query = $parsed['query'];
	}


	/**
	 * sets the scheme
	 * @todo make more dynamic?
	 */
	public function setScheme()
	{
		$parsed = $this->getParsed();
		$this->scheme = $parsed['scheme'] . ':' . US . US;
	}


	/**
	 * @return array 
	 */
	public function getPath() {
		return $this->path;
	}


	public function getPathString()
	{
		return implode(US, $this->path);
	}


	/**
	 * returns path single segment
	 * @param  int $key 0/1/2/3/
	 * @return string
	 */
	public function getPathPart($key = false)
	{

		// invalid
		if (gettype($key) != 'integer') {
			return;
		}
		
		// cache
		$path = $this->path;

		// need specific key, key references the position
		if (! array_key_exists($key, $path)) {
			return;
		}
		return $path[$key];
	}


	/**
	 * checks to see if the current connection is secure
	 * checks server vars and server port, untested
	 * @return boolean 
	 */
	public function isSecure() {
		return (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}


	/**
	 * upgraded get url method, allows unlimited segments
	 * friendly helps out with slashes and making things safe
	 * @param  array   $segments      each/segment/
	 * @return string                 the url
	 */
	public function build($segments = array(), $friendly = true) {
		$finalUrl = $this->getCache('base');
		foreach ($segments as $segment) {
			if ($friendly) {
				$segment = $this->urlFriendly($segment);
			}
			$finalUrl .= $segment . ($friendly ? '/' : '');
		}
		return $finalUrl;
	}
}
