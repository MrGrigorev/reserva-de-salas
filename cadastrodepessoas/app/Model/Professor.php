<?php
class Professor extends AppModel {
	public $name = 'Professor';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'));

	public function saveProfile($id, $data) {
		$data['Professor']['user_id'] = $id;

		$this->save($data);
	}
}
