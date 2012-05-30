<h1>Criação da Reserva</h1>
<?php
	echo $this->Html->script('create_reservation');
	echo $this->Form->Create('Reservation');
	echo $this->Form->Input('description', array('label' => __('Motivo da reserva')));
?>
<h2>Recursos utilizados</h2>
<div id="resources">
	<table class="redTable">
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
