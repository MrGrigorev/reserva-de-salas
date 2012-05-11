<?php
App::uses('Building', 'Model');

class RoomsController extends AppController {
	public $name = 'Rooms';

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Building = ClassRegistry::init('Building');
	}

	public function createRoom() {
		if ($this->request->is('post')) {
			if ($this->Room->save($this->request->data)) {
				$this->Session->setFlash(__('Sala cadastrada com sucesso'));
				$this->redirect(array('controller' => 'Users', 'action' => 'index'));
			}
			else {
				$this->Session->setFlash(__('Erro ao cadastrar sala'));
			}
		}

		$this->setBuildingsAndFloors();
	}

	public function listRooms($order = 'Room.number ASC') {
		$rooms = $this->Room->order = $order;
		$rooms = $this->Room->find('all');

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
		
		$this->set('room', $room);
	}

	private function setBuildingsAndFloors() {
		$this->Building->order = 'Building.name ASC';

		$this->set('buildings', $this->Building->find('all'));
	}
}
