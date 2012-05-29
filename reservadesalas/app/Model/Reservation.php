<?php
class Reservation extends AppModel {
	public $name = 'Reservation';

	public $belongsTo = array(
			'Room' => array('className' => 'Room', 'foreignKey' => 'room_id'));

	public $hasAndBelongsToMany = array(
			'Resources' => array('className' => 'Resource',
					'joinTable' => 'reservations_resources',
					'foreignKey' => 'reservation_id',
					'associationForeignKey' => 'resource_id', 'unique' => true));
}
