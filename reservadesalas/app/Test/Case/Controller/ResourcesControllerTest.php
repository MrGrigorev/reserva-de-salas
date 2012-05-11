<?php
App::uses('User', 'Model');
App::uses('Building', 'Model');
App::uses('Room', 'Model');
App::uses('Resource', 'Model');
App::uses('ResourcesController', 'Controller');

class ResourcesControllerTest extends ControllerTestCase {
	public $fixtures = array('app.user', 'app.resource', 'app.room', 'app.building');

	public function setUp() {
		parent::setUp();

		$this->User = ClassRegistry::init('User');
		$this->Building = ClassRegistry::init('Building');
		$this->Room = ClassRegistry::init('Room');
		$this->Resource = ClassRegistry::init('Resource');

		$this->ResourcesController = new ResourcesController();
		$this->ResourcesController->constructClasses();
	}
	
	public function testGetCreateResource() {
		$this->testAction('/Resources/createResource/' . 1, array('method' => 'get'));
	}
	
	public function testCreateResource() {
		$data = array(
				'Resource' => array(
					'room_id' => 1,
					'serial_number' => 'R42',
					'name' => 'Projetor Epsilon',
					'description' => 'it works'));

				
		$this
				->testAction('Resources/createResource',
						array('method' => 'post', 'data' => $data));
		
		$this->Resource->order = 'Resource.id DESC';
		$resource = $this->Resource->find('first');

		$this->assertEqual($resource['Resource']['room_id'], $data['Resource']['room_id']);
		$this->assertEqual($resource['Resource']['serial_number'], $data['Resource']['serial_number']);
		$this->assertEqual($resource['Resource']['name'], $data['Resource']['name']);
		$this->assertEqual($resource['Resource']['description'], $data['Resource']['description']);
	}
	
	public function testCreateInvalidResource() {
		$this->Resource->order = 'Resource.id DESC';
		$last_resource = $this->Resource->find('first');
		$data = array(
				'Resource' => array(
					'room_id' => -1,
					'serial_number' => NULL,
					'name' => NULL,
					'description' => 'it works'));

				
		$this
				->testAction('Resources/createResource',
						array('method' => 'post', 'data' => $data));
						
		$this->Resource->order = 'Resource.id DESC';
		$resource = $this->Resource->find('first');

		$this->assertEqual($resource['Resource']['room_id'], $last_resource['Resource']['room_id']);
		$this->assertEqual($resource['Resource']['serial_number'], $last_resource['Resource']['serial_number']);
		$this->assertEqual($resource['Resource']['name'], $last_resource['Resource']['name']);
		$this->assertEqual($resource['Resource']['description'], $last_resource['Resource']['description']);
	}
	
	public function testGetViewResource() {
		$this->testAction('/Resources/viewResource/' . 1, array('method' => 'get'));
	}

}
