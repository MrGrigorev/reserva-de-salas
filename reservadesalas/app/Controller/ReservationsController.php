<?php
App::uses('Room', 'Model');
App::uses('Resource', 'Model');
App::uses('Reservation', 'Model');

class ReservationsController extends AppController {
	public $name = 'Reservations';

	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Room = ClassRegistry::init('Room');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Reservation = ClassRegistry::init('Reservation');
	}

	public function isAuthorized($user) {
		return parent::isAuthorized($user);
	}

	public function chooseDate() {
	}

	public function createReservation() {
		
		// DATETIME FORMAT: yyyy-mm-dd hh:mm:ss
		$startDatetime = "2012-06-01 12:00:00";
		$endDatetime = "2012-06-01 16:00:00";

		$roomId = 1;
		$roomResources = $this->Resource
				->find('all',
						array(
								'conditions' => array(
										'Resource.room_id' => $roomId),
								'fields' => array('Resource.id',
										'Resource.serial_number',
										'Resource.name')));

		$this->set('fixedResources', $roomResources);
		$this->set('start_time', $startDatetime);
		$this->set('end_time', $endDatetime);
		$this->set('room_id', $roomId);
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