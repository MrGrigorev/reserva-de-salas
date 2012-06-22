<?php
App::uses('Building', 'Model');
App::uses('Room', 'Model');
App::uses('Resource', 'Model');
App::uses('RoomsController', 'Controller');
App::uses('UsersControllerTest', 'Test/Case/Controller');

class RoomsControllerTest extends ControllerTestCase {
	public $fixtures = array('app.building', 'app.room',
			'app.resource', 'app.reservation', 'app.reservationsResource');

	public function setUp() {
		parent::setUp();

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
				'Room' => array('building_id' => 3, 'name' => 'Jacy Monteiro',
						'number' => 100, 'floor' => 0,
						'room_type' => 'auditorium',
						'description' => 'Auditorio legal', 'capacity' => 100));

		$this
				->testAction('Rooms/createRoom',
						array('method' => 'post', 'data' => $data));

		$this->Room->order = 'Room.id DESC';
		$room = $this->Room->find('first');

		$this
				->assertEqual($room['Room']['building_id'],
						$data['Room']['building_id']);
		$this->assertEqual($room['Room']['name'], $data['Room']['name']);
		$this->assertEqual($room['Room']['number'], $data['Room']['number']);
		$this->assertEqual($room['Room']['floor'], $data['Room']['floor']);
		$this
				->assertEqual($room['Room']['room_type'],
						$data['Room']['room_type']);
		$this
				->assertEqual($room['Room']['description'],
						$data['Room']['description']);
		$this
				->assertEqual($room['Room']['capacity'],
						$data['Room']['capacity']);
	}

	public function testCreateInvalidRoom() {
		$this->Room->order = 'Room.id DESC';
		$last_room = $this->Room->find('first');
		$data = array(
				'Room' => array('building_id' => NULL,
						'name' => 'Jacy Monteiro', 'number' => 100,
						'floor' => NULL, 'room_type' => NULL,
						'description' => 'Auditorio legal', 'capacity' => NULL));

		$this
				->testAction('Rooms/createRoom',
						array('method' => 'post', 'data' => $data));
		$this->Room->order = 'Room.id DESC';
		$room = $this->Room->find('first');

		$this
				->assertEqual($room['Room']['building_id'],
						$last_room['Room']['building_id']);
		$this->assertEqual($room['Room']['name'], $last_room['Room']['name']);
		$this
				->assertEqual($room['Room']['number'],
						$last_room['Room']['number']);
		$this->assertEqual($room['Room']['floor'], $last_room['Room']['floor']);
		$this
				->assertEqual($room['Room']['room_type'],
						$last_room['Room']['room_type']);
		$this
				->assertEqual($room['Room']['description'],
						$last_room['Room']['description']);
		$this
				->assertEqual($room['Room']['capacity'],
						$last_room['Room']['capacity']);
	}

	public function testGetViewRoom() {
		$this->testAction('/Rooms/viewRoom/' . 1, array('method' => 'get'));
	}

	public function testGetViewInvalidRoom() {
		$this
				->assertEqual(
						$this
								->testAction('/Rooms/viewRoom/' . null,
										array('method' => 'get')),
						$this
								->redirect(
										array('controller' => 'Rooms',
												'action' => 'listRoom')));
	}

	public function testListRooms() {
		$this->testAction('Rooms/listRooms', array('method' => 'get'));

		$expected_rooms = $this->Room->find('all');
		$listed_rooms = $this->vars['rooms'];

		$this->assertEqual(count($listed_rooms), count($expected_rooms));

		foreach ($expected_rooms as $room) {
			$this->assertEqual(in_array($room, $listed_rooms), true);
		}

		$expected_buildings = $this->Building->find('all');
		$listed_buildings = $this->vars['buildings'];

		$this
				->assertEqual(count($listed_buildings),
						count($expected_buildings));

		foreach ($expected_buildings as $building) {
			$this->assertEqual(in_array($building, $listed_buildings), true);
		}
	}

	public function testFilterRoomsByName() {
		$data = array('Room' => array('name' => 'CE'),
				'Building' => array('id' => ''));

		$this
				->testAction('Rooms/listRooms',
						array('method' => 'post', 'data' => $data));

		$this->assertInternalType('array', $this->vars['rooms']);
		$this->assertNotEqual($this->vars['rooms'], null);

		$this->assertEqual(count($this->vars['rooms']), 1);
	}
	public function testFilterRoomsByCapacityAndBuilding() {
		$data = array('Room' => array('capacity' => 100, 'floor' => 1),
				'Building' => array('id' => 1));

		$this
				->testAction('Rooms/listRooms',
						array('method' => 'post', 'data' => $data));

		$this->assertInternalType('array', $this->vars['rooms']);
		$this->assertNotEqual($this->vars['rooms'], null);

		$this->assertEqual(count($this->vars['rooms']), 1);
	}
	public function testFilterRoomsByInvalidFloor() {
		$data = array('Room' => array('floor' => 300),
				'Building' => array('id' => ''));

		$this
				->testAction('Rooms/listRooms',
						array('method' => 'post', 'data' => $data));

		$this->assertInternalType('array', $this->vars['rooms']);
		$this->assertNotEqual($this->vars['rooms'], null);

		$this->assertEqual(count($this->vars['rooms']), 0);
	}
}
