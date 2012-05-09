<?php
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
							'message' => 'Não deve ser vazio.'))
					
			);

	public $hasMany = array(
			'Resources' => array('className' => 'Resource', 'dependent' => true));
}
