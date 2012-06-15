<?php
class UsersController extends AppController {
	public $name = 'Users';

	public function login() {
		$this->set('loginUrl', "http://localhost/cadastrodepessoas/Users/loginService");
	}
	
	public function authorize($userId, $name, $userType) {
		$user = array('id' => $userId, 'name' => $name, 'user_type' => $userType);
		$this->Session->write('user', $user);
		$this->redirect(array('controller' => 'Users', 'action' => 'index'));
	}

	public function logout() {
		$this->Session->destroy();
		$this->redirect(array('controller' => 'Users', 'action' => 'login'));
	}

	public function index() {
		if (!$this->isLogged()) {
			$this->redirect(array('controller' => 'Users', 'action' => 'login'));
		}
	}
}
