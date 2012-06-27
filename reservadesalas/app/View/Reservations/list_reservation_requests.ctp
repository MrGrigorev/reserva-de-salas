<?php
	echo $this->Html->script('list_activation_requests');  
?>
<h1><?php echo __('Lista de requisições de reserva'); ?></h1>

<table id="roomsTable">
<?php
	$header = array(
		array(
			array($this->Form->Input('selectAll', array('type' => 'checkbox', 'label' => '', 'class' => 'selectAll')), array('class' => 'header checkbox')),
			array(__('Sala'), array('class' => 'header')),
			array(__('Início'), array('class' => 'header')),
			array(__('Fim'), array('class' => 'header')),
			array(__('Usuário'), array('class' => 'header')),
			array(__('Descrição'), array('class' => 'header'))
		)
	);
	echo $this->Html->tableCells($header);
	
	echo $this->Form->Create('Reservation', array('class' => 'submittableForm'));
	
	$cells = array();
	foreach ($inactiveReservations as $reservation) {
		$cells[] = array(
			$this->Form->Input($reservation['Reservation']['id'] . '.isChecked', array('type'=>'checkbox', 'label' => '', 'class' => 'selectableBySelectAll')),
			$reservation['Room']['name'],
			$reservation['Reservation']['start_time'],
			$reservation['Reservation']['end_time'],
			$reservation['Reservation']['nusp'],
			$reservation['Reservation']['description']
		);
	}

	echo $this->Html->tableCells($cells);
	
?>
</table>

<div id="dialog-confirm" style="display:none"></div>

<table>
<?php
	echo "<tr><td>" . $this->Form->Submit(__('Aceita'), array('class' => 'needConfirmation')) . "</td>"; 
	echo "<td>" . $this->Form->Submit(__('Rejeita'), array('class' => 'needConfirmation')) . "</td>";
	echo "<td>" . $this->Form->Input('action', array('type' => 'hidden', 'name' => 'action')) . "</td></tr>";

	echo $this->Form->End();
?>
</table>

<?php
	echo $this->element('back');
?>