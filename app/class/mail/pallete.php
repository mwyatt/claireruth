<?php

/**
 * pulls information from the palette.scss and turns the information
 * into variables which can be used for templating
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Mail_Pallete extends Model
{


	/**
	 * path partition to the pallete
	 * @var string
	 */
	public $path = 'sass/_pallete.scss';


	/**
	 * store variables found in pallete
	 * @var array
	 */
	public $sassVariables = array();


	/**
	 * get path with basepath appended
	 * @todo could be added to config class?
	 * @return string 
	 */
	public function getPath() {
		return BASE_PATH . $this->path;
	}


	/**
	 * takes $sass-variable and converts to sass_variable
	 * @param  string $variableName from .scss file
	 * @return string               
	 */
	public function convertSassKeyToKey($variableName)
	{
		$variableName = substr($variableName, strpos($variableName, '$'));
		$variableName = str_replace('$', '', $variableName);
		$variableName = str_replace('-', '_', $variableName);
		return trim($variableName);
	}


	/**
	 * @param  string $key 
	 * @return string      hex
	 */
	public function getSassVariable($key)
	{
		if (array_key_exists($key, $this->sassVariables)) {
			return $this->sassVariables[$key];
		}
	}


	/**
	 * @param  string $key 
	 * @return string      
	 */
	public function getStyle($key)
	{
		if (array_key_exists($key, $this->styles)) {
			return $this->styles[$key];
		}
	}


	/**
	 * loads up sass file and sets the styles up
	 * @return array 
	 */
	public function setSassStyles()
	{
		$pathSass = $this->getPath();
		if (! file_exists($pathSass)) {
			return;
		}
		if (! $scss = file_get_contents($pathSass)) {
			return;
		}
		$sassVariables = array();
		foreach (explode(';', $scss) as $line) {
			if (strpos($line, ':') === false) {
				continue;
			}
			$lineParts = explode(':', $line);
			$key = $this->convertSassKeyToKey(reset($lineParts));
			$hex = trim(end($lineParts));
			$sassVariables[$key] = $hex;
		}
		return $this->sassVariables = $sassVariables;
	}


	/**
	 * core styles for email, pulls in sass variables for colours
	 */
	public function setStyles()
	{
		$style['no_spacing'] = ''
			. 'padding-top: 0;'
			. 'padding-right: 0;'
			. 'padding-bottom: 0;'
			. 'padding-left: 0;'
			. 'margin-top: 0;'
			. 'margin-right: 0;'
			. 'margin-bottom: 0;'
			. 'margin-left: 0;'
			;
		$style['padding'] = ''
			. 'padding-top: 1em;'
			. 'padding-right: 1em;'
			. 'padding-bottom: 1em;'
			. 'padding-left: 1em;'
			;
		$style['typography'] = ''
			. 'font-size: 14px;'
			. 'font-family: arial, sans-serif;'
			;
		$style['typography_small'] = ''
			. $style['typography']
			. 'font-size: 12px;'
			. 'line-height: 1.5em;'
			;
		$style['a'] = ''
			. $style['typography']
			. 'color: ' . $this->getSassVariable('color_primary') . ';'
			. 'padding: 0;' // outlook fix
			. 'text-decoration: underline;'
			;
		$style['a_small'] = ''
			. $style['a']
			. $style['typography_small']
			. 'font-weight: bold;'
			;
		$style['h1'] = ''
			. $style['typography']
			. 'font-size: 1.5em;'
			. 'color: ' . $this->getSassVariable('color_text') . ';'
			;
		$style['h2'] = ''
			. $style['h1']
			. 'font-size: 1.3em;'
			;
		$style['h4'] = ''
			. $style['typography']
			. 'font-size: 1em;'
			. 'color: ' . $this->getSassVariable('color_text') . ';'
			;
		$style['h_a'] = ''
			. $style['typography']
			. $style['a']
			. 'color: blue;'
			;
		$style['p'] = ''
			. $style['typography']
			. 'margin-top: 1em;'
			. 'margin-right: 0;'
			. 'margin-bottom: 1em;'
			. 'margin-left: 0;'
			. 'line-height: 1.2em;'
			. 'color: ' . $this->getSassVariable('color_text') . ';'
			;
		$style['li'] = ''
			. $style['p']
			;
		$style['background_table'] = ''
			. $style['no_spacing']
			. 'width: 100%;'
			. 'line-height: 100%;'
			;
		$style['table'] = ''
			. 'border-collapse: collapse;'
			. 'mso-table-lspace: 0pt;'
			. 'mso-table-rspace: 0pt;'
			;
		$style['table_spaced'] = ''
			. $style['table']
			. 'margin-bottom: 1em;'
			;
		$style['td'] = ''
			. $style['typography']
			. 'border: 1px solid #ccc;'
			. 'padding: 7px;'
			;
		$style['th'] = ''
			. $style['typography']
			. $style['td']
			. 'font-weight: bold;'
			;
		$style['body'] = ''
			. 'width: 100%;'
			. '-webkit-text-size-adjust: 100%;'
			. '-ms-text-size-adjust: 100%;'
			. $style['no_spacing']
			// . $style['color']
			. 'background-color: ' . $this->getSassVariable('color_background') . ';'
			;
		$style['img'] = ''
			. 'outline: none;'
			. 'text-decoration: none;'
			. '-ms-interpolation-mode:  bicubic;'
			. 'border: none;'
			;
		$style['img_block'] = $style['img']
			. 'display: block;'
			;
		$style['div_message'] = ''
			. 'display: block;'
			. 'background-color: rgb(243, 243, 243);'
			. 'padding: 15px;'
			. 'border: 1px solid #D8D8D8;'
			;
		return $this->setData($style);
	}
}
