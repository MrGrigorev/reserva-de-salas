<?php
App::uses('Student', 'Model');
App::uses('Professor', 'Model');
App::uses('Employee', 'Model');

class UsersController extends AppController {
	public $name = 'Users';

	public $components = array('Email');

	public function beforeFilter() {
		$this->Auth->allow(array('createAccount'));

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
				case 'professor':
					$this->Professor
							->saveProfile($this->User->id, $this->request->data);
					break;
				case 'student':
					$this->Student
							->saveProfile($this->User->id, $this->request->data);
					break;
				case 'employee':
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
			}
		}
	}
}
