<?php

/**
 * will concern itself with large collections of objects and or arrays
 * these will be easily accessible by storing in files '-' delimiter
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Cache extends Data
{


	/**
	 * folder house for cache
	 * @var string
	 */
	public $path = 'app/cache/';


	public $keyCurrent = '';


	/**
	 * typical extension used
	 * @var string
	 */
	public $extension = '.txt';


	/**
	 * returns the full path for a cached item regardless if it exists
	 * @param  string $key this-delimiter-space
	 * @return string      
	 */
	public function getPath($key) {
		return BASE_PATH . $this->path . $key/* . $this->extension*/;
	}


	/**
	 * does the file exist?
	 * @param  string $key 
	 * @return bool      
	 */
	public function fileExists($key)
	{
		if (file_exists($this->getPath($key))) {
			return true;
		}
	}


	/**
	 * serialises and creates cache file if required
	 * if the file already exists, skip this
	 * @param  string $key  example-file-name
	 * @param  array $data 
	 * @return bool       
	 */
	public function create($data)
	{

		// file must not already exist
		if ($this->fileExists($this->getKey())) {
			return;
		}

		// stringify
		$data = serialize($data);

		// write to file
		if (file_put_contents($this->getPath($this->getKey()), $data)) {
			return true;
		}
	}


	public function getKey()
	{
		return $this->keyCurrent;
	}


	/**
	 * reads in the cached file, if it exists
	 * unserialises and stores in data property
	 * @param  string $key example-file-name
	 * @return bool      
	 */
	public function read($key)
	{

		// store attempted key for create function
		$this->keyCurrent = $key;

		// quickly check if a file exists
		if (! $this->fileExists($key)) {
			return;
		}

		// load in
		$data = file_get_contents($this->getPath($key));
		return $this->setData(unserialize($data));
	}


	/**
	 * may not be needed
	 * @return null 
	 */
	public function update()
	{
		# code...
	}


	/**
	 * removes the file from the cache
	 * @param  string $key 
	 * @return bool      
	 */
	public function delete($key)
	{
		
		// nothing to delete
		if (! $this->fileExists($key)) {
			return;
		}

		// remove
		if (unlink($this->getPath($key))) {
			return true;
		}
	}
} 
