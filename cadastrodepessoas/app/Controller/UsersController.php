<?php
class UsersController extends AppController {
	public $name = 'Users';

	public function beforeFilter() {
		$this->Auth->allow(array('createAccount'));
	}

	public function createAccount() {
		if($this->request->is('post')) {
			if($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Email enviado para validação'));

				$user = $this->User->findById($this->User->id);

				// send email to admin
				//mail($user);

				$this->redirect(array('controller' => 'Users', 'action' => 'login'));
			}
			else {
				$this->Session->setFlash(__('Erro ao cadastrar conta'));
			}
		}
	}
}