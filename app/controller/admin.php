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
		$viewContent = new view_content($this);
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

		// common objects
		$menu = new model_admin_menu($this);
		$modelUser = new model_user($this);

		// menu and submenu full structure
		$menu->read();
		$this->view
			->setObject($sessionFormfield)
			->setObject($sessionFeedback)
			->setObject('menu', $menu);

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
			if ($this->config->getUrl(1)) {
				$sessionHistory->setCaptureUrl($this->config->getUrl('current'));
				$this->route('admin');
			}
			$this->view->renderTemplate('admin/login');
		}
	}


	public function index()
	{
		$this->view->renderTemplate('admin/dashboard');
	}


	public function content() {
		$this->view->renderTemplate('admin/dashboard');
	}


	// public function media()
	// {
	// 	// for nav menu
	// }


	// public function tag()
	// {
	// 	// for nav menu
	// }


	// public function profile() {
	// 	$sessionFeedback = new session_feedback($this);
	// 	$modelUser = new model_user($this);
	// 	$sessionAdminUser = new session_admin_user($this);
	// 	if (array_key_exists('form_update', $_POST)) {
	// 		// $modelUser->updateById($sessionAdminUser->getData('id'));
	// 		// crypt($_POST['password'])
	// 		$sessionFeedback->set('Profile successfully updated');
	// 		$this->route('current');
	// 	}
	// 	$this->view
	// 		->getTemplate('admin/profile');
	// }
}
