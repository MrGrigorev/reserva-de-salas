<?php

App::uses('Resource', 'Model');
App::uses('Room', 'Model');

class ResourcesController extends AppController {
	public $name = 'Resources';
	
public function beforeFilter() {
		parent::beforeFilter();

		$this->Room = ClassRegistry::init('Room');
	}
	
	public function createResource() {
		if ($this->request->is('post')) {
			if ($this->Resource->save($this->request->data)) {
				$this->Session->setFlash(__('Recurso cadastrado com sucesso'));
				$this->redirect(array('controller' => 'Users', 'action' => 'index'));
			}
			else {
				$this->Session->setFlash(__('E#1: Erro ao cadastrar recurso'));
			}
		}
		
		$this->setRooms();
	}
	
	public function viewResource($resourceId = null) {
		$resource = $this->Resource->findById($resourceId);
		if (!$resource) {
			$this->Session->setFlash(__('Recurso inexistente'));
			$this->redirect(array('controller' => 'Resources', 'action' => 'listResources'));
		}
		
		$room = $this->Room->findById($resource['Resource']['room_id']);
		$resource['Resource']['room'] = $room['Room']['name'];
		
		$this->set('resource', $resource);
	}
	
	private function setRooms() {
		$this->Room->order = 'Room.name ASC';

		$this->set('rooms', $this->Room->find('all'));
	}
}
