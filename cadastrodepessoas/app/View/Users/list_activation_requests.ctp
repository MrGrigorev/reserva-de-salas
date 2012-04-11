<?php
	echo $this->Html->script('list_activation_requests');  
?>
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
	
	$parameter = 'ASC';
	if ($parameter == $profileOrder)
		$parameter = 'DESC';
	$linkProfile = $this->Html->link(__('Perfil'), array('controller' => 'Users', 'action' => 'listActivationRequests', 'User.name ASC', $parameter));

	echo $this->Html->tableHeaders(array($this->Form->Input('selectAll', array('type' => 'checkbox', 'label' => '', 'class' => 'selectAll') ), $linkName, $linkNusp, $linkProfile));

	echo $this->Form->Create('User', array('class' => 'submittableForm'));
	
	$cells = array();
	foreach ($usersWaitingActivation as $userWaitingActivation) {
		switch ($userWaitingActivation['User']['profile']) {
			case 'Student': $userWaitingActivation['User']['profile'] = __('Estudante');
							break;
			case 'Professor': $userWaitingActivation['User']['profile'] = __('Funcionário Docente');
							break;
			case 'Employee': $userWaitingActivation['User']['profile'] = __('Funcionário Não-docente');
							break;
		}
		
		$cells[] = array($this->Form->Input($userWaitingActivation['User']['id'] . '.isChecked', array('type'=>'checkbox', 'label' => '', 'class' => 'selectableBySelectAll')),
						 $userWaitingActivation['User']['name'],
						 $userWaitingActivation['User']['nusp'],
						 $userWaitingActivation['User']['profile']
						 );
	}

	echo $this->Html->tableCells($cells);
?>
</table>

<div id="dialog-confirm" style="display:none"></div>

<table>
<?php
	echo "<tr><td>" . $this->Form->Submit(__('Ativa'), array('class' => 'needConfirmation')) . "</td>"; 
	echo "<td>" . $this->Form->Submit(__('Rejeita'), array('class' => 'needConfirmation')) . "</td>";
	echo "<td>" . $this->Form->Input('action', array('type' => 'hidden', 'name' => 'action')) . "</td></tr>";

	echo $this->Form->End();
?>
</table>