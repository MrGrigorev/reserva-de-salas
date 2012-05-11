<?php
App::uses('User', 'Model');
App::uses('Building', 'Model');
App::uses('Room', 'Model');
App::uses('Resource', 'Model');
App::uses('RoomsController', 'Controller');

class RoomsControllerTest extends ControllerTestCase {
	public $fixtures = array('app.user', 'app.building', 'app.room', 'app.resource');

	public function setUp() {
		parent::setUp();

		$this->User = ClassRegistry::init('User');
		$this->Building = ClassRegistry::init('Building');
		$this->Room = ClassRegistry::init('Room');
		$this->Resource = ClassRegistry::init('Resource');

		$this->RoomsController = new RoomsController();
		$this->RoomsController->constructClasses();
	}
	
	public function testGetCreateRoom() {
		$this->testAction('/Rooms/createRoom/' . 1, array('method' => 'get'));
	}
	
	public function testCreateRoom() {
		$data = array(
				'Room' => array('building_id' => 3,
					'name' => 'Jacy Monteiro',
					'number' => 100,
					'floor' => 0,
					'room_type' => 'auditorium',
					'description' => 'Auditorio legal',
					'capacity' => 100
					));

				
		$this
				->testAction('Rooms/createRoom',
						array('method' => 'post', 'data' => $data));
		
		$this->Room->order = 'Room.id DESC';
		$room = $this->Room->find('first');

		$this->assertEqual($room['Room']['building_id'], $data['Room']['building_id']);
		$this->assertEqual($room['Room']['name'], $data['Room']['name']);
		$this->assertEqual($room['Room']['number'], $data['Room']['number']);
		$this->assertEqual($room['Room']['floor'], $data['Room']['floor']);
		$this->assertEqual($room['Room']['room_type'], $data['Room']['room_type']);
		$this->assertEqual($room['Room']['description'], $data['Room']['description']);
		$this->assertEqual($room['Room']['capacity'], $data['Room']['capacity']);
	}
	
	public function testCreateInvalidRoom() {
			$this->Room->order = 'Room.id DESC';
			$last_room = $this->Room->find('first');
			$data = array(
					'Room' => array('building_id' => NULL,
						'name' => 'Jacy Monteiro',
						'number' => 100,
						'floor' => NULL,
						'room_type' => NULL,
						'description' => 'Auditorio legal',
						'capacity' => NULL
						));
	
			$this
					->testAction('Rooms/createRoom',
							array('method' => 'post', 'data' => $data));
			$this->Room->order = 'Room.id DESC';
			$room = $this->Room->find('first');
			
	
			$this->assertEqual($room['Room']['building_id'], $last_room['Room']['building_id']);
			$this->assertEqual($room['Room']['name'], $last_room['Room']['name']);
			$this->assertEqual($room['Room']['number'], $last_room['Room']['number']);
			$this->assertEqual($room['Room']['floor'], $last_room['Room']['floor']);
			$this->assertEqual($room['Room']['room_type'], $last_room['Room']['room_type']);
			$this->assertEqual($room['Room']['description'], $last_room['Room']['description']);
			$this->assertEqual($room['Room']['capacity'], $last_room['Room']['capacity']);
	}

	public function testGetViewRoom() {
		$this->testAction('/Rooms/viewRoom/' . 1, array('method' => 'get'));
	}
}
