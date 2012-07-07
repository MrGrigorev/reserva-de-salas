<?php
App::uses('Reservation', 'Model');

class Room extends AppModel {
	public $name = 'Room';

	public $belongsTo = array(
			'Building' => array('className' => 'Building',
					'foreignKey' => 'building_id'));

	public $validate = array(
			'floor' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.')),
			'room_type' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.')),
			'name' => array(
					 'name number' => array('required' => true,
							'rule' => 'notBothEmpty',
							'message' => 'Nome e número não devem ser ambos vazios.')),
			'number' => array(
					'number name' => array('required' => true,
							'rule' => 'notBothEmpty',
							'message' => 'Nome e número não devem ser ambos vazios.')),
			'capacity' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.')));

	public $hasMany = array(
			'Resources' => array('className' => 'Resource', 'dependent' => true));

	public function notBothEmpty() {
		if (!$this->data['Room']['name'] && !$this->data['Room']['number']) {
			return false;
		}
		return true;
	}
	
	public function isAvailable($roomId, $startDatetime, $endDatetime) {
		$reservation = ClassRegistry::init('Reservation');
		
		if (isset($startDatetime->date))
			$startDatetime = $startDatetime->date;
		
		if (isset($endDatetime->date))
			$endDatetime = $endDatetime->date;

		$results = $reservation
				->find('count',
						array(
								'conditions' => array(
										'Reservation.room_id' => $roomId,
										'Reservation.end_time >' => $startDatetime,
										'Reservation.start_time <' => $endDatetime,
										'Reservation.is_activated' => true)));
		if ($results) {
			return false;
		}
		return true;
	}
}
