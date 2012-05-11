<?php
App::uses('Building', 'Model');
App::uses('Resource', 'Model');

class RoomsController extends AppController {
	public $name = 'Rooms';

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Building = ClassRegistry::init('Building');
		$this->Resource = ClassRegistry::init('Resource');
	}

	public function createRoom() {
		if ($this->request->is('post')) {
			if ($this->Room->save($this->request->data)) {
				$this->Session->setFlash(__('Sala cadastrada com sucesso'));
				$this
						->redirect(
								array('controller' => 'Users',
										'action' => 'index'));
			} else {
				$this->Session->setFlash(__('Erro ao cadastrar sala'));
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

	private function arrayFilter($rooms, $key, $filter) {
		if ($filter == '' || $filter == 'all')
			return $rooms;

		$filter = $this->parseFilter($key, $filter);
		
		foreach ($rooms as $i => $room) {
			if (strpos($room['Room'][$key], $filter) === false) {
				//debug($rooms[$i]);
				unset($rooms[$i]);
			}
		}

		return $rooms;
	}

	private function filterRooms($rooms) {
		$filteredRoomsByName = $this->arrayFilter($rooms, 'name', $this->request->data['Room']['name']);
		$filteredRoomsByNumber = $this->arrayFilter($rooms, 'number', $this->request->data['Room']['name']);
		
		$filteredRooms = array_merge($filteredRoomsByName, $filteredRoomsByNumber);
		
		unset($this->request->data['Room']['name']);
		
		foreach ($this->request->data['Room'] as $key => $filter) {
			$filteredRooms = $this->arrayFilter($filteredRooms, $key, $filter);
		}

		return $filteredRooms;
	}

	public function listRooms($order = 'Room.number ASC') {
		$rooms = $this->Room->order = $order;
		$rooms = $this->Room->find('all');
		
		debug($rooms[0]);

		if ($this->request->is('post')) {
			$rooms = $this->filterRooms($rooms);
		}

		$this->set('rooms', $rooms);
		$this->set('actualOrder', $order);
	}
	
	public function viewRoom($roomId = null) {
		$room = $this->Room->findById($roomId);
		if (!$room) {
			$this->Session->setFlash(__('Sala inexistente'));
			$this->redirect(array('controller' => 'Rooms', 'action' => 'listRooms'));
		}
		
		$building = $this->Building->findById($room['Room']['building_id']);
		$room['Room']['building'] = $building['Building']['name'];
		
		$resources = $this->Resource->find('all', array('conditions' => array('room_id' => $roomId) ) );
		
		$room['Room']['resources'] = $resources;
		$this->set('room', $room);
	}

	private function setBuildingsAndFloors() {
		$this->Building->order = 'Building.name ASC';

		$this->set('buildings', $this->Building->find('all'));
	}
}
