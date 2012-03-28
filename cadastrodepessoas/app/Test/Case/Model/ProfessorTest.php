<?php
App::uses('User', 'Model');

class ProfessorTest extends CakeTestCase {
	public $fixtures = array('app.user', 'app.professor');

	public function setup() {
		parent::setUp();
		$this->Professor = ClassRegistry::init('Professor');
	}
	public function testSaveProfile() {

		$user_id = 445678;
		$user_dept = 'DCC';
		$user_lattes = 'http://my.lattes.com';
		$user_webpage = 'http://my.site.com';
		
		
		$data = array('Professor' => array());
		$data['Professor']['department'] = $user_dept;
		$data['Professor']['lattes'] = $user_lattes;
		$data['Professor']['webpage'] = $user_webpage;
		
		
		$this->Professor->saveProfile($user_id, $data);

		$result = $this->Professor->findById($this->Professor->id);

		$this
				->assertEquals($data['Professor']['department'],
						$result['Professor']['department']);
		$this->assertEquals($user_id, $result['Professor']['user_id']);
		$this->assertEquals($data['Professor']['lattes'],
				$result['Professor']['lattes']);
		$this
		->assertEquals($data['Professor']['webpage'],
				$result['Professor']['webpage']);

	}
}
