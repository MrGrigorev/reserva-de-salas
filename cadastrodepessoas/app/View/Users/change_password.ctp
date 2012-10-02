<?php echo $this->Html->css('Users/users'); ?>

<h1><?php echo __('Trocar senha'); ?></h1>

<?php
echo $this->Form->Create('User');
echo $this->Form->Input('currentPassword', array('label' => __('Senha atual'), 'type' => 'password'));
echo $this->Form->Input('password', array('label' => __('Nova senha'), 'type' => 'password'));
echo $this->Form->Input('passwordConfirmation', array('label' => __('Repetir senha'), 'type' => 'password'));

echo $this->Form->End(__('Confirmar'));
?>