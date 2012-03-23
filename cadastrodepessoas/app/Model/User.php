<?php
class User extends AppModel {
	public $name = 'User';

	public $hasOne = array('Student' => array('className' => 'Student', 'dependent' => 'true'),
					'Professor' => array('className' => 'Professor', 'dependent' => 'true'),
					'Employee' => array('className' => 'Employee', 'dependent' => 'true'));
}