<?php
class Resource extends AppModel {
	public $name = 'Resource';

	public $belongsTo = array(
			'Room' => array('className' => 'Room', 'foreignKey' => 'room_id'));
	
	public $validate = array(
			'name' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.')),
			'serial_number' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.')));
}
