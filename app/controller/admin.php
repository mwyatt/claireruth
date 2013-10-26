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
		$sessionUser = new session_admin_user($this->database, $this->config);
		$sessionFeedback = new session_feedback($this->database, $this->config);
		$sessionFormfield = new session_formfield($this->database, $this->config);
		$sessionHistory = new session_history($this->database, $this->config);
		// logout
		if (array_key_exists('logout', $_GET) && $sessionUser->getData()) {
			$sessionUser->delete();
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
				$sessionUser->login($user->getDataFirst('id'));
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
		if ($sessionUser->isLogged()) {
			$user->read("
				user.id
				, user.email
				, user.first_name
				, user.last_name
				, concat(user.first_name, ' ', user.last_name) as full_name
				, user.password
				, user.time_registered
				, user.level
			"
			, array('id' => $sessionUser->getData('id')));
			$this->view->setObject('model_user', $user->getDataFirst());
		} else {
			if ($this->config->getUrl(1)) {
				$sessionHistory->setCaptureUrl($this->config->getUrl('current'));
				$this->route('admin');
			}
			$this->view->loadTemplate('admin/login');
		}
	}


	public function index() {
		$this->view->loadTemplate('admin/dashboard');		
	}


	public function content() {
		$this->load(array('admin', 'content'), $this->config->getUrl(2), $this->view, $this->database, $this->config);
	}


	public function media() {
		$this->view->loadTemplate('admin/media-index');
	}
	

	public function profile() {
		// $userAction = new model($this->database, $this->config, 'user_action');
		$user = new model_user($this->database, $this->config);
		if (array_key_exists('form_update', $_POST)) {
			$user->updateById($this->session->get('user', 'id'));
			$this->route('current');
		}
		$user->readById($this->session->get('user', 'id'));
		// $userAction->readById(array($this->session->get('user', 'id')));
		$this->view
			->setObject($userAction)
			->setObject($user)
			->loadTemplate('admin/profile');
	}
}
