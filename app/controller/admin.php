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
		$menu = new Model_Mainmenu($this->database, $this->config);
		$menu->admin();
		$menu->adminSub();
		$this->view->setObject($menu);
		$user = new Model_Mainuser($this->database, $this->config);
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
		if (array_key_exists('season', $_GET) && $_GET['season'] == 'start' && ! $this->config->getOption('season_start')) {
			$userAction = new model_mainuser_action($this->database, $this->config);
			$mainOption = new model_mainoption($this->database, $this->config);
			$mainOption->update('season_status', 'started');
			$ttfixture = new model_admin_ttfixture($this->database, $this->config);
			$ttfixture->create();
			$userAction->create($this->session->get('user', 'id'), 'create', 'All fixtures generated, season started');
			$this->session->set('feedback', 'All fixtures generated. You may now <a href="' . $this->config->getUrl('base') . 'league/fixture/fulfill/" title="submit a scorecard">submit a scorecard</a>.');
			$this->route('base', 'admin/league/fixture/fulfill/');
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


	public function league() {
		$this->load(array('admin', 'league'), $this->config->getUrl(2), $this->view, $this->database, $this->config);
	}


	public function media() {
		$this->load(array('admin', 'media'), $this->config->getUrl(2), $this->view, $this->database, $this->config);
	}
	

	public function profile() {
		$userAction = new model_mainuser_action($this->database, $this->config);
		$user = new model_mainuser($this->database, $this->config);
		if (array_key_exists('form_update', $_POST)) {
			$user->updateById($this->session->get('user', 'id'));
			$this->route('current');
		}
		$user->readById($this->session->get('user', 'id'));
		$userAction->readById(array($this->session->get('user', 'id')));
		$this->view
			->setObject($userAction)
			->setObject($user)
			->loadTemplate('admin/profile');
	}
	

	// public function user() {
	// 	$user = new Model_Ttuser($this->database, $this->config);
		// if (array_key_exists('form_update', $_POST)) {
		// 	$user->update($_GET['edit']);
		// 	$this->route('current');
		// }
		// if (array_key_exists('edit', $_GET)) {
		// 	if (! $user->readById(array($_GET['edit']))) {
		// 		$this->route('current_noquery');
		// 	}
		// 	$division->read();
		// 	$team = new Model_Ttteam($this->database, $this->config);
		// 	$team->readByDivision($user->get('division_id'));
		// 	$this->view	
		// 		->setObject($division)
		// 		->setObject($team)
		// 		->setObject($user)
		// 		->loadTemplate('admin/league/user-create-update');
		// }
	// 	$user->read();
	// 	$this->view
	// 		->setObject($user)
	// 		->loadTemplate('admin/league/user');
	// }

	// public function settings() {
	// 	$option = new Model_Mainoption($this->database, $this->config);
	// 	$this->view
	// 		->loadTemplate('admin/settings');
	// }

	
}
