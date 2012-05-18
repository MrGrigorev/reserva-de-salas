<?php
	echo $this->Html->css('Rooms/rooms');

	$roomList = array();	
	$roomList[] = array("" => "-------");
	foreach ($rooms as $room) {
		$roomList[$room['Room']['id']] = $room['Room']['name'];
	} 
?>

<h1><?php echo __('Cadastrar recurso'); ?></h1>

<?php
	echo $this->Form->Create('Resource');
	echo $this->Form->Input('name', array('label' => __('Nome')));
	echo $this->Form->Input('serial_number', array('label' => __('Número de Série')));
	echo $this->Form->Input('room_id', array('label' => __('Sala'), 'type' => 'select', 'options' => $roomList));
	echo $this->Form->Input('description', array('label' => __('Descrição'), 'type' => 'textarea'));
	echo $this->Form->End(__('Criar recurso'));
?>