<?php

App::uses('Admin', 'Model');

class UsersController extends AppController {
	public $name = 'Users';
	
	public function beforeFilter() {
		parent::beforeFilter();
		
		$this->Admin = ClassRegistry::init('Admin');
	}

	public function login() {
		if ($this->request->is('post')) {
			$nusp = $this->request->data['User']['nusp'];
			$password = $this->request->data['User']['password'];
			
			$url = "http://localhost/cadastrodepessoas/Users/loginService";
			$data = array('nusp' => $nusp, 'password' => $password);
			
			$ch = curl_init($url);
			 
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			 
			$response = curl_exec($ch);
			
			if ($response) {
				$response = json_decode($response);
				
				if ($response->nusp > 0) {
					$user = array(
						'nusp' => $response->nusp,
						'name' => $response->name,
						'occupation' => $response->occupation,
						'isAdmin' => false
					);
					
					$admin = $this->Admin->findByNusp($user['nusp']);
					if ($admin) {
						$user['isAdmin'] = true;
					}
					
					$this->Session->write('user', $user);
					$this->redirect(array('controller' => 'Users', 'action' => 'index'));
				}
				else {
					$this->showErrorMessage($response->error);
				}
			}
			else {
				$this->showErrorMessage(__('Erro na comunicação com o sistema de cadastro de pessoas'));
			}
			
		}
	}
	
	public function authorize($userId, $name, $userType) {
		
	}

	public function logout() {
		$this->Session->destroy();
		$this->redirect(array('controller' => 'Users', 'action' => 'login'));
	}

	public function index() {
		if (!$this->isLogged()) {
			$this->redirect(array('controller' => 'Users', 'action' => 'login'));
		}
	}
}
