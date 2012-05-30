<?php

App::uses('Resource', 'Model');
App::uses('Room', 'Model');

class ResourcesController extends AppController {
	public $name = 'Resources';
	
	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Room = ClassRegistry::init('Room');
	}

	public function createResource() {
		if ($this->request->is('post')) {
			if ($this->Resource->save($this->request->data)) {
				$this->Session->setFlash(__('Recurso cadastrado com sucesso'));
				$this
						->redirect(
								array('controller' => 'Users',
										'action' => 'index'));
			} else {
				$this->Session->setFlash(__('E#1: Erro ao cadastrar recurso'));
			}
		}

		$this->setRooms();
	}

	public function viewResource($resourceId = null) {
		$resource = $this->Resource->findById($resourceId);
		if (!$resource) {
			$this->Session->setFlash(__('Recurso inexistente'));
			$this
					->redirect(
							array('controller' => 'Resources',
									'action' => 'listResources'));
		}

		$room = $this->Room->findById($resource['Resource']['room_id']);
		$resource['Resource']['room'] = $room['Room']['name'];

		$this->set('resource', $resource);
	}

	private function setRooms() {
		$this->Room->order = 'Room.name ASC';

		$this->set('rooms', $this->Room->find('all'));
	}

	private function parseFilter($key, $filter) {
		$filterLowerCase = strtolower($filter);

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

	private function arrayFilter($resources, $key, $filter) {
		if ($filter == '' || $filter == 'all')
			return $resources;

		$filter = $this->parseFilter($key, $filter);

		foreach ($resources as $i => $resource) {
			if ($this
					->containsCaseInsensitive($resource['Resource'][$key],
							$filter) == false) {
				unset($resources[$i]);
			}
		}

		return $resources;
	}

	private function arrayFIlterIsFixedResource($filteredResources, $filter) {
		$resources = $filteredResources;
		
		if ($filter == '' || $filter == 'all')
			return $resources;

		if ($filter == 'no') {
			foreach ($resources as $i => $resource) {
				if ($resource['Resource']['room_id'] != null
						&& $resource['Resource']['room_id'] != '') {
					unset($resources[$i]);
				}
			}
		} else {
			foreach ($resources as $i => $resource) {
				if ($resource['Resource']['room_id'] == null
						|| $resource['Resource']['room_id'] == '') {
					unset($resources[$i]);
				}
			}

		}

		return $resources;
	}

	private function filterResources($resources) {
		$filteredResources = $resources;

		foreach ($this->request->data['Resource'] as $key => $filter) {
			if ($key == 'is_fixed_resource') {
				$filteredResources = $this
						->arrayFilterIsFixedResource($filteredResources, $filter);
				continue;
			}

			$filteredResources = $this
					->arrayFilter($filteredResources, $key, $filter);
		}

		return $filteredResources;
	}

	public function listResources($order = 'Resource.name ASC') {
		$this->Resource->order = $order;
		$resources = $this->Resource->find('all');

		if ($this->request->is('post')) {
			$resources = $this->filterResources($resources);
		}

		$this->set('resources', $resources);
		$this->set('actualOrder', $order);
	}
	
	public function getAvailableResources($startDatetime = null, $endDatetime = null) {
		$this->RequestHandler->respondAs('json');
		$this->autoRender = false;

		echo json_encode($this->Resource->find('all') );
		exit();
	}
}
