<?php
class Professor extends AppModel {
	public $name = 'Professor';

	public $belongsTo = array(
			'User' => array('className' => 'User', 'foreignKey' => 'user_id'));

	public $validate = array(
			'department' => array(
					'not empty' => array('required' => true,
							'rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'between' => array('required' => true,
							'rule' => array('between', 1, 50),
							'message' => 'O departamento deve ter entre 1 e 50 caracteres.')),
			'webpage' => array(
					'between' => array('required' => true,
							'rule' => array('between', 0, 100),
							'message' => 'A página web pode ter no máximo 100 caracteres.'),
					'url' => array('allowEmpty' => true, 'required' => true, 'rule' => 'url',
							'message' => 'Formato de URL inválido.')),
			'lattes' => array(
					'between' => array('required' => true,
							'rule' => array('between', 0, 100),
							'message' => 'O currículo lattes pode ter no máximo 100 caracteres.'),
					'url' => array('allowEmpty' => true, 'required' => true, 'rule' => 'url',
							'message' => 'Formato de URL inválido.')));

	public function saveProfile($id, $data) {
		$data['Professor']['user_id'] = $id;

		$this->save($data);
	}
}
