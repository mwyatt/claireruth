<?php

/**
 * commonly used and helpful functions
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Helper
{


	/**
	 * check multiple array keys exist in an array
	 * @param  array $keys  
	 * @param  array $array 
	 * @return bool        
	 */
	public function arrayKeyExists($keys, $array)
	{
		foreach ($keys as $key) {
			if (array_key_exists($key, $array)) {
				continue;
			}
			return;
		}
		return true;
	}


	/**
	 * bats back a random string, good for unique codes
	 * @param  integer $length how big is the code?
	 * @return string          
	 */
	public function getRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
	

	/**
	 * performs explode() on a string with the given delimiter
	 * and trims all whitespace for the elements
	 */
	public function explodeTrim($str, $delimiter = ',') { 
	    if ( is_string($delimiter) ) { 
	        $str = trim(preg_replace('|\\s*(?:' . preg_quote($delimiter) . ')\\s*|', $delimiter, $str)); 
	        return explode($delimiter, $str); 
	    } 
	    return $str; 
	} 


	/**
	 * friendly url building
	 * @param  string $value 
	 * @return string        one you can be friends with
	 */
	public function urlFriendly($value = null)
	{
	
		// everything to lower and no spaces begin or end
		$value = strtolower(trim($value));
		
		// adding - for spaces and union characters
		$find = array(' ', '&', '\r\n', '\n', '+',',');
		$value = str_replace ($find, '-', $value);
		
		//delete and replace rest of special chars
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$value = preg_replace ($find, $repl, $value);
		
		//return the friendly str
		return $value; 	
	}


	/**
	 * takes 1 dimensional array and converts to an object
	 * @param  array $array 
	 * @return object        
	 */
	public function convertArrayToObject($array)
	{
		$object = new StdClass();
		foreach ($array as $key => $value) {
			$object->$key = $value;
		}
		return $object;
	}


	/**
	 * converts a delimiter seperated string to camelCase
	 * @param  string $delimiter the seperator for the original string
	 * @param  string $value     
	 * @return string            
	 */	
	public function delimiterToCamel($value, $delimiter = '_')
	{

		// return passed value if no underscores found
		if (strpos($value, $delimiter) === false) {
			return $value;
		}

		// initiate for concatenation
		$newValue = '';

		// mashes it together with each word as uppercase first
		foreach (explode($delimiter, $value) as $value) {
			$newValue .= ucfirst($value);
		}

		// always returns a camelcase
		return lcfirst($newValue);
	}
}
