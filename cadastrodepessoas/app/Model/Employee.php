<?php
class Employee extends AppModel {
	public $name = 'Employee';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'));

	public function saveProfile($id, $data) {
		$data['Employee']['user_id'] = $id;

		$this->save($data);
	}
}
