<?php

/**
 * Config
 *
 * core base for object, class, option operations
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
 
class Config
{


	/**
	 * stores returned model data
	 * @var array
	 */
	public $data;


	/**
	 * storage for objects to be passed into other objects
	 * @var array
	 */
	public $objects;

	/**
	 * full set of options data from the main_options table
	 * @var array
	 */
	public $options;


	/**
	 * collection of useful urls,
	 * base
	 * noquery
	 * segments of each url partition
	 * @var array
	 */
	public $url;

	
	public function setOptions($options) {
		$this->options = $options;
		return $this;
	}


	public function getOptions() {
		return $this->options;
	}


	public function getOption($key) {
		return (array_key_exists($key, $this->options) ? $this->options[$key] : false);
	}


	/**
	 * returns an object if it has been registered
	 * @param  string $objectTitle 
	 * @return object|bool              
	 */
	public function getObject($objectTitle) {
		$objectTitle = strtolower($objectTitle);
		if (array_key_exists($objectTitle, $this->objects)) {
			return $this->objects[$objectTitle];
		} else {
			return false;
		}
	}
	
	
	/**
	 * intention is to build the object with submitted objects
	 * takes the __CLASS__ name of objects and sets them within array
	 * @param string|object|array $objects
	 */
	public function setObject($objectsOrKey, $objectOrArray = false) {
		// if (gettype($objectOrArray) == 'array') {
		// 	$this->objects[strtolower($objectsOrKey)] = $objectOrArray;
		// }
		if ((gettype($objectsOrKey) == 'string') && $objectOrArray) {
			$this->objects[strtolower($objectsOrKey)] = $objectOrArray;
			return $this;
		}
		if (is_array($objectsOrKey)) {
			foreach ($objectsOrKey as $objectOrArray) {
				$classTitle = get_class($objectOrArray);
				$this->objects[strtolower($classTitle)] = $objectOrArray;
			}
		} elseif (is_object($objectsOrKey)) {
			$classTitle = get_class($objectsOrKey);
			$this->objects[strtolower($classTitle)] = $objectsOrKey;
		}
		return $this;
	}
	
	
	/**
	 * returns url key or path segment
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
	 * sets url array
	 * scheme, host, path, query
	 * use scheme + host for urlBase
	 * use scheme + host + path implode for urlCurrent
	 * returns $this
	 */	
	public function setUrl($scheme = '', $key = '', $value = '') {
		if ($scheme && $key) {
			$this->url[$scheme][$key] = $value;
			return $this;
		}
		if ($_SERVER) {
			$url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('.', '', $_SERVER['REQUEST_URI']);
			$url = strtolower($url);
			$url = parse_url($url);
			if (array_key_exists('path', $url)) {
				$scriptName = explode('/', strtolower($_SERVER['SCRIPT_NAME']));
				array_pop($scriptName); 
				$scriptName = array_filter($scriptName); 
				$scriptName = array_values($scriptName);			
				$url['path'] = explode('/', $url['path']);
				$url['path'] = array_filter($url['path']);
				$url['path'] = array_values($url['path']);
				foreach (array_intersect($scriptName, $url['path']) as $key => $value) {
					unset($url['path'][$key]);
				}
				$url['path'] = array_values($url['path']);		
			}		
			if (array_key_exists('query', $url)) {
				$url['query'] = explode('[;&]', $url['query']);
			}
			$this->url = $url;
			$scriptName = explode('/', strtolower($_SERVER['SCRIPT_NAME']));
			array_pop($scriptName); 
			$scriptName = array_filter($scriptName); 
			$scriptName = array_values($scriptName);
			$url = $this->getUrl('scheme') . '://' . $this->getUrl('host') . '/';
			foreach ($scriptName as $section) {
				$url .= $section . '/';
			}
			$this->url['base'] = $url;
			$this->url['admin'] = $this->url['base'] . 'admin/';
			$url = $this->url['base'];
			foreach ($this->url['path'] as $segment) {
				$url .= $segment . '/';
			}
			$this->url['current_noquery'] =  $url;
			$this->url['current'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$url = $this->url['base'];
			$segments = $this->url['path'];
			array_pop($segments);
			foreach ($segments as $segment) {
				$url .= $segment . '/';
			}
			$this->url['back'] = $url;
		}
		return $this;
	}	


	/**
	 * master get function for interacting with $this->data
	 * @todo  remember that if keys have been set, then they
	 *        expect to be returned the correct data or false
	 * @param  string|array  $one      
	 * @param  string $two   
	 * @param  string $three 
	 * @return array|string|int            
	 */
	public function get($one = false, $two = false, $three = false) {	
		if ($two === 0) {
			if (array_key_exists($one, $this->data)) {
				return $this->data[$one][$two][$three];
			}
			return false;
		}
		if (is_array($one)) {
			if (array_key_exists($two, $one)) {
				return $one[$two];
			}
			return;
		}
		if ($one && $two && $three) {
			if (is_array($this->data)) {
				if (array_key_exists($one, $this->data)) {
					if (is_array($this->data[$one])) {
						if (array_key_exists($two, $this->data[$one])) {
							if (is_array($this->data[$one][$two])) {
								if (array_key_exists($three, $this->data[$one][$two])) {
									return $this->data[$one][$two][$three];
								}
							} else {
								return $this->data[$one][$two][$three];
							}
						}
					} else {
						return $this->data[$one][$two];
					}
				}
			} else {
				return $this->data;
			}
			return false;
		}
		if ($one && $two) {
			if (is_array($this->data)) {
				if (array_key_exists($one, $this->data)) {
					if (is_array($this->data[$one])) {
						if (array_key_exists($two, $this->data[$one])) {
							return $this->data[$one][$two];
						}
					} else {
						return $this->data[$one][$two];
					}
				}
			} else {
				return $this->data;
			}
			return false;
		}
		if ($one) {
			if (is_array($this->data)) {
				if (array_key_exists($one, $this->data)) {
					return $this->data[$one];
				}
			} else {
				return $this->data[$one];
			}
			return false;
		}
		// return $this->data;
	}	


	/**
	 * full list of class methods without excluded keywords
	 * @param  string $className 
	 * @return array|bool            list of methods
	 */
	public function getClassMethods($className) {
		if (! class_exists($className)) {
			return false;
		}
		$exclusions = array(
			'initialise'
			, 'index'
			, 'load'
			, '__construct'
			, 'loadMethod'
			, 'route'
			, 'getId'
			, 'setOptions'
			, 'getOptions'
			, 'getOption'
			, 'getObject'
			, 'setObject'
			, 'getUrl'
			, 'setUrl'
			, 'get'
			, 'getClassMethods'
			, 'generateRandomString'
			, 'generateRandomString'
		);
		foreach (get_class_methods($className) as $method) {
			if (! in_array($method, $exclusions)) {
				$methods[] = $method;
			}
		}
		return $methods;
	}


	/**
	 * bats back a random string, good for unique codes
	 * @param  integer $length how big is the code?
	 * @return string          
	 */
	public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
	

}