<?php
	echo $this->Html->script('create_account'); 
	echo $this->Html->css('Users/users');
?>

<h1>Login</h1>

<?php 
	echo $this->Form->Create('User');
	echo $this->Form->Input('nusp', array('label' => 'Número USP'));
	echo $this->Form->Input('password', array('label' => 'Senha'));
	
	echo $this->Form->End('Login');
?>