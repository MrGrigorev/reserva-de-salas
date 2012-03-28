<?php
App::uses('User', 'Model');

class EmployeeTest extends CakeTestCase {
	public $fixtures = array('app.user', 'app.employee');

	public function setup() {
		parent::setUp();
		$this->Employee = ClassRegistry::init('Employee');
	}
	public function testSaveProfile() {

		$user_id = 7654;
		$data = array('Employee' => array());
		$data['Employee']['occupation'] = 'Programmer';
		$this->Employee->saveProfile($user_id, $data);

		$result = $this->Employee->findById($this->Employee->id);

		$this
				->assertEquals($data['Employee']['occupation'],
						$result['Employee']['occupation']);
		$this->assertEquals($user_id, $result['Employee']['user_id']);

	}
}
