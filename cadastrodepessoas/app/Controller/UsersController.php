<?php
App::uses('Student', 'Model');
App::uses('Professor', 'Model');
App::uses('Employee', 'Model');

class UsersController extends AppController {
	public $name = 'Users';

	public $components = array('Email');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('createAccount', 'confirmEmail', 'login'));

		$this->Student = ClassRegistry::init('Student');
		$this->Professor = ClassRegistry::init('Professor');
		$this->Employee = ClassRegistry::init('Employee');
	}

	public function index() {
	}

	public function createAccount() {
		if ($this->request->is('post')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Email enviado para validação'));

				switch ($this->request->data['User']['userType']) {
				case 'Professor':
					$this->Professor
							->saveProfile($this->User->id, $this->request->data);
					break;
				case 'Student':
					$this->Student
							->saveProfile($this->User->id, $this->request->data);
					break;
				case 'Employee':
					$this->Professor
							->saveProfile($this->User->id, $this->request->data);
					break;
				default:
					$this->Session
							->setFlash(__('E#2: Erro ao cadastrar perfil'));
				}

				$user = $this->User->findById($this->User->id);

				$this->Email->sendConfirmationEmail($user);

				$this
						->redirect(
								array('controller' => 'Users',
										'action' => 'login'));
			} else {
				$this->Session->setFlash(__('E#1: Erro ao cadastrar conta'));

				unset($this->request->data['User']['password']);
				unset($this->request->data['User']['passwordConfirmation']);
			}
		}
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login())
				$this->redirect($this->Auth->redirect());
			else {
				$this->Session
						->setFlash(__('Número USP e senha não conferem.'));

				unset($this->request->data['User']['password']);
			}
		}
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function confirmEmail($hash) {
		$this->User->order = 'User.id DESC';
		$user = $this->User->findByHash($hash);

		if ($user == null) {
			$this->Session->setFlash(__('E#3: Link de confirmação inválido'));

			$this
					->redirect(
							array('controller' => 'Users', 'action' => 'login'));
		}

		if ($user['User']['activation_status'] == 'waiting_validation') {
			$this->User->id = $user['User']['id'];
			if ($this->User
					->saveField('activation_status', 'waiting_activation')) {
				$this->Session
						->setFlash(
								__(
										'E-mail confirmado. Aguarde ativação pelo administrador.'));
			} else {
				$this->Session->setFlash(__('E#5: Erro ao validar e-mail.'));
			}
		} else {
			$this->Session->setFlash(__('E#4: E-mail já confirmado.'));
		}

		$this->redirect(array('controller' => 'Users', 'action' => 'login'));
	}
}