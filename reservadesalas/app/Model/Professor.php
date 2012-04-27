<?php
class Professor extends AppModel {
	public $name = 'Professor';

	public $useDbConfig = 'cadastrodepessoas';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'),
			'Department' => array('className' => 'Department',
					'foreignKey' => 'department_id'),
			'Category' => array('className' => 'ProfessorCategory',
					'foreignKey' => 'professor_category_id'));

	public $validate = array(
			'department_id' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.')),
			'professor_category_id' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => 'Não deve ser vazio.')),
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
		$data['Professor']['user_id'] = $id;

		$this->save($data);
	}
}
