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
			'capacity' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.')));

	public $hasMany = array(
			'Resources' => array('className' => 'Resource', 'dependent' => true));

	public function isAvailable($roomId, $startDatetime, $endDatetime) {
		$reservation = ClassRegistry::init('Reservation');

		$results = $reservation
				->find('count',
						array(
								'conditions' => array(
										'Reservation.room_id' => $roomId,
										'Reservation.end_time >' => $startDatetime,
										'Reservation.start_time <' => $endDatetime,
										'Reservation.is_activated' => 1)));
		if ($results) {
			return false;
		}
		return true;
	}
}
