<?php
	echo "<h1>" . $room['Room']['name'] . "</h1>"; 
?>
<br />
<?php
	echo __('Localização') . ": " . __('Bloco') . $room['Room']['building'];
	if ($room['Room']['number']) {
		echo ", " . __('Sala') . " " . $room['Room']['number'];
	}
	echo "<br />";
	echo "<br />";
	echo __('Capacidade') . ": " . $room['Room']['capacity'];
	
	if ($room['Room']['description']) {
		echo "<br /><br />";
		echo __('Descrição') . ":<br />";
		echo $room['Room']['description'];
	}
	
	if ($room['Room']['resources']) {
		echo "<br /><br />";
		echo __('Recursos disponíveis') . ":<br />";
		foreach($room['Room']['resources'] as $resource) {
			echo $this->Html->link($resource['Resource']['name'], array('controller' => 'Resources', 'action' => 'viewResource', $resource['Resource']['id']));
			echo "<br />";
		}
	}
?>

<?php
	echo $this->element('back');
?>