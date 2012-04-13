<?php include_once 'sharedFunctions.php'; ?>

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

	$header = array(
		array(
			array($linkName, array('class' => 'header')),
			array($linkNusp, array('class' => 'header')),
			array($linkEmail, array('class' => 'header')),
			array($linkProfile, array('class' => 'header')),
			array($linkStatus, array('class' => 'header'))
		)
	);
	echo $this->Html->tableCells($header);

	$cells = array();
	foreach ($users as $user) {
		$user['User']['profile'] = getTranslatedProfile($user);
		
		$user['User']['activation_status'] = getTranslatedStatus($user);
		
		$linkView = $this->Html->link($user['User']['name'], array('controller' => 'Users', 'action' => 'viewProfile', $user['User']['id']));
		
		$cells[] = array($linkView, $user['User']['nusp'], $user['User']['email'], $user['User']['profile'], $user['User']['activation_status']);
	}

	echo $this->Html->tableCells($cells);
?>
</table>