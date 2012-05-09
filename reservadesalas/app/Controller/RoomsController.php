<?php

App::uses('Room', 'Model');
App::uses('Building', 'Model');

class RoomsController extends AppController {
	public $name = 'Rooms';
	
public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('createAccount', 'confirmEmail', 'login'));

		$this->Room = ClassRegistry::init('Room');
		$this->Building = ClassRegistry::init('Building');
	}
	
	public function createRoom() {
		if ($this->request->is('post')) {
			$this->Room->save($this->request->data);
		}
		
		$this->setBuildingsAndFloors();
	}
	
	private function setBuildingsAndFloors() {
		$this->Building->order = 'Building.name ASC';

		$this->set('buildings', $this->Building->find('all'));
	}
}
