<?php echo $this->Html->css('styles'); ?>

<h1><?php echo __('Salas'); ?></h1>
<br />
<table id="roomsTable">
<?php
	function orderParameter($class, $attribute, $actualOrder) {
		$parameter = $class . '.' . $attribute . ' ASC';

		if ($parameter != $actualOrder)
			return $parameter;

		return $class . '.' . $attribute . ' DESC';
	}

	$parameter = orderParameter('Room', 'name', $actualOrder);
	$linkNumber = $this->Html->link(__('Número'), array('controller' => 'Rooms', 'action' => 'listRooms', $parameter));

	$parameter = orderParameter('Building', 'name', $actualOrder);
	$linkBuilding = $this->Html->link(__('Bloco'), array('controller' => 'Rooms', 'action' => 'listRooms', $parameter));

	$parameter = orderParameter('Room', 'floor', $actualOrder);
	$linkFloor = $this->Html->link(__('Andar'), array('controller' => 'Rooms', 'action' => 'listRooms', $parameter));
	
	$parameter = orderParameter('Room', 'room_type', $actualOrder);
	$linkType = $this->Html->link(__('Tipo'), array('controller' => 'Rooms', 'action' => 'listRooms', $parameter));
	
	$parameter = orderParameter('Room', 'capacity', $actualOrder);
	$linkCapacity = $this->Html->link(__('Capacidade'), array('controller' => 'Rooms', 'action' => 'listRooms', $parameter));

	$header = array(
		array(
			array($linkNumber, array('class' => 'header')),
			array($linkBuilding, array('class' => 'header')),
			array($linkFloor, array('class' => 'header')),
			array($linkType, array('class' => 'header')),
			array($linkCapacity, array('class' => 'header'))
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