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
		$this->mockEmail();

	}

	private function mockEmail() {
		$Users = $this
				->generate('Users',
						array('components' => array('Session', 'Email')));
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
		$this->testAction('/Users/viewProfile/' . 1, array('method' => 'get'));
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

	public function testGetIndex() {
		$this->testAction('Users/index', array('method' => 'get'));
	}

	public function testCreateAccountStudent() {

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
	}
	
	public function testCreateAccountProfessor() {

		$data = array(
				'User' => array('name' => 'User Test', 'nusp' => '0384763',
						'email' => 'test2superlegal@ime.usp.br', 'password' => '123456',
						'passwordConfirmation' => '123456',
						'profile' => 'Professor', 'lattes' => '',
						'webpage' => ''));

		$data['Professor']['professor_category_id'] = 1;
		$data['Professor']['department_id'] = 1;

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
	}
	
	public function testCreateAccountEmployee() {

		$data = array(
				'User' => array('name' => 'User Test', 'nusp' => '0384763',
						'email' => 'test2superlegal@ime.usp.br', 'password' => '123456',
						'passwordConfirmation' => '123456',
						'profile' => 'Employee', 'lattes' => '',
						'webpage' => ''));

		$occupation = "Faxineiro";
		
		$data['Employee']['occupation'] = $occupation;

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
		$this->assertEqual($user['Employee']['occupation'], $occupation);
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

	public function testConfirmEmailWithInvalidHash() {
		$this
				->testAction('Users/confirmEmail/' . 'invalid_hash',
						array('method' => 'get'));

		$this->assertContains('Users/login', $this->headers['Location']);
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

	public function testLoginWithLoggedUser() {
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

		$result = $this
				->testAction('Users/login',
						array('method' => 'get', 'return' => 'vars'));

		$this->assertNotContains('login', $this->headers['Location']);
	}

	public function testLogout() {
		$this->testAction('Users/logout');

		$data = array(
				'User' => array('nusp' => '12345678', 'password' => '12345'));

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

		$userBefore = $this->getLastCreatedAccount();

		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$userAfter = $this->getLastCreatedAccount();

		$this->assertEqual($userBefore, $userAfter);
	}

	public function testCreateAccountWithBlankName() {
		$data = array(
				'User' => array('name' => '', 'nusp' => '9873456',
						'email' => 'invalidName@ime.usp.br',
						'password' => '123456',
						'passwordConfirmation' => '123456',
						'profile' => 'Student', 'lattes' => '',
						'webpage' => ''));

		$data['Student']['course_id'] = 1;

		$userBefore = $this->getLastCreatedAccount();

		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$userAfter = $this->getLastCreatedAccount();

		$this->assertEqual($userBefore, $userAfter);
	}

	public function testCreateAccountWithBlankNusp() {
		$data = array(
				'User' => array('name' => 'Teste', 'nusp' => '',
						'email' => 'invalidNusp@ime.usp.br',
						'password' => '123456',
						'passwordConfirmation' => '123456',
						'profile' => 'Student', 'lattes' => '',
						'webpage' => ''));

		$data['Student']['course_id'] = 1;

		$userBefore = $this->getLastCreatedAccount();

		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$userAfter = $this->getLastCreatedAccount();

		$this->assertEqual($userBefore, $userAfter);
	}

	public function testCreateAccountWithInvalidEmail() {
		$data = array(
				'User' => array('name' => 'Teste', 'nusp' => '4769807604',
						'email' => 'invalidEmail', 'password' => '123456',
						'passwordConfirmation' => '123456',
						'profile' => 'Student', 'lattes' => '',
						'webpage' => ''));

		$data['Student']['course_id'] = 1;

		$userBefore = $this->getLastCreatedAccount();

		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$userAfter = $this->getLastCreatedAccount();

		$this->assertEqual($userBefore, $userAfter);
	}

	public function testCreateAccountWithDifferentPasswords() {
		$data = array(
				'User' => array('name' => 'Teste', 'nusp' => '476907604',
						'email' => 'invalidPassword@gmail.com',
						'password' => '123456',
						'passwordConfirmation' => '12357658',
						'profile' => 'Student', 'lattes' => '',
						'webpage' => ''));

		$data['Student']['course_id'] = 1;

		$userBefore = $this->getLastCreatedAccount();

		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$userAfter = $this->getLastCreatedAccount();

		$this->assertEqual($userBefore, $userAfter);
	}

	public function testCreateAccountWithBlankPassword() {
		$data = array(
				'User' => array('name' => 'Teste', 'nusp' => '476907604',
						'email' => 'invalidPassword@gmail.com',
						'password' => '', 'passwordConfirmation' => '12357658',
						'profile' => 'Student', 'lattes' => '',
						'webpage' => ''));

		$data['Student']['course_id'] = 1;

		$userBefore = $this->getLastCreatedAccount();

		$this
				->testAction('Users/createAccount',
						array('method' => 'post', 'data' => $data));

		$userAfter = $this->getLastCreatedAccount();

		$this->assertEqual($userBefore, $userAfter);
	}

	private function getLastCreatedAccount() {
		$this->User->order = 'User.id DESC';

		return $this->User->find('first');
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
		$this->testAction('Users/logout');

		$admin = $this->User->findByUser_type('admin');

		$data = array(
				'User' => array('nusp' => $admin['User']['nusp'],
						'password' => '12345'));

		$this
				->testAction('Users/login',
						array('method' => 'post', 'data' => $data));
	}

	public function testActivateAccounts() {
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

	public function testFilterUsersByName() {
		$this->loginWithAdmin();

		$data = array('User' => array('name' => 'hirat'));

		$this
				->testAction('Users/listUsers',
						array('method' => 'post', 'data' => $data));

		$this->assertInternalType('array', $this->vars['users']);
		$this->assertNotEqual($this->vars['users'], null);

		$this->assertEqual(count($this->vars['users']), 1);
	}

	public function testFilterUsersByNonExistingEmail() {
		$this->loginWithAdmin();

		$data = array('User' => array('email' => 'gdghshgfdkahkhjk'));

		$this
				->testAction('Users/listUsers',
						array('method' => 'post', 'data' => $data));

		$this->assertInternalType('array', $this->vars['users']);
		$this->assertNotEqual($this->vars['users'], null);

		$this->assertEqual(count($this->vars['users']), 0);
	}
}
