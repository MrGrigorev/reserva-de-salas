<?php
class Student extends AppModel {
	public $name = 'Student';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'));

	public $validate = array(
			'course' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'between' => array('required' => true,
							'rule' => array('between', 1, 100),
							'message' => 'O curso deve ter entre 1 e 100 caracteres.')));

	public function saveProfile($id, $data) {
		$data['Student']['user_id'] = $id;

		$this->save($data);
	}
}
