<?php
App::uses('Reservation', 'Model');

class ReservationsControllerTest extends ControllerTestCase {
	public $fixtures = array('app.building', 'app.room',
			'app.resource', 'app.reservation', 'app.reservationsResource');

	public function setUp() {
		parent::setUp();

		$this->Reservation = ClassRegistry::init('Reservation');
	}

	public function testGetCreateReservation() {
		$this
				->testAction(
						'/Reservations/createReservation/1/1-1-2012/1-00/1-00',
						array('method' => 'get'));
	}
	/*public function testCreateReservation() {
		$roomId = 1;
		$description = 'test';

		$data = array(
				'Reservation' => array(
					'room_id' => 1,
					'description' => $description,
					'nusp' => $nusp,
					'start_time' => $startTime,
					'end_time' => $startTime,
					)
				);
		$this
				->testAction(
						'/Reservations/createReservation/1/01-01-2012/01-00/01-00',
						array('method' => 'post', 'data' => $data));
		
		$this->Reservation->order = 'Reservation.id DESC';
		$reservation = $this->Reservation->find();

		$this
				->assertEqual($data['Reservation']['room_id'],
						$reservation['Reservation']['room_id']);
	}*/
}
