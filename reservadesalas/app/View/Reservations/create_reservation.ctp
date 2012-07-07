<?php 
	echo $this->Html->script('create_reservation');
	
	$roomName = $room['Room']['name'];
	$roomNumber = $room['Room']['number'];
	$roomTitle = "";
	
	if ($roomNumber) {
		$roomTitle = $roomNumber;
	}
	if ($roomName) {
		if ($roomNumber) {
			$roomTitle .= " - ";				
		}
		$roomTitle .= $roomName;
	}
?>
<h1>Reserva da sala <?php echo $this->Html->link($roomTitle, array('controller' => 'Rooms', 'action' => 'viewRoom', $room['Room']['id']), array('target' => '_blank')); ?></h1>

<?php
	foreach ($chosenDates as $date)
		echo $date . "<br />";
?>
<br />
<br />
<?php
	echo $this->Form->Create('Reservation');
	echo $this->Form->Input('description', array('label' => __('Motivo da reserva')));
	echo $this->Form->Input('roomId', array('type' => 'hidden', 'value' => $room_id));
	echo $this->Form->Input('dates', array('type' => 'hidden', 'value' => $dates));
	echo $this->Form->Input('beginTimes', array('type' => 'hidden', 'value' => $beginTimes));
	echo $this->Form->Input('endTimes', array('type' => 'hidden', 'value' => $endTimes));
	echo $this->Form->Input('until', array('type' => 'hidden', 'value' => $untilDate));
	echo $this->Form->Input('repetitions', array('type' => 'hidden', 'value' => $repetitions));
	
	echo $this->Form->Input('save', array('type' => 'hidden', 'value' => true));
?>
<h2>Recursos utilizados</h2>
<div id="resources">
	<table class="redTable">
		<?php 
		$header = array(
		array(
			array(__('Nome'), array('class' => 'header')),
			array(__('Número de Patrimônio'), array('class' => 'header')),
			array('', array('class' => 'header'))
		)
		);
		echo $this->Html->tableCells($header);
		
		$cells = array();
		foreach($fixedResources as $fixedResource) {
			$link = $this->Html->link($fixedResource['Resource']['name'], array('controller' => 'Resources', 'action' => 'viewResource', $fixedResource['Resource']['id']), array('target' => '_blank'));
			$cells[] = array($link, $fixedResource['Resource']['serial_number'], '');
		}
		echo $this->Html->tableCells($cells);
		?>
	</table>
</div>

<br />
<br />
<button id="addResource" type="button">Adicionar recursos</button>
<div id="availableResources">
</div>

<br />
<br />

<?php
	echo $this->Form->End(__('Criar reserva')); 
?>
