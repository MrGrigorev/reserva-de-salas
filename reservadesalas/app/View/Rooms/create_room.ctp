<?php
	echo $this->Html->css('Rooms/rooms');
	
	$buildingsList = array();
	foreach ($buildings as $building) {
		$buildingsList[$building['Building']['id']] = $building['Building']['name'];
	}
	
	$roomTypeList = array("normal" => "Normal", "auditorium" => "Auditório", "noble" => "Nobre");
?>
<h1><?php echo __('Cadastrar sala'); ?></h1>

<?php
	echo $this->Form->Create('Room');
	echo $this->Form->Input('name', array('label' => __('Título')));
	echo $this->Form->Input('building_id', array('label' => __('Bloco'), 'type' => 'select', 'options' => $buildingsList));
	echo $this->Form->Input('floor', array('label' => __('Andar')));
	echo $this->Form->Input('number', array('label' => __('Número')));
	echo $this->Form->Input('capacity', array('label' => __('Capacidade')));
	echo $this->Form->Input('room_type', array('label' => __('Tipo'), 'type' => 'select', 'options' => $roomTypeList));
	echo $this->Form->Input('description', array('label' => __('Descrição'), 'type' => 'textarea'));
	echo $this->Form->End(__('Criar sala'));
?>