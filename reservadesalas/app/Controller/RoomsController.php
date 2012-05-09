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
	
	private function setBuildingsAndFloors() {
		$this->Building->order = 'Building.name ASC';

		$this->set('buildings', $this->Building->find('all'));
	}
}
