<?php
class User extends AppModel {
	public $name = 'User';

	public $hasOne = array(
			'Student' => array('className' => 'Student', 'dependent' => 'true'),
			'Professor' => array('className' => 'Professor',
					'dependent' => 'true'),
			'Employee' => array('className' => 'Employee',
					'dependent' => 'true'));

	public function beforeSave() {
		if (isset($this->data['User']['hash']) == false) {
			$this->data['User']['hash'] = substr(
					Security::hash($this->data['User']['nusp'] . time()), 0,
					40);
		}

		if (isset($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password(
					$this->data['User']['password']);
		}
	}
}
