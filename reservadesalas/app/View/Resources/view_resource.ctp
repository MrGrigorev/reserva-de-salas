<?php
	echo "<h1>" . $resource['Resource']['name'] . "</h1>"; 
?>
<br />
<?php
	if ($resource['Resource']['room']) {
		echo __('Sala') . ": " . $this->Html->link($resource['Resource']['room'], array('controller' => 'Rooms', 'action' => 'viewRoom', $resource['Resource']['room_id']));
		echo "<br /><br />";
	}
	
	echo __("Número de Série") . ": " . $resource['Resource']['serial_number'];
	
	if ($resource['Resource']['description']) {
		echo "<br /><br />";
		echo __('Descrição') . ":<br />";
		echo $resource['Resource']['description'];
	}
?>

<?php
	echo $this->element('back');
?>