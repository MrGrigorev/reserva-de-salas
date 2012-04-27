<?php
class Department extends AppModel {
	public $name = 'Department';

	public $useDbConfig = 'cadastrodepessoas';

	public $hasMany = array(
			'Professors' => array('className' => 'Professor',
					'dependent' => 'false'));
}
