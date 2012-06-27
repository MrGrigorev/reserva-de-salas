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

		if (!$this->isLogged()) {
			$this
					->redirect(
							array('controller' => 'Users', 'action' => 'login'));
		}
		
		$params = $this->params;
		$restrictedActions = array();
		if (in_array($params['action'], $restrictedActions)) {
			if (!$this->isAdmin()) {
				$this->redirect(array('controller' => 'Users', 'action' => 'index'));
			}
		}

		$this->Room = ClassRegistry::init('Room');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Reservation = ClassRegistry::init('Reservation');
		$this->ReservationsResource = ClassRegistry::init(
				'ReservationsResource');
	}

	public function isAuthorized($user) {
		return parent::isAuthorized($user);
	}

	public function chooseDate() {
		$currentTime = time();
		$currentYear = date('Y', $currentTime);

		$firstSemesterFinalTime = mktime(0, 0, 0, 7, 15, $currentYear);

		if ($currentTime < $firstSemesterFinalTime)
			$this->set('untilDate', date('d/m/Y', $firstSemesterFinalTime));
		else
			$this
					->set('untilDate',
							date('d/m/Y', mktime(0, 0, 0, 12, 15, $currentYear)));
	}

	public function createReservation($roomId, $date, $startTime, $endTime) {
		$startDatetime = DateTime::createFromFormat('d-m-Y G-i',
				$date . ' ' . $startTime);
		$displayStart = $startDatetime->format('d/m/Y' . __(' à\s ') . 'G:i');
		$startDatetime = $startDatetime->format('Y-m-d G:i:s');

		$endDatetime = DateTime::createFromFormat('d-m-Y G-i',
				$date . ' ' . $endTime);
		$displayEnd = $endDatetime->format('d/m/Y' . __(' à\s ') . 'G:i');
		$endDatetime = $endDatetime->format('Y-m-d G:i:s');

		if ($this->request->is('post')) {
			$user = $this->getLoggedUser();
			$this->request->data['Reservation']['nusp'] = $user['nusp'];
			// TODO: Verificar se é usuário comum ou não
			$this->request->data['Reservation']['is_activated'] = 1;

			if (!$this->Room
					->isAvailable($roomId, $startDatetime, $endDatetime)) {
				$this
						->showErrorMessage(
								__('Sala não disponível. Selecione outra sala.'));
				$this
						->redirect(
								array('controller' => 'Reservations',
										'action' => 'chooseDate'));
			}
			if ($this->Reservation->save($this->request->data)) {
				$this->showSuccessMessage(__('Reserva realizada com sucesso'));
				$this
						->redirect(
								array('controller' => 'Rooms',
										'action' => 'viewRoom', $roomId));
			} else {
				$this->showErrorMessage(__('Erro ao reservar sala'));
			}
		}

		$this->set('displayStart', $displayStart);
		$this->set('displayEnd', $displayEnd);

		$room = $this->Room->findById($roomId);
		$this->set('room', $room);

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
		$beginTimes = $param->begin_time;
		$endTimes = $param->end_time;
		$capacity = $param->capacity;
		$repetition = $param->repetition;
		$untilDate = $param->until_date;

		if ($untilDate != null || $untilDate != '')
			$untilDate = DateTime::createFromFormat('d/m/Y', $untilDate);

		if ($capacity == null || $capacity == '')
			$capacity = 0;

		$this->RequestHandler->respondAs('json');
		$this->autoRender = false;

		$this->Room->order = 'Room.capacity ASC';
		$allRooms = $this->Room->find('all');

		$datetimeBegin = array();
		$datetimeEnd = array();
		$intersectionTime = array();

		$addDate = '-1 day';
		switch ($repetition) {
		case 'daily':
			$addDate = '+1 day';
			break;
		case 'weekly':
			$addDate = '+7 day';
			break;
		case 'monthly':
			$addDate = '+1 month';
			break;
		}

		for ($i = 0; $i < count($date); $i++) {
			$dateIterator = DateTime::createFromFormat('d/m/Y', $date[$i]);

			if ($repetition == 'none')
				$untilDate = $dateIterator;

			while ($dateIterator <= $untilDate) {
				$datetimeBegin = DateTime::createFromFormat('d/m/Y G:i',
						$dateIterator->format('d/m/Y') . ' ' . $beginTimes[$i]);
				$datetimeEnd = DateTime::createFromFormat('d/m/Y G:i',
						$dateIterator->format('d/m/Y') . ' ' . $endTimes[$i]);

				$intersectionTime[] = array(
						'Reservation.end_time >=' => $datetimeBegin
								->format('Y-m-d G:i:s'),
						'Reservation.start_time <=' => $datetimeEnd
								->format('Y-m-d G:i:s'),
						'Reservation.is_activated' => true);

				$dateIterator = strtotime($addDate,
						$dateIterator->getTimestamp());
				$dateIterator = date('d/m/Y', $dateIterator);
				$dateIterator = DateTime::createFromFormat('d/m/Y',
						$dateIterator);
			}
		}

		$reservations = $this->Reservation
		        ->find('all',
		                array('conditions' => array('or' => $intersectionTime)));
		
		foreach ($allRooms as $i => $room) {
		    if ($room['Room']['capacity'] < $capacity) {
		        unset($allRooms[$i]);
		        continue;
		    }
		
		    foreach ($reservations as $reservation) {
		        if ($reservation['Reservation']['room_id']
		                == $room['Room']['id']) {
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