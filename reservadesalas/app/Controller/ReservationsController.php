<?php
App::uses('Room', 'Model');
App::uses('Resource', 'Model');
App::uses('Reservation', 'Model');
App::uses('ReservationsResource', 'Model');

class ReservationsController extends AppController {
	public $name = 'Reservations';

	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Room = ClassRegistry::init('Room');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Reservation = ClassRegistry::init('Reservation');
		$this->ReservationsResource = ClassRegistry::init('ReservationsResource');
	}

	public function isAuthorized($user) {
		return parent::isAuthorized($user);
	}

	public function chooseDate() {
	}

	public function createReservation($roomId, $date, $startTime, $endTime) {
		$startDatetime = DateTime::createFromFormat('d-m-Y G-i', $date.' '.$startTime);
		$startDatetime = $startDatetime->format('Y-m-d G:i:s');
		$endDatetime = DateTime::createFromFormat('d-m-Y G-i', $date.' '.$endTime);
		$endDatetime = $endDatetime->format('Y-m-d G:i:s');
		
		if ($this->request->is('post')) {
			$user = $this->getLoggedUser();
			$this->request->data['Reservation']['user_id'] = $user['id'];
			// TODO: Verificar se é usuário comum ou não
			$this->request->data['Reservation']['is_activated'] = 1;
			
			if (! $this->Room->isAvailable($roomId, $startDatetime, $endDatetime) ) {
				$this->Session->setFlash(__('Sala não disponível. Selecione outra sala.'), 'default', array('class' => 'message errorMessage roundedBorders'));
				$this->redirect(array('controller' => 'Reservations', 'action' => 'chooseDate'));
			}
			
			if ($this->Reservation->save($this->request->data)) {
				$this->Session->setFlash(__('Reserva realizada com sucesso'), 'default', array('class' => 'message success roundedBorders'));
				$this->redirect(array('controller' => 'Rooms', 'action' => 'viewRoom', $roomId));
			}
			else {
				$this->Session->setFlash(__('Erro ao reservar sala'), 'default', array('class' => 'message errorMessage roundedBorders'));
			}
			
		}
		
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
		$param = json_decode($this->params['data']);
		$date = $param->date;
		$begin_time = $param->begin_time;
		$end_time = $param->end_time;
		
		$datetime_begin = DateTime::createFromFormat('d/m/Y G:i', $date.' '.$begin_time);
		$datetime_end = DateTime::createFromFormat('d/m/Y G:i', $date.' '.$end_time);

		$this->RequestHandler->respondAs('json');
		$this->autoRender = false;

		$allRooms = $this->Room->find('all');
		
		$intersectionTime = array('Reservation.end_time >=' => $datetime_begin->format('Y-m-d G:i:s'),
								  'Reservation.start_time <=' => $datetime_end->format('Y-m-d G:i:s'),
								  'Reservation.is_activated' => true);
		$reservations = $this->Reservation->find('all', array('conditions'=>$intersectionTime));
		
		/* Filter available Rooms */
		foreach ($allRooms as $i=>$room) {
			foreach($reservations as $reservation) {
				if ($reservation['Reservation']['room_id'] == $room['Room']['id']) {
					unset($allRooms[$i]);
					break;
				}
			}
		}

		echo json_encode($allRooms);
		exit();
	}
}
?>