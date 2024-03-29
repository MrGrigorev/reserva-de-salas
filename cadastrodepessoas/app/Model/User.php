<?php
CakePlugin::load('MeioUpload');

class User extends AppModel {
	public $name = 'User';

	public $hasOne = array(
			'Student' => array('className' => 'Student', 'dependent' => 'true'),
			'Professor' => array('className' => 'Professor',
					'dependent' => 'true'),
			'Employee' => array('className' => 'Employee',
					'dependent' => 'true'));

	public $validate = array(
			'nusp' => array(
					'not empty' => array('rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'between' => array('required' => 'create',
							'rule' => array('between', 2, 10),
							'message' => 'O Número USP deve ser entre 2 e 10 dígitos.'),
					'numeric' => array('rule' => 'numeric',
							'message' => 'Só números são permitidos.'),
					'is unique' => array('rule' => 'isUnique',
							'message' => 'Esse Número USP já está cadastrado.')),
			'name' => array(
					'not empty' => array('rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'valid name' => array('rule' => array('between', 1, 70),
							'message' => 'O nome deve ter entre 1 e 70 caracteres.')),
			'email' => array(
					'not empty' => array('rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'valid email format' => array('rule' => 'email',
							'message' => 'Formato de e-mail inválido.'),
					'is unique' => array('rule' => 'isUnique',
							'message' => 'Esse E-mail já está cadastrado.')),
			'password' => array(
					'not empty' => array('rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'between' => array('rule' => array('between', 6, 12),
							'message' => 'A senha deve ter entre 6 e 12 caracteres.')),
			'passwordConfirmation' => array(
					'not empty' => array('rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'confirmation' => array('rule' => 'comparePasswords',
							'required' => 'create',
							'message' => 'Senhas não correspondem.')),
			'currentPassword' => array(
					'not empty' => array('rule' => 'notEmpty',
							'message' => "Não deve ser vazio.")),
			'profile' => array(
					'not empty' => array('rule' => 'notEmpty',
							'message' => "Não deve ser vazio."),
					'validate profile' => array(
							'rule' => 'validateProfile', 'message' => '')),
			'webpage' => array(
					'between' => array('allowEmpty' => true,
							'rule' => array('between', 0, 100),
							'message' => 'A página web pode ter no máximo 100 caracteres.'),
					'url' => array('allowEmpty' => true, 'rule' => 'url',
							'message' => 'Formato de URL inválido.')),
			'lattes' => array(
					'between' => array('allowEmpty' => true,
							'rule' => array('between', 0, 100),
							'message' => 'O currículo lattes pode ter no máximo 100 caracteres.'),
					'url' => array('allowEmpty' => true, 'rule' => 'url',
							'message' => 'Formato de URL inválido.')),
			'activation_status' => array('rule' => 'notEmpty',
					'message' => 'Não deve ser vazio.'),
			'user_type' => array('rule' => 'notEmpty',
					'message' => 'Não deve ser vazio.'));

	function validateProfile() {
		switch ($this->data['User']['profile']) {
			case 'Professor':
				$this->Professor->set($this->data['Professor']);
				return $this->Professor->validates();
				break;
			case 'Student':
				$this->Student->set($this->data['Student']);
				return $this->Student->validates();
			case 'Employee':
				$this->Employee->set($this->data['Employee']);
				return $this->Employee->validates();
			default:
				return false;
		}
	}

	function comparePasswords() {
		return $this->data['User']['password']
		== $this->data['User']['passwordConfirmation'];
	}

	public $actsAs = array(
			'MeioUpload.MeioUpload' => array(
					'photo' => array('dir' => 'photos',
							'create_directory' => true, 'maxSize' => 2097152,
							'allowed_mime' => array('image/jpeg',
									'image/pjpeg', 'image/png'),
							'allowed_ext' => array('.jpg', '.jpeg', '.png'),
							'thumbsizes' => array(
									'small' => array('width' => 120,
											'height' => 120)),
							'default' => 'default.jpg')));

	public function beforeSave() {
		if (isset($this->data['User']['password'])) {
			if (isset($this->data['User']['hash']) == false) {
				$this->data['User']['hash'] = substr(
						Security::hash($this->data['User']['nusp'] . time()),
						0, 40);
			}
			$this->data['User']['password'] = AuthComponent::password(
					$this->data['User']['password']);
		}

		if (isset($this->data['User']['webpage'])
				&& $this->data['User']['webpage'] != '') {
			$webpageAddress = $this->data['User']['webpage'];

			if (!preg_match('/^http:\/\//', $webpageAddress)
					&& !preg_match('/^https:\/\//', $webpageAddress)) {
				$webpageAddress = "http://" . $webpageAddress;
			}

			$this->data['User']['webpage'] = $webpageAddress;
		}

		if (isset($this->data['User']['lattes'])
				&& $this->data['User']['lattes'] != '') {
			$lattesAddress = $this->data['User']['lattes'];

			if (!preg_match('/^http:\/\//', $lattesAddress)
					&& !preg_match('/^https:\/\//', $lattesAddress)) {
				$lattesAddress = "http://" . $lattesAddress;
			}

			$this->data['User']['lattes'] = $lattesAddress;
		}
	}

	public function profile($user) {
		$myprofile = 'Student';
		if (isset($user['Professor']['id'])) {
			$myprofile = 'Professor';
		} else if (isset($user['Employee']['id'])) {
			$myprofile = 'Employee';
		}
		return $myprofile;
	}
}
