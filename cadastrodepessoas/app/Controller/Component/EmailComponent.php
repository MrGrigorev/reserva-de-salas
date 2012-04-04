<?php
App::uses('CakeEmail', 'Network/Email');

class EmailComponent extends Component {
	private function instanceCakeMail() {
		return new CakeEmail('gmail');
	}

	public function sendConfirmationEmail($user) {
		$link = 'http://' . $_SERVER['HTTP_HOST']
				. '/cadastrodepessoas/Users/confirmEmail/'
				. $user['User']['hash'];

		$this
				->sendEmail(__('Confirmação de E-mail'), $user,
						'emailConfirmation', $link);
	}

	public function sendActivationReport($user) {
		$link = 'http://' . $_SERVER['HTTP_HOST']
				. '/cadastrodepessoas/Users/login';

		$this
				->sendEmail(__('Aprovação de Cadastro'), $user,
						'activationReport', $link);
	}

	public function sendRejectionReport($user) {
		$this->sendEmail(__('Rejeição de Cadastro'), $user, 'rejectionReport');
	}

	private function sendEmail($subject, $user, $template, $link = null) {
		$email = $this->instanceCakeMail();

		$email->to($user['User']['email']);
		$email->subject($subject);
		$email->template($template);

		$email
				->viewVars(
						array('name' => $user['User']['name'], 'link' => $link));

		$email->send();
	}
}
