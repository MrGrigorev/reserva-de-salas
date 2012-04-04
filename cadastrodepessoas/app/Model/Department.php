<?php
class Department extends AppModel {
	public $name = 'Department';

	public $hasMany = array(
			'Professors' => array('className' => 'Professor',
					'dependent' => 'false'));
}
