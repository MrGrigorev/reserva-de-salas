<?php
class Course extends AppModel {
	public $name = 'Course';

	public $useDbConfig = 'cadastrodepessoas';

	public $hasMany = array(
			'Students' => array('className' => 'Student',
					'dependent' => 'false'));
}
