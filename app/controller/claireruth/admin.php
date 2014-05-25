<?php

/**
 * admin
 *
 * PHP version 5
 * 
 * @package	~unknown~
 * @author Martin Wyatt <martin.wyatt@gmail.com> 
 * @version	0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class Controller_Admin extends Controller
{


	public function initialise()
	{
		$this->checkLogged();
		$this->setMenu();
		$this->setUser();
		$this->setFeedback();
	}


	/**
	 * @todo test session handling here
	 * @todo feedback should be its own session module
	 * @todo ensure you build in session_history to visit the
	 *       url you intend to after logging in
	 */
	public function run() {
		$modelUser = new model_user($this);
		$sessionAdminUser = new session_admin_user($this);
		$sessionFeedback = new session_feedback($this);
		$sessionFormfield = new session_formfield($this);
		$sessionHistory = new session_history($this);
		$this->view->setObject('user', false);

		// logout
		if (array_key_exists('logout', $_GET) && $sessionAdminUser->getData()) {
			$sessionAdminUser->delete();
			$sessionHistory->delete();
			$sessionFeedback->set('Successfully logged out');
			$this->route('admin');
		}

		$this->setMenu();
		$this->setUser();
		$this->setFeedback();

		$this->view
			->setObject($sessionFormfield);

		// logging in
		if (array_key_exists('login', $_POST)) {

			// remember form field
			$sessionFormfield->add($_POST, array('login_email', 'login_password'));

			// user exists
			if (! $modelUser->read(array('where' => array('email' => $_POST['login_email'])))) {
				$sessionFeedback->set('Email address does not exist');
				$this->route('admin');
			}

			// validate password
			if (! $modelUser->validatePassword($_POST['login_password'])) {
				$sessionFeedback->set('Password incorrect');
				$this->route('admin');
			}

			// the mold
			$mold = $modelUser->getDataFirst();

			// login
			$sessionAdminUser->login($mold);
			$sessionFeedback->set('Successfully Logged in as ' . $mold->email);
				
			// send off to captured url
			if ($sessionHistory->getCaptureUrl()) {
				$this->route($sessionHistory->getCaptureUrl());
			} else {
				$this->route('admin');
			}
		}

		// is logged in?
		if ($sessionAdminUser->isLogged()) {
			if (! $modelUser->read(array('where' => array('id' => $sessionAdminUser->getData('id'))))) {
				$this->route('admin');
			}
			$this->view->setObject('user', $modelUser->getDataFirst());
		} else {
			if ($this->url->getPathPart(1)) {
				$sessionHistory->setCaptureUrl($this->url->getCache('current'));
				$this->route('admin');
			}
			return $this->view->getTemplate('admin/login');
		}
		return $this->view->getTemplate('admin/dashboard');
	}


	public function setUser()
	{
		$modelUser = new model_user($this);
		$sessionAdminUser = new session_admin_user($this);
		if (! $sessionAdminUser->isLogged()) {
			return;
		}
		if (! $modelUser->read(array('where' => array('id' => $sessionAdminUser->getData('id'))))) {
			$this->route('admin');
		}
		$this->view->setObject('user', $modelUser->getDataFirst());
	}


	public function checkLogged()
	{
		if ($this->url->getPathPart(0) == 'admin' && ! $this->url->getPathPart(1)) {
			return;
		}
		$sessionAdminUser = new session_admin_user($this);
		if (! $sessionAdminUser->isLogged()) {
			$this->route('admin');
		}
	}


	public function setMenu()
	{
		$json = new json($this);
		$json->read('admin/menu');
		$this->view
			->setObject('menu', $json->getData());
	}


	public function setFeedback()
	{
		$sessionFeedback = new session_feedback($this);
		$this->view
			->setObject($sessionFeedback);
	}
}
