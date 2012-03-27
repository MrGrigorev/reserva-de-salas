<?php
App::uses('CakeEmail', 'Network/Email');

class EmailComponent extends Component {
	private function instanceCakeMail() {
		return new CakeEmail('gmail');
	}

	public function sendConfirmationEmail($user) {
		$email = $this->instanceCakeMail();

		$email->to($user['User']['email']);
		$email->subject(__('Confirmação de E-mail'));
		$email->template('emailConfirmation');

		$link = 'http://' . $_SERVER['HTTP_HOST'] . '/cadastrodepessoas/Users/emailConfirmation/' .
				$user['User']['hash'];
				
		$email->viewVars(array('name' => $user['User']['name'], 'link' => $link));

		$email->send();
	}
}