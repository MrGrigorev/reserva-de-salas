<?php
class Resource extends AppModel {
	public $name = 'Resource';

	public $belongsTo = array(
			'Room' => array('className' => 'Room', 'foreignKey' => 'room_id'));

	public $hasAndBelongsToMany = array(
			'Reservations' => array('className' => 'Reservation',
					'joinTable' => 'reservations_resources',
					'foreignKey' => 'resource_id',
					'associationForeignKey' => 'reservation_id', 'unique' => true));

	public $validate = array(
			'name' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.')),
			'serial_number' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.'),
					'is unique' => array('required' => true,
							'rule' => 'isUnique',
							'message' => 'Número de série já cadastrado.')));
}
