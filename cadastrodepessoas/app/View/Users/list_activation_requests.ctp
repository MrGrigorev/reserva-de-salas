<h5>Lista de usuários aguandando ativação</h4>
<br />
<table width='30%' align='center'>
	<tr><th>Nome</th><th>Nusp</th><th>Perfil</th></tr>
<?php
	$html = "";
	echo $this->Form->Create('User');
	foreach ($usersWaitingActivation as $userWaitingActivation) {
		$html = "<tr><td>{$userWaitingActivation['User']['name']}</td>";
		$html .= "<td>{$userWaitingActivation['User']['nusp']}</td>";
		$html .= "<td>{$userWaitingActivation['User']['profile']}</td>";
		$html .= "<td>" . $this->Form->Input($userWaitingActivation['User']['id'] . '.isChecked', array('type'=>'checkbox', 'label' => '')) ."</td></tr>";
		
		echo $html;
	}
?>
</table>
<?php echo $this->Form->End('Ativa'); ?>