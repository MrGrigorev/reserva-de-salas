<h1><?php echo __('Lista de usuários'); ?></h1>
<br />

<table id="usersTable">
<?php
	function orderParameter($attribute, $actualOrder) {
		$parameter = 'User.' . $attribute . ' ASC';

		if ($parameter != $actualOrder)
			return $parameter;
		
		return 'User.' . $attribute . ' DESC'; 
	}

	$parameter = orderParameter('name', $actualOrder);
	$linkName = $this->Html->link(__('Nome'), array('controller' => 'Users', 'action' => 'listUsers', $parameter));
	
	$parameter = orderParameter('nusp', $actualOrder);
	$linkNusp = $this->Html->link(__('Número USP'), array('controller' => 'Users', 'action' => 'listUsers', $parameter));
	
	$parameter = orderParameter('email', $actualOrder);
	$linkEmail = $this->Html->link(__('E-Mail'), array('controller' => 'Users', 'action' => 'listUsers', $parameter));
	
	$parameter = 'ASC';
	if ($parameter == $profileOrder)
		$parameter = 'DESC';
	$linkProfile = $this->Html->link(__('Perfil'), array('controller' => 'Users', 'action' => 'listUsers', 'User.name ASC', $parameter));
	
	$parameter = orderParameter('activation_status', $actualOrder);
	$linkStatus = $this->Html->link(__('Status'), array('controller' => 'Users', 'action' => 'listUsers', $parameter));

	echo $this->Html->tableHeaders(array($linkName, $linkNusp, $linkEmail, $linkProfile, $linkStatus));

	$cells = array();
	foreach ($users as $user) {
		switch ($user['User']['profile']) {
			case 'Student': $user['User']['profile'] = __('Estudante');
							break;
			case 'Professor': $user['User']['profile'] = __('Funcionário Docente');
							break;
			case 'Employee': $user['User']['profile'] = __('Funcionário Não-docente');
							break;
		}
		switch ($user['User']['activation_status']) {
			case 'active': $user['User']['activation_status'] = __('Ativo');
							break;
			case 'waiting_validation': $user['User']['activation_status'] = __('Esperando validação');
							break;
			case 'waiting_activation': $user['User']['activation_status'] = __('Esperando ativação');
							break;
		}
		
		$cells[] = array($user['User']['name'], $user['User']['nusp'], $user['User']['email'], $user['User']['profile'], $user['User']['activation_status']);
	}

	echo $this->Html->tableCells($cells);
?>
</table>