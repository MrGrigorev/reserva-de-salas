<?php

App::uses('Building', 'Model');
App::uses('Room', 'Model');
App::uses('Resource', 'Model');
App::uses('ResourcesController', 'Controller');
App::uses('RoomsController', 'Controller');
App::uses('UsersControllerTest', 'Test/Case/Controller');

class ResourcesControllerTest extends ControllerTestCase {
	public $fixtures = array('app.building', 'app.room', 'app.resource');

	public function setUp() {
		parent::setUp();

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
	
	public function testGetViewInvalidResource() {
		$result = $this->testAction('/Resources/viewResource/' . 19782, array('method' => 'get', 'return' => 'vars'));
		$this->assertContains('/Resources/listResources', $this->headers['Location']);
	}
	
	public function testListResources() {
		$this->testAction('Resources/listResources', array('method' => 'get'));
	
		$expected_resources = $this->Resource->find('all');
		$listed_resources = $this->vars['resources'];
		
		$this->assertEqual(count($listed_resources), count($expected_resources));
		
		foreach ($expected_resources as $resource) {
			$this->assertEqual(in_array($resource, $listed_resources), true);
		}
	}
	
	public function testFilterResourcesByNameFixed() {
		$data = array('Resource' => array('is_fixed_resource' => 'yes', 'name' => 'HP'));

		$this
				->testAction('Resources/listResources',
						array('method' => 'post', 'data' => $data));

		$this->assertInternalType('array', $this->vars['resources']);
		$this->assertNotEqual($this->vars['resources'], null);

		$this->assertEqual(count($this->vars['resources']), 1);
	}
	
	public function testFilterResourcesByNameNotFixed() {
		$data = array('Resource' => array('is_fixed_resource' => 'no'));

		$this
				->testAction('Resources/listResources',
						array('method' => 'post', 'data' => $data));

		$this->assertInternalType('array', $this->vars['resources']);
		$this->assertNotEqual($this->vars['resources'], null);

		$this->assertEqual(count($this->vars['resources']), 1);
	}
}
