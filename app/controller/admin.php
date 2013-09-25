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


	public function initialise() {
		$menu = new Model_menu($this->database, $this->config);
		$menu->admin();
		$menu->adminSub();
		$this->view->setObject($menu);
		$user = new Model_user($this->database, $this->config);
		if ($user->isLogged() && $user->get('level') < 10) {
			$accessTo = $user->getPermission($user->get('level'));
			if ($this->config->getUrl(2) && ! in_array($this->config->getUrl(2), $accessTo)) {
				$this->route('base', 'admin/');
			}
		}
		if (array_key_exists('logout', $_GET)) {
			$user->logout();
			$this->session->set('feedback', 'Successfully logged out');
			$this->route('base', 'admin/');
		}
		if (array_key_exists('form_login', $_POST)) {
			if ($user->login($_POST['email_address'], $_POST['password'])) {
				$this->session->set('feedback', 'Successfully Logged in as ' . ($this->session->get('user', 'first_name') ? $this->session->get('user', 'first_name') . ' ' . $this->session->get('user', 'last_name') : $this->session->get('user', 'email')));
				$user->permission();
			}
			$this->session->set('feedback', 'Email Address or password incorrect');
			$this->session->set('form_field', array('email' => $_POST['email_address']));
			$this->route('base', 'admin/');
		}
		if (array_key_exists('form_login_reset', $_POST)) {
			if ($user->passwordReset($_POST['password'])) {
				$this->session->set('feedback', 'Password successfully reset');
			} else {
				$this->session->set('feedback', 'Password was not reset');
			}
			$this->route('base', 'admin/');
		}
		if (array_key_exists('form_login_recovery', $_POST)) {
			if ($user->passwordRecover($_POST['email_address'])) {
				$this->session->set('feedback', 'Password recovery email sent to ' . $_POST['email_address'] . '.');
				$this->route('base', 'admin/');
			} else {
				$this->session->set('feedback', 'Email address is not associated with any account.');
				$this->route('base', 'admin/recovery/');
			}
		}
		if (array_key_exists('code', $_GET) && $_GET['code'] == $this->session->get('password_recovery', 'code')) {
			$this->view->loadTemplate('admin/login-reset');
		}
		if ($user->isLogged()) {
			$user->setData($user->get());
			$this->view->setObject($user);
		} else {
			if ($this->config->getUrl(1) == 'recovery') {
				$this->view->loadTemplate('admin/login-recovery');
			}
			if ($this->config->getUrl(1)) {
				$this->route('base', 'admin/');
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
