<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Json extends Data
{


	/**
	 * folder house for cache
	 * @var string
	 */
	public $path = 'app/json/';


	/**
	 * typical extension used
	 * @var string
	 */
	public $extension = '.json';


	/**
	 * decides whether the path string should be used, sometimes the json
	 * file can come from other locations
	 * @var boolean
	 */
	public $usePath = true;


	// public $keyCurrent;


	/**
	 * returns the full path for a cached item regardless if it exists
	 * @param  string $key this-delimiter-space
	 * @return string      
	 */
	public function getPath($key) {
		return BASE_PATH . ($this->getUsePath() ? $this->path : '') . $key . $this->extension;
	}


	public function getUsePath() {
		return $this->usePath;
	}


	public function setUsePath($value)
	{
		$this->usePath = $value;
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
	// public function create($data)
	// {

		// // file must not already exist
		// if ($this->fileExists($this->getKey())) {
		// 	return;
		// }

		// // encode
		// $data = json_encode($data);

		// // write to file
		// if (file_put_contents($this->getPath($this->getKey()), $data)) {
		// 	return true;
		// }
	// }


	/**
	 * reads in the cached file, if it exists
	 * unserialises and stores in data property
	 * @param  string $key example-file-name
	 * @return bool      
	 */
	public function read($key)
	{

		// quickly check if a file exists
		if (! $this->fileExists($key)) {
			return;
		}

		// load in
		$data = file_get_contents($this->getPath($key));
		return $this->setData(json_decode($data));
	}


	/**
	 * updates the specified key with the currently set data
	 * @param  string $key 
	 * @return bool      
	 */
	public function update($key)
	{

		// quickly check if a file exists
		if (! $this->fileExists($key)) {
			return;
		}

		// encode
		// store in file
		$data = json_encode($this->getData(), JSON_PRETTY_PRINT);
		file_put_contents($this->getPath($key), $data);
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
