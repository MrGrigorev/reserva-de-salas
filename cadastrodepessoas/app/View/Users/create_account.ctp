<h1>Criar conta</h1>

<?php
	echo $this->Form->Create('User');
	echo $this->Form->Input('nusp', array('label' => 'Número USP'));
	echo $this->Form->Input('name', array('label' => 'Nome'));
	echo $this->Form->Input('email', array('label' => 'E-mail'));
	echo $this->Form->Input('password', array('label' => 'Senha'));
	echo $this->Form->Input('passwordConfirmation', array('label' => 'Confirmação da senha'));
	echo $this->Form->Input('photo', array('label' => 'Foto', 'type' => 'file'));
	echo $this->Form->Input('userType', array('label' => 'Tipo de usuário'));
	
	
	
	echo $this->Form->End('Criar conta');
?>