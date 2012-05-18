<?php
	echo $this->Html->css('Rooms/rooms');
	echo $this->Html->css('Users/users');
?>

<h1><?php echo __('Login'); ?></h1>

<?php 
	echo $this->Form->Create('User');
	echo $this->Form->Input('nusp', array('label' => __('Número USP')));
	echo $this->Form->Input('password', array('label' => __('Senha')));

	echo $this->Form->End(__('Login'));
?>