<h1><?php __('Lista de usuários aguandando ativação'); ?></h1>
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
	$linkName = $this->Html->link(__('Nome'), array('controller' => 'Users', 'action' => 'listActivationRequests', $parameter));
	
	$parameter = orderParameter('nusp', $actualOrder);
	$linkNusp = $this->Html->link(__('Número USP'), array('controller' => 'Users', 'action' => 'listActivationRequests', $parameter));

	echo $this->Html->tableHeaders(array($linkName, $linkNusp, __('Perfil'), __('Seleção')));

	echo $this->Form->Create('User');
	
	$cells = array();
	foreach ($usersWaitingActivation as $userWaitingActivation) {
		$cells[] = array($userWaitingActivation['User']['name'],
						 $userWaitingActivation['User']['nusp'],
						 $userWaitingActivation['User']['profile'],
						 $this->Form->Input($userWaitingActivation['User']['id'] . '.isChecked', array('type'=>'checkbox', 'label' => '')));
	}

	echo $this->Html->tableCells($cells);
?>
</table>

<table>
<?php
	echo "<tr><td>" . $this->Form->Submit(__('Ativa'), array('name' => 'action')) . "</td>"; 
	echo "<td>" . $this->Form->Submit(__('Rejeita'), array('name' => 'action')) . "</td></tr>";

	echo $this->Form->End();
?>
</table>