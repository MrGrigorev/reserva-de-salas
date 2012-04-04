<?php
App::uses('User', 'Model');

class ProfessorTest extends CakeTestCase {
	public $fixtures = array('app.user', 'app.professor', 'app.department');

	public function setup() {
		parent::setUp();
		$this->Professor = ClassRegistry::init('Professor');
	}
	public function testSaveProfile() {

		$user_id = 445678;
		$user_dept_id = 1;

		$data = array('Professor' => array());
		$data['Professor']['department_id'] = $user_dept_id;

		$this->Professor->saveProfile($user_id, $data);

		$result = $this->Professor->findById($this->Professor->id);

		$this
				->assertEquals($data['Professor']['department_id'],
						$result['Professor']['department_id']);
		$this->assertEquals($user_id, $result['Professor']['user_id']);
	}
}
