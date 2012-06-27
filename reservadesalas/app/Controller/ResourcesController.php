<?php

App::uses('Resource', 'Model');
App::uses('Room', 'Model');

class ResourcesController extends AppController {
	public $name = 'Resources';

	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();
		
		if (!$this->isLogged()) {
			$this->redirect(array('controller' => 'Users', 'action' => 'login'));
		}
		
		$params = $this->params;
		$restrictedActions = array('createResource');
		if (in_array($params['action'], $restrictedActions)) {
			if (!$this->isAdmin()) {
				$this->redirect(array('controller' => 'Users', 'action' => 'index'));
			}
		}

		$this->Room = ClassRegistry::init('Room');
	}

	public function createResource() {
		if ($this->request->is('post')) {
			if ($this->Resource->save($this->request->data)) {
				$this->showSuccessMessage(__('Recurso cadastrado com sucesso'));
				$this
						->redirect(
								array('controller' => 'Users',
										'action' => 'index'));
			} else {
				$this->showErrorMessage(__('Erro ao cadastrar recurso'));
			}
		}

		$this->setRooms();
	}

	public function viewResource($resourceId = null) {
		$resource = $this->Resource->findById($resourceId);
		if (!$resource) {
			$this->showErrorMessage(__('Recurso inexistente'));
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
						->arrayFilterIsFixedResource($filteredResources,
								$filter);
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

	public function getAvailableResources() {
				
		$startDatetime = $this->request->data['startDatetime'];
		$endDatetime = $this->request->data['endDatetime'];
				
		$this->RequestHandler->respondAs('json');
		$this->autoRender = false;

		$options['joins'] = array(
				array('table' => 'reservations_resources',
						'alias' => 'ReservationsResource', 'type' => 'LEFT',
						'conditions' => array(
								'ReservationsResource.resource_id = Resource.id')),
				array('table' => 'reservations', 'alias' => 'Reservations',
						'type' => 'LEFT',
						'conditions' => array(
								'Reservations.id = ReservationsResource.reservation_id',
								'Reservations.is_activated = 1'
						)));

		$options['conditions'] = array(
			'or' => array(
				'Resource.room_id !=' => null,
				'and' => array('Reservations.start_time < ' => $endDatetime,
								'Reservations.end_time > ' => $startDatetime)
			));
							 
		$options['fields'] = array('DISTINCT (Resource.id)');
		

		$unavailableResources = $this->Resource->find('all', $options);
		
		$notIn = array();
		foreach($unavailableResources as $unavailableResource) {
			$notIn[] = $unavailableResource['Resource']['id'];
		}
		
		$availableOptions['conditions'] = array('NOT' => array('Resource.id' => $notIn));
		$availableOptions['fields'] = array('Resource.id', 'Resource.name', 'Resource.serial_number');
		
		$availableResources = $this->Resource->find('all', $availableOptions);

		echo json_encode($availableResources);
		exit();
	}
}
