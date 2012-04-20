<?php
App::uses('Student', 'Model');
App::uses('Professor', 'Model');
App::uses('Employee', 'Model');
App::uses('Department', 'Model');
App::uses('Course', 'Model');
App::uses('ProfessorCategory', 'Model');

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
		$this->ProfessorCategory = ClassRegistry::init('ProfessorCategory');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		$params = $this->params;

		if ($params['action'] == 'listActivationRequests') {
			if ($user['user_type'] == 'user')
				return false;
		}

		if ($params['action'] == 'listUsers') {
			if ($user['user_type'] == 'user')
				return false;
		}

		if ($params['action'] == 'adminEdit') {
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

				$this
						->saveProfile($this->request->data,
								$this->request->data['User']['profile'],
								$this->User->id);

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

		$this->setCoursesAndDepartments();
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

	public function editProfile() {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->saveProfile($this->request->data);

				$this->Session->setFlash(__('Dados atualizados.'));

				$this
						->redirect(
								array('controller' => 'Users',
										'action' => 'index'));
			} else {
				$this->Session
						->setFlash(
								__('E#6: Não foi possível atualizar os dados.'));
			}
		}

		$user = array();
		$user = $this->getLoggedUser();
		$user = $this->User->findById($user['id']);
		$this->request->data = $user;

		$this->set('profile', $this->User->profile($user));

		$this->setCoursesAndDepartments();
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
				foreach ($this->request->data['User'] as $id => $user)
					if ($user['isChecked'])
						$this->activateAccount($id);
			} else if ($this->request->data['action'] == 'Rejeita') {
				foreach ($this->request->data['User'] as $id => $user)
					if ($user['isChecked'])
						$this->rejectAccount($id);
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
	
	private function containsCaseInsensitive($value, $filter) {
		$valueLowerCase = strtolower($value);
		$filterLowerCase = strtolower($filter);
		
		$position = strpos($valueLowerCase, $filterLowerCase);
		
		if($position === false)
			return false;
			
		return true;
	}
	
	private function arrayFilter($users, $key, $filter) {
		if($key != 'profile' && $key != 'activation_status') {
			if ($filter == '')
				return $users;			
		}
		else if($filter == 'all')
			return $users;
			
		foreach ($users as $i => $user) {
			if ($this->containsCaseInsensitive($user['User'][$key], $filter) == false) {
				unset($users[$i]);
			}
		}
		
		return $users;
	}
	
	private function filterUsers($users) {
		$filteredUsers = $users;
		
		foreach ($this->request->data['User'] as $key => $filter) {
			$filteredUsers = $this->arrayFilter($filteredUsers, $key, $filter);
		}
		
		return $filteredUsers;
	}

	public function listUsers($order = 'User.name ASC', $profileOrder = null) {
		$users = $this->User->order = $order;
		$users = $this->User->find('all');

		for ($i = 0; $i < count($users); $i++) {
			$users[$i]['User']['profile'] = $this->User->profile($users[$i]);
		}

		if ($profileOrder != null)
			array_multisort(array_map($this->getProfile, $users), $users);

		if ($profileOrder == 'DESC')
			$users = array_reverse($users);
			
		if($this->request->is('post')) {
			$users = $this->filterUsers($users);
		}

		$this->set('users', $users);
		$this->set('actualOrder', $order);
		$this->set('profileOrder', $profileOrder);
	}

	public function viewProfile($userId = null) {
		if ($userId == null) {
			$user = $this->getLoggedUser();
			$userId = $user['id'];
		}

		$user = $this->User->findById($userId);
		$user['User']['profile'] = $this->User->profile($user);
		if ($user['User']['profile'] == 'Student') {
			$course = $this->Course->findById($user['Student']['course_id']);
			$user['User']['subProfile'] = $course['Course']['name'];
		}

		if ($user['User']['profile'] == 'Professor') {
			$department = $this->Department
					->findById($user['Professor']['department_id']);
			$user['User']['subProfile'] = $department['Department']['name'];
		}

		if ($user['User']['profile'] == 'Employee') {
			$user['User']['subProfile'] = $user['Employee']['occupation'];
		}

		$this->set('user', $user);
	}

	public function adminEdit($userId = null) {
		if ($userId == null) {
			$this
					->redirect(
							array('controller' => 'Users', 'action' => 'index'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$user = $this->User->findById($userId);
			$userProfile = $this->User->profile($user);

			if ($this->User->save($this->request->data)) {
				if ($userProfile != $this->request->data['User']['profile'])
					$this->deleteProfile($user);

				$this
						->saveProfile($this->request->data,
								$this->request->data['User']['profile'],
								$userId);

				$this->Session->setFlash(__('Dados atualizados.'));

				$this
						->redirect(
								array('controller' => 'Users',
										'action' => 'viewProfile', $userId));
			} else {
				$this->Session
						->setFlash(
								__('E#7: Não foi possível atualizar o usuário.'));
			}
		}

		$user = $this->User->findById($userId);
		$user['User']['profile'] = $this->User->profile($user);

		$this->request->data = $user;

		$this->setCoursesAndDepartments();
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

	private function setCoursesAndDepartments() {
		$this->Department->order = 'Department.name ASC';
		$this->Course->order = 'Course.name ASC';
		$this->ProfessorCategory->order = 'ProfessorCategory.name ASC';

		$this->set('departments', $this->Department->find('all'));
		$this->set('courses', $this->Course->find('all'));
		$this->set('categories', $this->ProfessorCategory->find('all'));
		
	}

	private function getProfile($user) {
		return $user['User']['profile'];
	}

	private function saveProfile($user, $profile = null, $userId = null) {
		if ($profile == null)
			$profile = $this->User->profile($user);

		if ($userId == null)
			$userId = $user['User']['id'];

		switch ($profile) {
		case 'Professor':
			$this->Professor->saveProfile($userId, $user);
			break;
		case 'Student':
			$this->Student->saveProfile($userId, $user);
			break;
		case 'Employee':
			$this->Employee->saveProfile($userId, $user);
			break;
		default:
			$this->Session->setFlash(__('E#2: Erro ao cadastrar perfil'));
		}
	}

	private function deleteProfile($user) {
		$profile = $this->User->profile($user);

		switch ($profile) {
		case 'Professor':
			$this->Professor->delete($user['Professor']['id']);
			break;
		case 'Student':
			$this->Student->delete($user['Student']['id']);
			break;
		case 'Employee':
			$this->Employee->delete($user['Employee']['id']);
			break;
		}
	}
}
