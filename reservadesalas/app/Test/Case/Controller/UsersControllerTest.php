<?php
App::uses('Admin', 'Model');
App::uses('UsersController', 'Controller');

class UsersControllerTest extends ControllerTestCase {
	public $fixtures = array('app.admin');

	public function setUp() {
		parent::setUp();

		$this->Admin = ClassRegistry::init('Admin');

		$this->UsersController = new UsersController();
		$this->UsersController->constructClasses();
	}
	
	public function testGetIndex() {
		$this->testAction('/Users/index', array('method' => 'get'));
	}

	/*public function testLoginWithLoggedUser() {
		$nusp = '12345678';
		$password = '12345';
		$this->doLogin($nusp, $password);
		
		$result = $this
				->testAction('Users/login',
						array('method' => 'get', 'return' => 'vars'));

		$this->assertNotContains('login', $this->headers['Location']);
	}
	
	/*
	public function loginAsActiveUser() {
		$this->testAction('Users/logout');
		
		$password = '12345';
		$user = UsersControllerTest::getActiveUser($password);
		$data = array(
				'User' => array('nusp' => $user['User']['nusp'],
						'password' => $password));
		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));
	}
	
	public function getActiveUser($password) {
		$hash = AuthComponent::password($password);
		$users = $this->User->find("all");
		foreach($users as $user) {
			if ($user['User']['activation_status'] == "active" && $user['User']['password'] == $hash) {
				return $user;
			}
		}
		return null;
	}*/
}
