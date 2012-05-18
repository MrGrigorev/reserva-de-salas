<?php
	echo $this->Html->css('Rooms/list_rooms');
	echo $this->Html->script('list_rooms');
?>

<h1><?php echo __('Recursos'); ?></h1>
<br />
<table id="roomsTable">
<?php
	function orderParameter($class, $attribute, $actualOrder) {
		$parameter = $class . '.' . $attribute . ' ASC';

		if ($parameter != $actualOrder)
			return $parameter;

		return $class . '.' . $attribute . ' DESC';
	}

	$parameter = orderParameter('Resource', 'name', $actualOrder);
	$linkName = $this->Html->link(__('Nome'), array('controller' => 'Resources', 'action' => 'listResources', $parameter));

	$parameter = orderParameter('Resource', 'serial_number', $actualOrder);
	$linkSerial = $this->Html->link(__('Número de série'), array('controller' => 'Resources', 'action' => 'listResources', $parameter));
	
	$parameter = orderParameter('Resource', 'room_id', $actualOrder);
	$linkFixedResource = $this->Html->link(__('Recurso fixo'), array('controller' => 'Resources', 'action' => 'listResources', $parameter));
	
	$header = array(
		array(
			array($linkName, array('class' => 'header')),
			array($linkSerial, array('class' => 'header')),
			array($linkFixedResource, array('class' => 'header')),
		)
	);
	echo $this->Html->tableCells($header);
	
	$fixedResourcesOptionsList = array();
	$fixedResourcesOptionsList[] = array('' => __('Todos'));
	$fixedResourcesOptionsList['yes'] = __('Sim');
	$fixedResourcesOptionsList['no'] = __('Não'); 
	
	echo $this->Form->Create('Resource', array('class' => 'submittableForm'));
	echo $this->Html->tableCells(array(
			array(
					array($this->Form->Input('name', array('label' => __(' '), 'placeholder' => 'Filtrar por nome...')), array()),
					array($this->Form->Input('serial_number', array('label' => __(' '), 'placeholder' => 'Filtrar por número de série...')), array()),
					array($this->Form->Input('is_fixed_resource', array('label' => __(' '), 'type' => 'select', 'options' => $fixedResourcesOptionsList)), array()),
			)
	));
	$this->Form->End();

	$cells = array();
	foreach ($resources as $resource) {
		$resourceName = $resource['Resource']['name'];
		$resourceSerialNumber = $resource['Resource']['serial_number'];
		
		$resourceFixed = __('Sim');
		if ($resource['Resource']['room_id'] == null || $resource['Resource']['room_id'] == '') {
			$resourceFixed = __('Não');
		}
		
		$resourceLink = $this->Html->link($resourceName, array('controller' => 'Resource', 'action' => 'viewResource', $resource['Resource']['id']));
				
		$cells[] = array($resourceLink, $resourceSerialNumber, $resourceFixed);
	}

	echo $this->Html->tableCells($cells);
?>
</table>

<?php
	echo $this->element('back');
?>