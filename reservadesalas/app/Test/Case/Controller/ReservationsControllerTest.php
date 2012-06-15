<?php
App::uses('Reservation', 'Model');
App::uses('User', 'Model');

class ReservationsControllerTest extends ControllerTestCase {
	public $fixtures = array('app.user', 'app.student', 'app.professor',
			'app.employee', 'app.course', 'app.department',
			'app.professorCategory', 'app.building', 'app.room',
			'app.resource', 'app.reservation', 'app.reservationsResource');

	public function setUp() {
		parent::setUp();

		$this->Reservation = ClassRegistry::init('Reservation');
		$this->User = ClassRegistry::init('User');
	}

	public function testGetCreateReservation() {
		$this
				->testAction(
						'/Reservations/createReservation/1/1-1-2012/1-00/1-00',
						array('method' => 'get'));
	}

	private function login() {
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
	}
	
	public function testCreateReservation() {
		$this->login();
		$roomId = 1;
		$description = 'test';

		$data = array(
				'Reservation' => array('room_id' => 1,
						'description' => $description));
		$this
				->testAction(
						'/Reservations/createReservation/1/1-1-2012/1-00/1-00',
						array('method' => 'post', 'data' => $data));
		
		$this->Reservation->order = 'Reservation.id DESC';
		$reservation = $this->Reservation->find();

		$this
				->assertEqual($data['Reservation']['room_id'],
						$reservation['Reservation']['room_id']);
	}
	
}
