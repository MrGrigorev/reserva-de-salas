<?php
class Professor extends AppModel {
	public $name = 'Professor';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'));
}
