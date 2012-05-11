<?php
	include_once 'sharedFunctions.php';
	echo $this->Html->css('Rooms/list_rooms');
	echo $this->Html->script('list_rooms');
?>

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
	$linkNumber = $this->Html->link(__('Nome/Número'), array('controller' => 'Rooms', 'action' => 'listRooms', $parameter));

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
	
	echo $this->Form->Create('Room', array('class' => 'submittableForm'));
	echo $this->Html->tableCells(array(
			array(
					array($this->Form->Input('name', array('label' => __(' '), 'placeholder' => 'Filtrar por nome/número...')), array()),
					array($this->Form->Input('Building.name', array('label' => __(' '), 'placeholder' => 'Filtrar por bloco...')), array()),
					array($this->Form->Input('floor', array('label' => __(' '), 'placeholder' => 'Filtrar por andar...')), array()),
					array($this->Form->Input('room_type', array('label' => __(' '), 'placeholder' => 'Filtrar por perfil...', 'type' => 'select',
							'options' => array('all' => __('Todas'), 'auditorium' => __('Auditório'), 'normal' => __('Normal'), 'noble' => __('Sala nobre')))), array()),
					array($this->Form->Input('capacity', array('label' => __(' '), 'placeholder' => 'Filtrar por capacidade...')), array()),
			)
	));
	$this->Form->End();

	$cells = array();
	foreach ($rooms as $room) {
		$roomNumberOrName = $room['Room']['number'];
		if ($roomNumberOrName == '')
			$roomNumberOrName = $room['Room']['name'];
		
		$roomNumberOrName = $this->Html->link($roomNumberOrName, array('controller' => 'Rooms', 'action' => 'viewRoom', $room['Room']['id']));
		
		$floor = $room['Room']['floor'];
		if ($floor == 0)
			$floor = __('Térreo');
		
		$cells[] = array($roomNumberOrName, $room['Building']['name'], $floor, getTranslatedRoomType($room['Room']['room_type']), $room['Room']['capacity']);
	}

	echo $this->Html->tableCells($cells);
?>
</table>

<?php
	echo $this->element('back');
?>