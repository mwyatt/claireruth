<?php

/**
 * handles form validation, can be used through ajax which will pass
 * a json object, otherwise an array is returned and passed to the view
 * various functions
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Form extends Config
{


	/**
	 * auto filled with the form structure
	 * basically identifies what to expect in a form
	 * then validates post against this structure when required
	 * returns array or json object
	 *
	 * once form is valid this data should be replaced? with feedback
	 * @var array
	 */
	public $data = array(
		'form_admin_login' => array(
			'email' => array('email')
			, 'password' => array('email')
			, 'another' => true
		)
	);


	/**
	 * loops through the formstructure and matches against post
	 * @param  string $formName 
	 * @return [type]           [description]
	 */
	public function validate($formName)
	{

		// no form structure means it must be valid!
		if (! $formStructure = $this->getData($formName)) {
			return true;
		}

		// 
		$resultStructure = $formStructure;

		// get through the structure and make sure that it passes any
		// checks
		foreach ($formStructure as $fieldName => $methodsToPass) {
			if (! array_key_exists($fieldName, $_POST)) {
				$resultStructure[$fieldName] = 'nothing';
				continue;
			}
			if (is_array($methodsToPass)) {
				foreach ($methodsToPass as $methodName) {
					if (method_exists($this, $methodName)) {
						$resultStructure = $this->$methodName($_POST[$fieldName]);
					}
				}
			}

			echo '<pre>';
			print_r($fieldName);
			print_r($methodsToPass);
			echo '</pre>';
			
		}

		// result structure can be passed to view
		$this->setData($resultStructure);
	}


	/**
	 * @todo find a good email validator
	 * @param  string $emailAddress
	 * @return bool
	 */
	public function email($emailAddress = '')
	{
		return;
	}


	/**
	 * handy for checking if a checkbox has been ticked
	 * @param  string  $key 
	 * @return boolean      
	 * @todo remove this if possible, use validate_whatever
	 */
	public function isChecked($key) {
		return (array_key_exists($key, $_POST) ? true : false);
	}	
}
