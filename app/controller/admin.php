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


	/**
	 * @todo test session handling here
	 * @todo feedback should be its own session module
	 * @todo ensure you build in session_history to visit the
	 *       url you intend to after logging in
	 */
	public function initialise() {
		$sessionAdminUser = new session_admin_user($this->database, $this->config);
		$sessionFeedback = new session_feedback($this->database, $this->config);
		$sessionFormfield = new session_formfield($this->database, $this->config);
		$sessionHistory = new session_history($this->database, $this->config);
		// logout
		if (array_key_exists('logout', $_GET) && $sessionAdminUser->getData()) {
			$sessionAdminUser->delete();
			$sessionHistory->delete();
			$sessionFeedback->set('Successfully logged out');
			$this->route('admin');
		}

		// common objects
		$menu = new model_admin_menu($this->database, $this->config);
		$user = new model_user($this->database, $this->config);

		// menu and submenu full structure
		$menu->read();
		$this->view
			->setObject($sessionFormfield)
			->setObject($sessionFeedback)
			->setObject($menu);

		// logging in
		if (array_key_exists('login', $_POST)) {

			// remember form field
			$sessionFormfield->add($_POST, array('login_email', 'login_password'));

			// validate the username and password
			if ($user->validatePassword($_POST['login_email'], $_POST['login_password'])) {
				$sessionAdminUser->login($user->getDataFirst('id'));
				$sessionFeedback->set('Successfully Logged in as ' . $_POST['login_email']);
				
				// send off to captured url if an important one is detected
				if ($sessionHistory->getCaptureUrl()) {
					$this->route($sessionHistory->getCaptureUrl());
				} else {
					$this->route('admin');
				}
			} else {
				$sessionFeedback->set('Email Address or password incorrect');
				$this->route('admin');
			}
		}

		// is logged in?
		if ($sessionAdminUser->isLogged()) {
			$user->lazyRead("
				user.id
				, user.email
				, user.first_name
				, user.last_name
				, concat(user.first_name, ' ', user.last_name) as full_name
				, user.password
				, user.time_registered
				, user.level
			"
			, array('id' => $sessionAdminUser->getData('id')));
			$this->view->setObject('model_user', $user->getDataFirst());
			
		} else {
			if ($this->config->getUrl(1)) {
				$sessionHistory->setCaptureUrl($this->config->getUrl('current'));
			}
			$this->view->loadTemplate('admin/login');
		}
	}


	public function index()
	{
		$this->view->loadTemplate('admin/dashboard');		
	}


	public function content() {
		$this->view->loadTemplate('admin/dashboard');		
	}


	public function media()
	{
		# code...
	}


	public function profile() {
		$sessionFeedback = new session_feedback($this->database, $this->config);
		$modelUser = new model_user($this->database, $this->config);
		$sessionAdminUser = new session_admin_user($this->database, $this->config);
		if (array_key_exists('form_update', $_POST)) {
			$modelUser->updateById($sessionAdminUser->getData('id'));
			$sessionFeedback->set('Profile successfully updated');
			$this->route('current');
		}
		$this->view
			->loadTemplate('admin/profile');
	}
}
