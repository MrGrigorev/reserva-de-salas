<?php
class Room extends AppModel {
	public $name = 'Room';

	public $belongsTo = array(
			'Building' => array('className' => 'Building',
					'foreignKey' => 'building_id'));

	public $hasMany = array(
			'Resources' => array('className' => 'Resource', 'dependent' => true));
}
