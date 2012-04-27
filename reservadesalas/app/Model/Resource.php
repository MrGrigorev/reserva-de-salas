<?php
class Resource extends AppModel {
	public $name = 'Resource';

	public $belongsTo = array(
			'Room' => array('className' => 'Room', 'foreignKey' => 'room_id'));
}
