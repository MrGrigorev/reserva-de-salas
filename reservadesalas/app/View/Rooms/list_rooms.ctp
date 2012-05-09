<?php echo $this->Html->css('styles'); ?>

<h1><?php echo __('Salas'); ?></h1>
<br />
<table id="roomsTable">
<?php
	$header = array(
		array(
			array(__('Número'), array('class' => 'header')),
			array(__('Bloco'), array('class' => 'header')),
			array(__('Andar'), array('class' => 'header')),
			array(__('Tipo'), array('class' => 'header')),
			array(__('Capacidade'), array('class' => 'header'))
		)
	);
	echo $this->Html->tableCells($header);

	$cells = array();
	foreach ($rooms as $room) {
		$roomNumberOrName = $room['Room']['number'];
		if ($roomNumberOrName == '')
			$roomNumberOrName = $room['Room']['name'];
		
		$cells[] = array($roomNumberOrName, $room['Building']['name'], $room['Room']['floor'], $room['Room']['room_type'], $room['Room']['capacity']);
	}

	echo $this->Html->tableCells($cells);
?>
</table>

<?php
	echo $this->element('back');
?>