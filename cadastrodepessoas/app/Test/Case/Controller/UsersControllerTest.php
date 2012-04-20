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
		$this->Student = ClassRegistry::init('Student');
		$this->Professor = ClassRegistry::init('Professor');

		$this->UsersController = new UsersController();
		$this->UsersController->constructClasses();
	}

	public function testGetCreateAccount() {
		$this->testAction('/Users/createAccount', array('method' => 'get'));
	}

	public function testGetLogin() {
		$this->testAction('/Users/login', array('method' => 'get'));
	}

	public function testGetListUsers() {
		$this->testAction('/Users/listUsers', array('method' => 'get'));
	}

	public function testGetViewProfile() {
		$this->testAction('/Users/viewProfile', array('method' => 'get'));
	}

	public function testGetResendConfirmationEmail() {
		$user = $this->User->find('first');

		$this
				->testAction(
						'Users/resendConfirmationEmail/' . $user['User']['id']);
	}

	public function testGetAdminEdit() {
		$this->testAction('Users/adminEdit', array('method' => 'get'));
	}

	public function testCreateAccount() {
		$data = array(
				'User' => array('name' => 'User Test', 'nusp' => '1234567',
						'email' => 'test@ime.usp.br', 'password' => '123456',
						'passwordConfirmation' => '123456',
						'profile' => 'Student', 'lattes' => '',
						'webpage' => ''));

		$data['Student']['course_id'] = 1;
		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$this->User->order = 'User.id DESC';
		$user = $this->User->find('first');

		$this->assertEqual($user['User']['name'], $data['User']['name']);
		$this->assertEqual($user['User']['email'], $data['User']['email']);
		$this->assertEqual($user['User']['nusp'], $data['User']['nusp']);
		$this
				->assertEqual($user['User']['password'],
						AuthComponent::password($data['User']['password']));
		/* TODO: Test course */
	}

	public function testConfirmEmail() {
		$user_id = 2;
		$user = $this->User->findById($user_id);

		$this->testAction('Users/confirmEmail/' . $user['User']['hash']);

		$result = $this->User->findById($user_id);

		$this
				->assertEqual($result['User']['activation_status'],
						'waiting_activation');
	}

	public function testEditProfile() {
		$user_id = 6;
		$user_before = $this->User->findById($user_id);

		$data = array(
				'User' => array('id' => $user_id,
						'lattes' => 'http://changedlattes.com',
						'webpage' => 'http://changedsite.com'));
		$data['Student']['id'] = 2;
		$data['Student']['course_id'] = 2;

		$this
				->testAction('Users/editProfile/',
						array('method' => 'post', 'data' => $data));

		$user_after = $this->User->findById($user_id);

		$this
				->assertEqual($user_after['User']['webpage'],
						$data['User']['webpage']);
		$this
				->assertEqual($user_after['User']['lattes'],
						$data['User']['lattes']);
		$this
				->assertEqual($user_after['Student']['course_id'],
						$data['Student']['course_id']);
		/* TODO: test photo */
	}

	public function testEditProfileInvalidURL() {
		$user_id = 6;
		$user_before = $this->User->findById($user_id);

		$data = array(
				'User' => array('id' => $user_id, 'lattes' => 'IamNotAnURL',
						'webpage' => 'meNeither'));

		$this
				->testAction('Users/editProfile/',
						array('method' => 'post', 'data' => $data));

		$user_after = $this->User->findById($user_id);

		$this
				->assertEqual($user_after['User']['webpage'],
						$user_before['User']['webpage']);
		$this
				->assertEqual($user_after['User']['lattes'],
						$user_before['User']['lattes']);
	}

	public function testLogin() {
		$this->testAction('Users/logout');

		$user_id = 3;
		$user = $this->User->findById($user_id);
		$password = '12345';

		$data = array(
				'User' => array('nusp' => '12345678', 'password' => $password));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));

		$loggedUser = $this->UsersController->getLoggedUser();

		$this->assertNotEqual($loggedUser, null);
		$this->assertEqual($loggedUser['id'], $user_id);
	}

	public function testLoginWithWrongPassword() {
		$this->testAction('Users/logout');

		$user_id = 3;
		$user = $this->User->findById($user_id);
		$password = '54321';

		$data = array(
				'User' => array('nusp' => '12345678', 'password' => $password));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));

		$this->assertEqual($this->UsersController->getLoggedUser(), null);
	}

	public function testLoginWithUserWaitingValidation() {
		$this->testAction('Users/logout');

		$user = $this->User->findByActivation_status('waiting_validation');
		$password = '12345';

		$data = array(
				'User' => array('nusp' => $user['User']['nusp'],
						'password' => $password));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));

		$this->assertEqual($this->UsersController->getLoggedUser(), null);
	}

	public function testLoginWithUserWaitingActivation() {
		$this->testAction('Users/logout');

		$user = $this->User->findByActivation_status('waiting_activation');
		$password = '12345';

		$data = array(
				'User' => array('nusp' => $user['User']['nusp'],
						'password' => $password));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));

		$this->assertEqual($this->UsersController->getLoggedUser(), null);
	}

	public function testLogout() {
		$user_id = 3;
		$user = $this->User->findById($user_id);
		$password = '12345';

		$data = array(
				'User' => array('nusp' => '12345678', 'password' => $password));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));

		$this->assertNotEqual($this->UsersController->getLoggedUser(), null);

		$this->testAction('Users/logout');

		$this->assertEqual($this->UsersController->getLoggedUser(), null);
	}

	public function testCreateBlankAccount() {
		$data = array(
				'User' => array('name' => '', 'nusp' => '', 'email' => '',
						'password' => '', 'passwordConfirmation' => '',
						'profile' => ''));

		$this->User->order = 'User.id DESC';
		$userBefore = $this->User->find('first');

		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$this->User->order = 'User.id DESC';
		$userAfter = $this->User->find('first');

		$this->assertEqual($userBefore, $userAfter);
	}

	public function testCreateAccountWithExistingNusp() {
		$existingUser = $this->User->find('first');

		$data = array(
				'User' => array('name' => 'Teste',
						'nusp' => $existingUser['User']['nusp'],
						'email' => 'testExistingNusp@gmail.com',
						'password' => '123456',
						'passwordConfirmation' => '123456',
						'profile' => 'Student'));

		$data['Student']['course'] = 'BCC';

		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$count = $this->User
				->find('count',
						array(
								'conditions' => array(
										'nusp' => $existingUser['User']['nusp'])));

		$this->assertEqual($count, 1);
	}

	public function testCreateAccountWithExistingEmail() {
		$existingUser = $this->User->find('first');

		$data = array(
				'User' => array('name' => 'Teste', 'nusp' => '98794561',
						'email' => $existingUser['User']['email'],
						'password' => '123456',
						'passwordConfirmation' => '123456',
						'profile' => 'Student'));

		$data['Student']['course'] = 'BCC';

		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$count = $this->User
				->find('count',
						array(
								'conditions' => array(
										'nusp' => $existingUser['User']['nusp'])));

		$this->assertEqual($count, 1);
	}

	private function loginWithAdmin() {
		$admin = $this->User->findByUser_type('admin');

		$data = array(
				'User' => array('nusp' => $admin['User']['nusp'],
						'password' => '12345'));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));
	}

	public function testActivateAccounts() {
		$this->loginWithAdmin();

		$users = $this->User
				->find('all',
						array(
								'conditions' => array(
										'activation_status' => 'waiting_activation')));

		$data = array('User' => array());
		foreach ($users as $user) {
			$data['User'][$user['User']['id']]['isChecked'] = true;
			$data['User'][$user['User']['id']]['id'] = $user['User']['id'];
		}

		$data['action'] = 'Ativa';

		$this
				->testAction('Users/listActivationRequests',
						array('method' => 'post', 'data' => $data));

		foreach ($users as $user) {
			$result = $this->User->findById($user['User']['id']);

			$this->assertEqual($result['User']['activation_status'], 'active');
		}
	}

	public function testRejectAccounts() {
		$this->loginWithAdmin();

		$users = $this->User
				->find('all',
						array(
								'conditions' => array(
										'activation_status' => 'waiting_activation')));

		$data = array('User' => array());
		foreach ($users as $user) {
			$data['User'][$user['User']['id']]['isChecked'] = true;
			$data['User'][$user['User']['id']]['id'] = $user['User']['id'];
		}

		$data['action'] = 'Rejeita';

		$this
				->testAction('Users/listActivationRequests',
						array('method' => 'post', 'data' => $data));

		foreach ($users as $user) {
			$result = $this->User->findById($user['User']['id']);

			$this->assertEqual($result, null);
		}
	}

	public function testAdminEdit() {
		$this->loginWithAdmin();

		$userBefore = $this->User->find('first');

		$userProfile = 'Student';
		$course_or_dep = "course_id";
		if ($userProfile == $this->User->profile($userBefore)) {
			$userProfile = 'Professor';
			$course_or_dep = "department_id";
		}

		$data = array(
				'User' => array('id' => $userBefore['User']['id'],
						'name' => 'User Admin Edit',
						'nusp' => $userBefore['User']['nusp'],
						'email' => $userBefore['User']['email'],
						'profile' => $userProfile, 'lattes' => '',
						'webpage' => ''),
				$userProfile => array('user_id' => $userBefore['User']['id'],
						'id' => 42, $course_or_dep => 1));

		$this
				->testAction('Users/adminEdit/' . $userBefore['User']['id'],
						array('method' => 'post', 'data' => $data));

		$userAfter = $this->User->findById($userBefore['User']['id']);

		$this->assertEqual($userAfter['User']['name'], $data['User']['name']);
		$this->assertEqual($userAfter['User']['nusp'], $data['User']['nusp']);

		if ($userProfile == 'Student') {
			$professor = $this->Professor
					->findById($userBefore['Professor']['id']);
			$this->assertEqual($professor, null);

			$student = $this->Student->findById($userAfter['Student']['id']);
			$this->assertNotEqual($student, null);
		}

		if ($userProfile == 'Professor') {
			$student = $this->Student->findById($userBefore['Student']['id']);
			$this->assertEqual($student, null);

			$professor = $this->Professor
					->findById($userAfter['Professor']['id']);
			$this->assertNotEqual($professor, null);
		}
	}
}
