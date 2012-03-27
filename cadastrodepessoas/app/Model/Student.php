<?php
class Student extends AppModel {
	public $name = 'Student';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'));
}
