<?php
class UsersController extends AppController {
	public $name = 'Users';

	public function login() {
		if ($this->Auth->loggedIn())
			$this
					->redirect(
							array('controller' => 'Users', 'action' => 'index'));

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$user = $this->getLoggedUser();

				if ($user['activation_status'] != 'active') {
					$this->Session->setFlash(__('Cadastro não ativado.'));
					$this->logout();
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

	public function index() {

	}
}
