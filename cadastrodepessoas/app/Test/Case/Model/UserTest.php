<?php
App::uses('User', 'Model');
App::uses('Security', 'Utility');
App::uses('AuthComponent', 'Controller/Component');

class UserTest extends CakeTestCase {
	public $fixtures = array('app.user');

	public function setup() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
	}
	public function testSaveEmptyUser() {
		$data = array(
				'User' => array('name' => '', 'nusp' => '',
						'email' => '', 'password' => '',
						'passwordConfirmation' => '',
						'userType' => ''));
		$this->assertEqual($this->User->save($data), false);

	}
	public function testSaveInvalidUser() {
		$data = array(
				'User' => array('name' => '', 'nusp' => 'dad',
						'email' => 'lala', 'password' => '123123',
						'passwordConfirmation' => '111111',
						'userType' => ''));
		$this->assertEqual($this->User->save($data), false);

	}
	public function testSaveDuplicateNusp() {
		$data = array(
				'User' => array('name' => 'User 2', 'nusp' => '12345678',
						'email' => 'user2@gmail.com', 'password' => '12345678',
						'passwordConfirmation' => '123456',
						'userType' => 'Student')
		);
		$data['Student']['course'] = 'BCC';
		
		$this->assertEqual($this->User->save($data), false);

	}
}
