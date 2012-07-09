<?php
App::uses('Building', 'Model');
App::uses('Resource', 'Model');
App::uses('Reservation', 'Model');

class RoomsController extends AppController {
	public $name = 'Rooms';

	public function beforeFilter() {
		parent::beforeFilter();

		if (!$this->isLogged()) {
			$this->redirect(array('controller' => 'Users', 'action' => 'login'));
		}

		$params = $this->params;
		$restrictedActions = array('createRoom');
		if (in_array($params['action'], $restrictedActions)) {
			if (!$this->isAdmin()) {
				$this->redirect(array('controller' => 'Users', 'action' => 'index'));
			}
		}

		$this->Building = ClassRegistry::init('Building');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Reservation = ClassRegistry::init('Reservation');
	}

	public function createRoom() {
		if ($this->request->is('post')) {
			if ($this->Room->save($this->request->data)) {
				$this->showSuccessMessage(__('Sala cadastrada com sucesso'));
				$this
				->redirect(
						array('controller' => 'Users',
								'action' => 'index'));
			} else {
				$this->showErrorMessage(__('Erro ao cadastrar sala'));
			}
		}

		$this->setBuildingsAndFloors();
	}

	private function parseFilter($key, $filter) {
		$filterLowerCase = strtolower($filter);
		$filterLowerCase = str_replace('é', 'e', $filterLowerCase);

		if ($key == 'floor' && $filterLowerCase == 'terreo')
			$filterLowerCase = 0;

		return $filterLowerCase;
	}

	private function containsCaseInsensitive($value, $filter) {
		$valueLowerCase = strtolower($value);
		$filterLowerCase = strtolower($filter);

		$position = strpos($valueLowerCase, $filterLowerCase);

		if ($position === false)
			return false;

		return true;
	}

	private function arrayFilter($rooms, $key, $filter, $class) {
		if ($filter == '' || $filter == 'all')
			return $rooms;

		$filter = $this->parseFilter($key, $filter);

		foreach ($rooms as $i => $room) {
			if ($this->containsCaseInsensitive($room[$class][$key], $filter)
					== false) {
				unset($rooms[$i]);
			}
		}

		return $rooms;
	}

	private function arrayFilterCapacity($rooms, $filter) {
		if ($filter == '' || $filter == 'all')
			return $rooms;

		foreach ($rooms as $i => $room) {
			if ($room['Room']['capacity'] < $filter) {
				unset($rooms[$i]);
			}

		}

		return $rooms;
	}

	private function arrayFilterNameOrNumber($rooms, $filter) {
		if ($filter == '' || $filter == 'all')
			return $rooms;

		$filter = $this->parseFilter('name', $filter);

		foreach ($rooms as $i => $room) {
			if (($this->containsCaseInsensitive($room['Room']['name'], $filter)
					== false) && ($room['Room']['number'] != $filter)) {
				unset($rooms[$i]);
			}
		}

		return $rooms;
	}

	private function filterRooms($rooms) {
		$filteredRooms = $rooms;

		foreach ($this->request->data['Room'] as $key => $filter) {
			if ($key == 'capacity') {
				$filteredRooms = $this
				->arrayFilterCapacity($filteredRooms, $filter);

				continue;
			}

			if ($key == 'name') {
				$filteredRooms = $this
				->arrayFilterNameOrNumber($filteredRooms, $filter);

				continue;
			}

			$filteredRooms = $this
			->arrayFilter($filteredRooms, $key, $filter, 'Room');
		}

		$filteredRooms = $this
		->arrayFilter($filteredRooms, 'id',
				$this->request->data['Building']['id'], 'Building');

		return $filteredRooms;
	}

	public function listRooms($order = 'Room.number ASC') {
		$this->Room->order = $order;
		$rooms = $this->Room->find('all');

		$this->Building->order = 'Building.name ASC';
		$buildings = $this->Building->find('all');

		if ($this->request->is('post')) {
			$rooms = $this->filterRooms($rooms);
		}

		$this->set('rooms', $rooms);
		$this->set('buildings', $buildings);
		$this->set('actualOrder', $order);
	}

	public function viewRoom($roomId = null) {
		$room = $this->Room->findById($roomId);
		if (!$room) {
			$this->showErrorMessage(__('Sala inexistente'));
			$this
			->redirect(
					array('controller' => 'Rooms',
							'action' => 'listRooms'));
		}

		$building = $this->Building->findById($room['Room']['building_id']);
		$room['Room']['building'] = $building['Building']['name'];

		$resources = $this->Resource
		->find('all',
				array('conditions' => array('room_id' => $roomId)));

		$room['Room']['resources'] = $resources;
		$this->set('room', $room);
	}

	private function setBuildingsAndFloors() {
		$this->Building->order = 'Building.name ASC';

		$this->set('buildings', $this->Building->find('all'));
	}
	public function getRoomReservations() {
		$room_id = $this->request['data']['room_id'];
		$month = $this->request['data']['month'];
		$year = $this->request['data']['year'];

		$this->autoRender = false;
		$options['conditions'] = array ('Reservation.room_id' => $room_id,
				'Reservation.is_activated' => true,
				'OR' => array('AND' => array('MONTH(Reservation.start_time)' => $month,
						'YEAR(Reservation.start_time)' => $year), 'AND' => array('MONTH(Reservation.end_time)' => $month,
								'YEAR(Reservation.end_time)' => $year) )
		);

		$options['fields'] = array ('Reservation.start_time, Reservation.end_time, Reservation.description, Reservation.nusp');

		$results = $this->Reservation->find('all', $options);

		$response = array();
		foreach ($results as $result) {
			$result['Reservation']['info'] = $result['Reservation']['nusp'] . " - " . substr($result['Reservation']['description'], 0, 20) . "...";
			$result['Reservation']['start_time'] = str_replace(' ', 'T', $result['Reservation']['start_time']);
			$result['Reservation']['end_time'] = str_replace(' ', 'T', $result['Reservation']['end_time']);
			
			$response[] = $result['Reservation'];
		}

		echo json_encode($response);
		exit;
	}
}
