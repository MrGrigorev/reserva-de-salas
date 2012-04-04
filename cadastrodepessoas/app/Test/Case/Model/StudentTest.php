<?php
App::uses('User', 'Model');

class StudentTest extends CakeTestCase {
	public $fixtures = array('app.user', 'app.student', 'app.course');

	public function setup() {
		parent::setUp();
		$this->Student = ClassRegistry::init('Student');
	}

	public function testSaveProfile() {
		$user_id = 94758;
		$data = array('Student' => array());
		$data['Student']['course_id'] = 1;

		$this->Student->saveProfile($user_id, $data);

		$result = $this->Student->findById($this->Student->id);

		$this
				->assertEquals($data['Student']['course_id'],
						$result['Student']['course_id']);
		$this->assertEquals($user_id, $result['Student']['user_id']);

	}
}
