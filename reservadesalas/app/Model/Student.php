<?php
class Student extends AppModel {
	public $name = 'Student';

	public $useDBConfig = 'cadastrodepessoas';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'),
			'Course' => array('className' => 'Course',
					'foreignKey' => 'course_id'));

	public $validate = array(
			'course_id' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => "Não deve ser vazio.")));

	public function saveProfile($id, $data) {
		$data['Student']['user_id'] = $id;

		$this->save($data);
	}
}
