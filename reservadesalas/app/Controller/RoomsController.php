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
				$this->Session->setFlash(__('E#1: Erro ao cadastrar sala'));
			}
		}

		$this->setBuildingsAndFloors();
	}

	public function listRooms($order = 'Room.number ASC') {
		$rooms = $this->Room->order = $order;
		$rooms = $this->Room->find('all');

		$this->set('rooms', $rooms);
	}

	private function setBuildingsAndFloors() {
		$this->Building->order = 'Building.name ASC';

		$this->set('buildings', $this->Building->find('all'));
	}
}
