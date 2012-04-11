<?php
App::uses('Student', 'Model');
App::uses('Professor', 'Model');
App::uses('Employee', 'Model');
App::uses('Department', 'Model');
App::uses('Course', 'Model');

class UsersController extends AppController {
	public $name = 'Users';

	public $components = array('Email');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('createAccount', 'confirmEmail', 'login'));

		$this->Student = ClassRegistry::init('Student');
		$this->Professor = ClassRegistry::init('Professor');
		$this->Employee = ClassRegistry::init('Employee');
		$this->Department = ClassRegistry::init('Department');
		$this->Course = ClassRegistry::init('Course');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		$params = $this->params;

		if ($params['action'] == 'listActivationRequests') {
			if ($user['user_type'] == 'user')
				return false;
		}

		return true;
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
					$this->Employee
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

		$this->Department->order = 'Department.name ASC';
		$this->Course->order = 'Course.name ASC';

		$this->set('departments', $this->Department->find('all'));
		$this->set('courses', $this->Course->find('all'));
	}

	public function login() {
		if ($this->Auth->loggedIn())
			$this
					->redirect(
							array('controller' => 'Users', 'action' => 'index'));

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$user = $this->getLoggedUser();

				switch ($user['activation_status']) {
				case 'active':
					$this->redirect($this->Auth->redirect());
					break;
				case 'waiting_validation':
					$link = Router::url(
							array('controller' => 'Users',
									'action' => 'resendConfirmationEmail',
									$user['id']));

					$this->Session
							->setFlash(
									sprintf(
											__(
													'Seu e-mail não foi confirmado. <a href="%s">Clique aqui para reenviar o e-mail de confirmação</a>.'),
											$link));

					$this->logout();
					break;
				case 'waiting_activation':
					$this->Session
							->setFlash(
									__(
											'Por favor aguarde ativação pelo administrador.'));

					$this->logout();
					break;
				}
			} else {
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

	public function resendConfirmationEmail($userId) {
		$user = $this->User->findById($userId);

		$this->Email->sendConfirmationEmail($user);

		$this->Session->setFlash(__('E-mail reenviado.'));

		$this->redirect(array('controller' => 'Users', 'action' => 'login'));
	}

	public function listActivationRequests($order = 'User.name ASC',
			$profileOrder = null) {
		if ($this->request->is('post')) {
			if ($this->request->data['action'] == 'Ativa') {
				foreach ($this->request->data['User'] as $user)
					if ($user['isChecked'])
						$this->activateAccount($user['id']);
			} else if ($this->request->data['action'] == 'Rejeita') {
				foreach ($this->request->data['User'] as $user)
					if ($user['isChecked'])
						$this->rejectAccount($user['id']);
			}
		}

		$users = $this->User->order = $order;
		$users = $this->User
				->find('all',
						array(
								'conditions' => array(
										'activation_status' => 'waiting_activation')));

		for ($i = 0; $i < count($users); $i++) {
			$users[$i]['User']['profile'] = $this->User->profile($users[$i]);
		}

		if ($profileOrder != null)
			array_multisort(array_map($this->getProfile, $users), $users);

		if ($profileOrder == 'DESC')
			$users = array_reverse($users);

		$this->set('usersWaitingActivation', $users);
		$this->set('actualOrder', $order);
		$this->set('profileOrder', $profileOrder);
	}

	private function activateAccount($userId) {
		$this->User->id = $userId;

		if ($this->User->exists() == false)
			return;

		$this->Email->sendActivationReport($this->User->findById($userId));

		$this->User->saveField('activation_status', 'active');
	}

	private function rejectAccount($userId) {
		$this->User->id = $userId;

		if ($this->User->exists() == false)
			return;

		$this->Email->sendRejectionReport($this->User->findById($userId));

		$this->User->delete($userId);
	}

	private function getProfile($user) {
		return $user['User']['profile'];
	}
}
