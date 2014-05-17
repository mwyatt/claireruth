<?php

/**
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */ 
class Config extends Data
{


	/**
	 * stores options data
	 * at the moment this is used as a kind of global config
	 * @var array
	 */
	public $options;


	/**
	 * marks the app as coming soon and covers with a splash screen
	 * ?preview=true will set session to override this
	 * @var boolean
	 */
	public $comingSoon = false;

	
	/**
	 * sets all options taken from the options table
	 * @param array $options 
	 */
	public function setOptions($options) {
		$this->options = $options;
		return $this;
	}


	/**
	 * returns full options array
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}


	/**
	 * returns a specific option
	 * @param  string $key 
	 * @return int|string|bool      
	 */
	public function getOption($key) {
		if (array_key_exists($key, $this->options)) {
			return $this->options[$key];
		}
	}


	/**
	 * handy for pulling ids from various urls, e.g. martin-wyatt-22
	 * @param  string $segment url segment
	 * @return string          the id
	 */
	protected function getId($segment) {
		$segments = explode('-', $segment);
		return end($segments);
	}
	

	/**
	 * detrmines if app is set in coming soon mode, allows pass through with
	 * a preview get var set
	 * @return boolean 
	 */
	public function isComingSoon()
	{
		if (! $this->comingSoon) {
			return;
		}
		$sessionPreview = new session_preview($this);
		if (array_key_exists('preview', $_GET)) {
			$sessionPreview->setData(true);
		}
		return ! $sessionPreview->getData();
	}


	/**
	 * common settings for a php app
	 * @return object chain
	 */
	public function phpSettings()
	{

		// enable errors, which a custom error handler is set for later
		ini_set('display_errors', true);	

		// set time zone to uk 
		ini_set('date.timezone', "Europe/London");

		// keep processing after user disconnect (for cron)
		ignore_user_abort(true);

		return $this;
	}


	public function getDataSite()
	{
		$data = $this->getData();
		return $data->site;
	}
}
