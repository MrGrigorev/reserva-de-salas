<h1>Lista de usuários aguandando ativação</h1>
<br />
<table width='80%' align='center'>
	<tr><th>Nome</th><th>Nusp</th><th>Perfil</th><th>Seleção</th></tr>
<?php
	$html = "";
	echo $this->Form->Create('User');
	foreach ($usersWaitingActivation as $userWaitingActivation) {
		$html = "<tr><td>{$userWaitingActivation['User']['name']}</td>";
		$html .= "<td>{$userWaitingActivation['User']['nusp']}</td>";
		$html .= "<td>{$userWaitingActivation['User']['profile']}</td>";
		$html .= "<td>" . $this->Form->Input($userWaitingActivation['User']['id'] . '.isChecked', array('type'=>'checkbox', 'label' => '')) ."</td></tr>";
		echo $this->Form->Input($userWaitingActivation['User']['id'] . '.id', array('type' => 'hidden', 'value' => $userWaitingActivation['User']['id']));
		
		echo $html;
	}
?>
</table>
<table>
<?php
	echo "<tr><td>" . $this->Form->Submit('Ativa', array('name' => 'action')) . "</td>"; 
	echo "<td>" . $this->Form->Submit('Rejeita', array('name' => 'action')) . "</td></tr>";
	
	echo $this->Form->End();
?>
</table>