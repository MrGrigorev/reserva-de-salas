<?php
App::uses('Room', 'Model');
App::uses('Reservation', 'Model');

class ReservationsController extends AppController {
	public $name = 'Reservations';

	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Room = ClassRegistry::init('Room');
		$this->Reservation = ClassRegistry::init('Reservation');
	}

	public function isAuthorized($user) {
		return parent::isAuthorized($user);
	}

	public function chooseDate() {
	}
	
	public function createReservation($roomId, $startDatetime, $endDatetime) {
		// DATETIME FORMAT: yyyy-mm-dd hh:mm:ss
		//$t1 = "2012-06-30 12:00:00";
		//$t2 = "2012-06-30 17:00:00";
	}

	public function loadAvailableRooms() {
		$result = json_decode($this->params['data']);
		$date = $result->date;
		$begin_time = $result->begin_time;
		$end_time = $result->end_time;
		
		$this->RequestHandler->respondAs('json');
		$this->autoRender = false;
		

		$rooms = $this->Room->find('all');
		/*$rooms = $this->Room->find('all', array('conditions'=>array('Room.id >='=> 'aquilo'))); */

		echo json_encode($result);
		exit();
	}
}
?>