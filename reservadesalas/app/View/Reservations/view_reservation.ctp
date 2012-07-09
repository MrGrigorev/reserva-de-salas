<?php
	echo $this->Html->script('view_reservation');

	echo "<h1>" . 'Reserva' . "</h1>";
	
	echo $date;
	echo ' ' . __('das') . ' ' . $reservation['Reservation']['start_time'];
	echo ' ' . __('às') . ' ' . $reservation['Reservation']['end_time'];
	
	echo "<br /><br />";

	echo __('Sala') . ': ';
	
	$roomLink = '';
	if ($reservation['Room']['number']) {
		$roomLink .= $reservation['Room']['number'];

		if ($reservation['Room']['name'])
			$roomLink .= " - ";
	}
	if ($reservation['Room']['name']) {
		$roomLink .= $reservation['Room']['name'];
	}
	
	echo $this->Html->link($roomLink, array('controller' => 'Rooms', 'action' => 'viewRoom', $reservation['Room']['id']));
	
	echo "<br />";
	
	echo __('NUSP') . ': ' . $reservation['Reservation']['nusp'];
	
	if ($reservation['Reservation']['description']) {
		echo "<br /><br />";
		echo __('Motivo da reserva') . ":<br />";
		echo $room['Room']['description'];
	}
	
	if ($reservation['Resources']) {
		echo "<br /><br />";
		echo __('Recursos alocados') . ":<br />";
		foreach($reservation['Resources'] as $resource) {
			echo $this->Html->link($resource['name'], array('controller' => 'Resources', 'action' => 'viewResource', $resource['id']));
			echo "<br />";
		}
	}
?>

<div id="dialog-confirm" style="display:none"></div>

<br />
<br />
<?php
	if ($loggedUser['nusp'] == $reservation['Reservation']['nusp'] || $loggedUser['isAdmin'] == true) {
		echo $this->Form->Create('Reservation', array('action' => 'delete', 'class' => 'submittableForm'));
		echo $this->Form->Input('id', array('type' => 'hidden', 'value' => $reservation['Reservation']['id']));
	
		echo $this->Form->Submit(__('Apagar Reserva'), array('class' => 'needConfirmation'));
		echo $this->Form->end();
	}

	echo $this->element('back');
?>