<?php
class Employee extends AppModel {
	public $name = 'Employee';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'));
}
