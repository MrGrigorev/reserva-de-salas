<?php

App::uses('Resource', 'Model');
App::uses('Room', 'Model');

class ResourcesController extends AppController {
	public $name = 'Resources';

	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();

		if (!$this->isLogged()) {
			$this
			->redirect(
					array('controller' => 'Users', 'action' => 'login'));
		}

		$params = $this->params;
		$restrictedActions = array('createResource');
		if (in_array($params['action'], $restrictedActions)) {
			if (!$this->isAdmin()) {
				$this
				->redirect(
						array('controller' => 'Users',
								'action' => 'index'));
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
		$param = json_decode($this->params['data']);

		$this->RequestHandler->respondAs('json');
		$this->autoRender = false;

		$dates = split(',', $param->dates);
		$beginTimes = split(',', $param->beginTimes);
		$endTimes = split(',', $param->endTimes);
		$repetitions = $param->repetitions;
		$untilDate = $param->untilDate;

		$resources = $this->Resource->find('all', array('conditions' => array('Resource.room_id' => null)));

		$returnValues = $this->addDateRepetitions($repetitions, $dates, $beginTimes, $endTimes, $untilDate);

		$beginDatetimes = $returnValues['datetimeBegin'];
		$endDatetimes = $returnValues['datetimeEnd'];

		foreach ($resources as $i => $resource) {
			foreach ($resource['Reservations'] as $reservation) {
				for ($j = 0; $j < count($beginDatetimes); $j++) {
					$beginDatetime = $beginDatetimes[$j]->format('Y-m-d H:i');
					$endDatetime = $endDatetimes[$j]->format('Y-m-d H:i');

					if ($this->isSameTime($beginDatetime, $endDatetime, $reservation['start_time'], $reservation['end_time'])) {
						unset($resources[$i]);

						break;
					}
				}
			}
		}

		echo json_encode($resources);
		exit();
	}

	private function isSameTime($startDatetime1, $endDatetime1, $startDatetime2, $endDatetime2) {
		if ($startDatetime1 >= $startDatetime2 && $startDatetime1 < $endDatetime2)
			return true;

		if ($endDatetime1 > $startDatetime2 && $endDatetime1 <= $endDatetime2)
			return true;

		if ($startDatetime2 >= $startDatetime1 && $startDatetime2 < $endDatetime1)
			return true;

		if ($endDatetime2 > $startDatetime1 && $endDatetime2 <= $endDatetime1)
			return true;

		return false;
	}

	private function addDateRepetitions($repetition, $date, $beginTimes, $endTimes, $untilDate) {
		$datetimeBegin = array();
		$datetimeEnd = array();

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
			$untilDate = DateTime::createFromFormat('d/m/Y', $untilDate);

			if ($repetition == 'none')
				$untilDate = $dateIterator;

			while ($dateIterator <= $untilDate) {
				$datetimeBegin = DateTime::createFromFormat('d/m/Y G:i',
						$dateIterator->format('d/m/Y') . ' ' . $beginTimes[$i]);
				$datetimeEnd = DateTime::createFromFormat('d/m/Y G:i',
						$dateIterator->format('d/m/Y') . ' ' . $endTimes[$i]);

				$datetimeBegins[] = $datetimeBegin;
				$datetimeEnds[] = $datetimeEnd;

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

		return $returnValues;
	}
}
