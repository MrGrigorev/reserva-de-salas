<?php
	echo $this->Html->css('Rooms/rooms');

	echo $this->Html->script('choose_date');

	echo "<h1>Seleção de data para reserva</h1>"; 

	echo $this->Form->Create('Date');
?>
<div id="reservationDates">
	<div id="reservationDate">
		<?php
		    echo $this->Form->Input('0.date', array('label' => __('Data'), 'placeholder' => __('Insira a data...')));
		    
		    $timeNow = time() + 10 * 60;
		    echo $this->Form->Input('0.begin_time', array('label' => __('Hora de início'), 'value' => date('G:i', $timeNow)));
		    
		    $timeNow += 60 * 60;
		    echo $this->Form->Input('0.end_time', array('label' => __('Hora de término'), 'value' => date('G:i', $timeNow)));
		?>
	</div>
</div>
<button id="addDatetime">Adicionar horário</button>
<button id="removeDatetime" style="display: none">Remover horário</button>
<?php
    echo $this->Form->Input('capacity', array('label' => __('Capacidade mínima')));
    
    echo $this->Form->Input('repetitions', array('legend' => __('Repetir'), 'options' => array('none' => __('Reserva única'), 'daily' => __('Diariamente'), 'weekly' => __('Semanalmente'), 'monthly' => __('Mensalmente')), 'type' => 'radio', 'class' => 'reservationRadio', 'default' => 'none'));
?>
<div id="reservationUntil" style="display:none">
<?php
    echo $this->Form->Input('until', array('label' => __('Até'), 'value' => $untilDate));
?> 
</div>
<?php
    echo $this->Form->Submit(__('Carregar Salas Disponíveis'), array('id'=>'loadAvailableRooms'));
	echo $this->Form->End();
?>
<div id="availableRooms">
</div>
<div>
<?php
	echo $this->Form->Create('Reservation', array('action' => 'createReservation'));
	echo $this->Form->Input('roomId', array('type' => 'hidden'));
	echo $this->Form->Input('dates', array('type' => 'hidden'));
	echo $this->Form->Input('beginTimes', array('type' => 'hidden'));
	echo $this->Form->Input('endTimes', array('type' => 'hidden'));
	echo $this->Form->End();
?>
</div>