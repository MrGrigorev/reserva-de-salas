<?php
App::uses('User', 'Model');
App::uses('Student', 'Model');
App::uses('Professor', 'Model');
App::uses('UsersController', 'Controller');

class UsersControllerTest extends ControllerTestCase {
	public $fixtures = array('app.user', 'app.student', 'app.professor',
			'app.employee', 'app.course', 'app.department',
			'app.professorCategory');

	public function setUp() {
		parent::setUp();

		$this->User = ClassRegistry::init('User');

		$this->UsersController = new UsersController();
		$this->UsersController->constructClasses();
	}
	
	public function testGetIndex() {
		$this->testAction('/Users/index', array('method' => 'get'));
	}

	public function testLogin() {
		$this->testAction('Users/logout');
		
		$user_id = 3;
		$user = $this->User->findById($user_id);
		$password = '12345';

		$data = array(
				'User' => array('nusp' => $user['User']['nusp'],
						'password' => $password));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));

		$loggedUser = $this->UsersController->getLoggedUser();

		$this->assertNotEqual($loggedUser, null);
		$this->assertEqual($loggedUser['id'], $user_id);
	}

	public function testLoginUserNotActive() {
		$this->testAction('Users/logout');
		
		$user_id = 1;
		$user = $this->User->findById($user_id);
		$password = '12345';

		$data = array(
				'User' => array('nusp' => $user['User']['nusp'],
						'password' => $password));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));

		$loggedUser = $this->UsersController->getLoggedUser();

		$this->assertEqual($loggedUser, null);
	}

	public function testLoginWrongPassword() {
		$this->testAction('Users/logout');
		
		$user_id = 3;
		$user = $this->User->findById($user_id);
		$password = '4thtuehtreahgl	';

		$data = array(
				'User' => array('nusp' => $user['User']['nusp'],
						'password' => $password));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));

		$loggedUser = $this->UsersController->getLoggedUser();

		$this->assertEqual($loggedUser['id'], null);
	}
	
	public function testLoginWithLoggedUser() {
		$this->loginAsActiveUser();
		
		$result = $this
				->testAction('Users/login',
						array('method' => 'get', 'return' => 'vars'));

		$this->assertNotContains('login', $this->headers['Location']);
	}
	
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
	}
}
