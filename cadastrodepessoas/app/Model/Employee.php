<?php
class Employee extends AppModel {
	public $name = 'Employee';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'));

	public $validate = array(
			'occupation' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'between' => array('required' => true,
							'rule' => array('between', 1, 70),
							'message' => 'O cargo deve ter entre 1 e 70 caracteres.')));

	public function saveProfile($id, $data) {
		$data['Employee']['user_id'] = $id;

		$this->save($data);
	}
}
