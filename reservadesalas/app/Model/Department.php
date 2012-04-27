<?php
class Department extends AppModel {
	public $name = 'Department';

	public $useDBConfig = 'cadastrodepessoas';

	public $hasMany = array(
			'Professors' => array('className' => 'Professor',
					'dependent' => 'false'));
}
