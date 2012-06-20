<h1><?php echo __('Página Inicial'); ?></h1>

<?php
	$greeting = "Olá";
	$welcomeMessage = "Seja bem-vindo ao sistema de Reserva de Salas do IME-USP.";
	echo $greeting . ", " . $loggedUser['name'] . ".";
?>
<br /><br />
<?php
	echo $welcomeMessage;
?>
<br /><br />
<?php
	
	echo $this->Html->link(__('Reservar Sala'), array('controller' => 'Reservations', 'action' => 'chooseDate'));
	echo "<br />";
	echo $this->Html->link(__('Visualizar salas'), array('controller' => 'Rooms', 'action' => 'listRooms'));
	echo "<br />";
	if ($loggedUser['isAdmin']) {
		echo $this->Html->link(__('Cadastrar salas'), array('controller' => 'Rooms', 'action' => 'createRoom') );
		echo "<br />";
	}
	echo "<br />";
	
	echo $this->Html->link(__('Visualizar recursos'), array('controller' => 'Resources', 'action' => 'listResources'));
	echo "<br />";

	if ($loggedUser['isAdmin']) {
		echo $this->Html->link(__('Cadastrar recursos'), array('controller' => 'Resources', 'action' => 'createResource') ); 
	}
?>