<?php
class Employee extends AppModel {
	public $name = 'Employee';

	public $useDBConfig = 'cadastrodepessoas';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'));

	public $validate = array(
			'occupation' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'between' => array('required' => true,
							'rule' => array('between', 1, 70),
							'message' => 'O cargo deve ter entre 1 e 70 caracteres.')),
			'telephone' => array(
					'number' => array('rule' => 'numeric',
							'message' => 'Apenas números.',
							'allowEmpty' => true),
					'max length' => array('rule' => array('between', 1, 9),
							'message' => 'Até 9 dígitos.',
							'allowEmpty' => true)),
			'room' => array(
					'max length' => array('rule' => array('between', 1, 10),
							'message' => 'Até 10 caracteres.',
							'allowEmpty' => true)));

	public function saveProfile($id, $data) {
		$data['Employee']['user_id'] = $id;

		$this->save($data);
	}
}
