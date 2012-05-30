<?php

class ReservationsControllerTest extends ControllerTestCase {
	public $fixtures = array('app.user', 'app.student', 'app.professor',
			'app.employee', 'app.course', 'app.department',
			'app.professorCategory', 'app.building', 'app.room', 'app.resource');

	public function setUp() {
		parent::setUp();
	}
	
	public function testGetCreateReservation() {
		$this->testAction('/Reservations/createReservation/', array('method' => 'get'));
	}
}
