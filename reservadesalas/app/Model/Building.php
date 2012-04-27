<?php
class Building extends AppModel {
	public $name = 'Building';

	public $hasMany = array(
			'Rooms' => array('className' => 'Room', 'dependent' => true));
}
