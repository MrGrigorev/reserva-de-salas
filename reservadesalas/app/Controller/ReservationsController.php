<?php
App::uses('Room', 'Model');
App::uses('Resource', 'Model');
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
		$restrictedActions = array('activateReservation', 'rejectReservation', 'listReservationRequests');
		if (in_array($params['action'], $restrictedActions)) {
			if (!$this->isAdmin()) {
				$this
				->redirect(
						array('controller' => 'Users',
								'action' => 'index'));
			}
		}

		if ($params['action'] == 'delete') {
			$reservation = $this->Reservation->findById($this->request->data['Reservation']['id'], array('fields' => 'Reservation.nusp'));
			$user = $this->getLoggedUser();

			if (!($this->isAdmin() || $reservation['Reservation']['nusp'] == $user['nusp']))
				$this->redirect(array('controller' => 'Users', 'action' => 'index'));
		}

		$this->Room = ClassRegistry::init('Room');
		$this->Resource = ClassRegistry::init('Resource');
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

	public function createReservation() {
		$dates = split(',', $this->request->data['Reservation']['dates']);
		$beginTimes = split(',',
				$this->request->data['Reservation']['beginTimes']);
		$endTimes = split(',', $this->request->data['Reservation']['endTimes']);
		$roomId = $this->request->data['Reservation']['roomId'];
		$room = $this->Room->findById($roomId);

		$untilDate = $this->request->data['Reservation']['until'];
		$repetitions = $this->request->data['Reservation']['repetitions'];

		$chosenDatetimes = array();
		$beginDatetimes = array();
		$endDatetimes = array();
		for ($i = 0; $i < count($dates); $i++) {
			$chosenDatetimes[] = $dates[$i] . __(' das ') . $beginTimes[$i]
			. __(' às ') . $endTimes[$i];

			$beginDatetimes[] = $dates[$i] . ' ' . $beginTimes[$i];
			$endDatetimes[] = $dates[$i] . ' ' . $endTimes[$i];
		}

		if (isset($this->request->data['Reservation']['save'])) {
			$this->saveReservations($dates, $beginTimes, $endTimes, $room);
		}

		$this->set('room', $room);
		$this->set('dates', $this->request->data['Reservation']['dates']);
		$this
		->set('beginTimes',
				$this->request->data['Reservation']['beginTimes']);
		$this
		->set('endTimes',
				$this->request->data['Reservation']['endTimes']);

		$this->set('chosenDates', $chosenDatetimes);

		$roomResources = $this->Resource
		->find('all',
				array(
						'conditions' => array(
								'Resource.room_id' => $roomId),
						'fields' => array('Resource.id',
								'Resource.serial_number',
								'Resource.name')));

		$this->set('fixedResources', $roomResources);
		$this->set('room_id', $roomId);
		$this->set('untilDate', $untilDate);
		$this->set('repetitions', $repetitions);
	}

	private function saveReservations($dates, $beginTimes, $endTimes, $room) {
		$user = $this->getLoggedUser();

		$reservation = array();
		$reservation['Reservation']['description'] = $this->request
		->data['Reservation']['description'];
		$reservation['Reservation']['nusp'] = $user['nusp'];
		$reservation['Reservation']['room_id'] = $room['Room']['id'];

		$reservation['Reservation']['is_activated'] = false;
		if ($user['occupation'] != 'student'
				&& $room['Room']['room_type'] == 'normal')
			$reservation['Reservation']['is_activated'] = true;

		$reservation['Resources'] = $this->request->data['Resources'];


		$untilDate = $this->request->data['Reservation']['until'];
		if ($untilDate != null || $untilDate != '')
			$untilDate = DateTime::createFromFormat('d/m/Y', $untilDate);

		$returnValues = $this->addDateRepetitions($this->request->data['Reservation']['repetitions'], $dates, $beginTimes, $endTimes, $untilDate);

		$beginDatetimes = $returnValues['datetimeBegin'];
		$endDatetimes = $returnValues['datetimeEnd'];

		for ($i = 0; $i < count($beginDatetimes); $i++) {
			if (!$this->Room
					->isAvailable($room['Room']['id'], $beginDatetimes[$i],
							$endDatetimes[$i])) {
				$this
				->showErrorMessage(
						__(
								'Sala não disponível. Selecione outra sala.'));
				$this
				->redirect(
						array('controller' => 'Reservations',
								'action' => 'chooseDate'));
			}
		}

		for ($i = 0; $i < count($beginDatetimes); $i++) {
			$reservation['Reservation']['start_time'] = $this->formatDate($beginDatetimes[$i]);
			$reservation['Reservation']['end_time'] = $this->formatDate($endDatetimes[$i]);

			$this->Reservation->Create();
			if ($this->Reservation->save($reservation)) {
				$this
				->showSuccessMessage(
						__('Reserva realizada com sucesso'));
			} else {
				$this->showErrorMessage(__('Erro ao reservar sala'));
				break;
			}
		}

		$this
		->redirect(
				array('controller' => 'Rooms',
						'action' => 'viewRoom', $room['Room']['id']));
	}

	private function formatDate($datetime) {
		return $datetime->format('Y-m-d G:i');
	}

	private function addDateRepetitions($repetition, $date, $beginTimes, $endTimes, $untilDate) {
		$datetimeBegin = array();
		$datetimeEnd = array();
		$intersectionTime = array();

		$addDate = '';
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

		$datetimeBegins = array();
		$datetimeEnds = array();

		for ($i = 0; $i < count($date); $i++) {
			$dateIterator = DateTime::createFromFormat('d/m/Y', $date[$i]);

			if ($repetition == 'none')
				$untilDate = $dateIterator;

			while ($dateIterator <= $untilDate) {
				$datetimeBegin = DateTime::createFromFormat('d/m/Y H:i',
						$dateIterator->format('d/m/Y') . ' ' . $beginTimes[$i]);
				$datetimeEnd = DateTime::createFromFormat('d/m/Y H:i',
						$dateIterator->format('d/m/Y') . ' ' . $endTimes[$i]);

				$datetimeBegins[] = $datetimeBegin;
				$datetimeEnds[] = $datetimeEnd;

				$intersectionTime[] = array(
						'Reservation.end_time >=' => $datetimeBegin
						->format('Y-m-d H:i:s'),
						'Reservation.start_time <=' => $datetimeEnd
						->format('Y-m-d H:i:s'),
						'Reservation.is_activated' => true);

				if ($repetition == 'none')
					break;

				$dateIterator = strtotime($addDate,
						$dateIterator->getTimestamp());
				$dateIterator = date('d/m/Y', $dateIterator);
				$dateIterator = DateTime::createFromFormat('d/m/Y',
						$dateIterator);
			}
		}

		$returnValues = array();

		$returnValues['datetimeBegin'] = $datetimeBegins;
		$returnValues['datetimeEnd'] = $datetimeEnds;
		$returnValues['intersectionTime'] = $intersectionTime;

		return $returnValues;
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

		$returnValues = $this->addDateRepetitions($repetition, $date, $beginTimes, $endTimes, $untilDate);

		$intersectionTime = $returnValues['intersectionTime'];

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

	public function activateReservation($reservationId, &$message) {
		$this->Reservation->id = $reservationId;

		if (!$this->Reservation) {
			return;
		}

		$rightNow = date("Y-m-d H:i:s");
		$endTime = $this->Reservation->field('end_time');
		if ($endTime < $rightNow) {
			$reservation = $this->Reservation->findById($reservationId);
			$roomName = $reservation['Room']['name'];
			$startTime = $reservation['Reservation']['start_time'];
			$message .= "Reserva não ativada: $roomName no período de $startTime até $endTime<br />";
			return;
		}

		$this->Reservation->saveField('is_activated', true);

		$reservation = $this->Reservation->findById($reservationId);
		$roomName = $reservation['Room']['name'];
		$startTime = $reservation['Reservation']['start_time'];
		$message .= "Reserva ativada: $roomName no período de $startTime até $endTime<br />";
	}

	public function rejectReservation($reservationId, &$message) {
		$this->Reservation->id = $reservationId;

		if ($this->Reservation->exists() == false)
			return;

		$this->Reservation->delete();

		$reservation = $this->Reservation->findById($reservationId);
		$roomName = $reservation['Room']['name'];
		$startTime = $reservation['Reservation']['start_time'];
		$endTime = $reservation['Reservation']['end_time'];
		$message .= "Reserva rejeitada: $roomName no período de $startTime até $endTime<br />";
	}

	public function listReservationRequests() {
		if ($this->request->is('post')) {
			$message = "";
			if ($this->request->data['action'] == 'Aceita') {
				foreach ($this->request->data['Reservation'] as $id => $reservation)
					if ($reservation['isChecked'])
					$this->activateReservation($id, $message);
			} else if ($this->request->data['action'] == 'Rejeita') {
				foreach ($this->request->data['Reservation'] as $id => $reservation)
					if ($reservation['isChecked'])
					$this->rejectReservation($id, $message);
			}

			$this->showSuccessMessage($message);
		}

		$options['fields'] = array(
				'Reservation.id, Reservation.start_time, Reservation.end_time,
				Reservation.nusp, Reservation.description, Room.name');
		$options['conditions'] = array('Reservation.is_activated' => false);
		$options['order'] = array('Reservation.start_time');
		$inactiveReservations = $this->Reservation->find('all', $options);

		$this->set('inactiveReservations', $inactiveReservations);
	}

	public function viewReservation($id) {
		$reservation = $this->Reservation->findById($id);

		$date = DateTime::createFromFormat('Y-m-d H:i:s', $reservation['Reservation']['end_time']);
		$reservation['Reservation']['end_time'] = $date->format('H:i');

		$date = DateTime::createFromFormat('Y-m-d H:i:s', $reservation['Reservation']['start_time']);
		$reservation['Reservation']['start_time'] = $date->format('H:i');

		$date = $date->format('d/m/Y');

		$this->set('reservation', $reservation);
		$this->set('date', $date);
	}
	
	public function delete() {
		$this->Reservation->id = $this->request->data['Reservation']['id'];
		if ($this->Reservation->delete())
			$this->showSuccessMessage('Reserva apagada com sucesso');
		else
			$this->showErrorMessage('Falha ao apagar reserva');
		
		$this->redirect(array('controller' => 'Rooms', 'action' => 'listRooms'));
	}
}
?>