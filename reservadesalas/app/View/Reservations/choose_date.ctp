<?php
	echo $this->Html->css('Rooms/rooms');

	echo $this->Html->script('choose_date');

	echo "<h1>Seleção de data para reserva</h1>"; 

	echo $this->Form->Create('Date');
    echo $this->Form->Input('date', array('label' => __('Data')));
    echo $this->Form->Input('begin_time', array('label' => __('Hora de início')));
    echo $this->Form->Input('end_time', array('label' => __('Hora de término')));

    echo $this->Form->Submit(__('Carregar Salas Disponíveis'), array('id'=>'loadAvailableRooms'));
	$this->Form->End();
?>
<div id="availableRooms">
</div>