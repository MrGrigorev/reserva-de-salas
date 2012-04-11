<?php
	echo $this->Html->script('create_account'); 
	echo $this->Html->css('Users/users');

	$departmentsList = array();
	foreach ($departments as $department)
		$departmentsList[$department['Department']['id']] = $department['Department']['name'];

	$coursesList = array();
	foreach ($courses as $course)
		$coursesList[$course['Course']['id']] = $course['Course']['name'];
?>
<h1><?php echo __('Editar conta'); ?></h1>

<?php
	echo $this->Form->Create('User', array('type' => 'file'));
	echo "Nusp: " . $this->request->data['User']['nusp'] . "<br />";
	echo "Nome: " . $this->request->data['User']['name'] . "<br />";
	echo $this->Form->Input('id', array('type' => 'hidden'));
	echo $this->Form->Input('email', array('label' => __('E-mail')));
	echo $this->Form->Input('photo', array('label' => __('Foto'), 'type' => 'file'));
	echo $this->Form->Input('webpage', array('label' => __('Página na Web')));
	echo $this->Form->Input('lattes', array('label' => __('Currículo Lattes')));
?>

<?php
	if ($profile == 'Employee') {
		echo $this->Form->Input('Employee.id', array('type' => 'hidden'));
		echo $this->Form->Input('Employee.occupation', array('label' => __('Cargo')));
	}
	if ($profile == 'Student') {
		echo $this->Form->Input('Student.id', array('type' => 'hidden'));
		echo $this->Form->Input('Student.course_id', array('label' => __('Curso'), 'type' => 'select', 'options' => $coursesList));
	}
	if ($profile == 'Professor') {
		echo $this->Form->Input('Professor.id', array('type' => 'hidden'));
		echo $this->Form->Input('Professor.department_id', array('label' => __('Departamento'), 'type' => 'select', 'options' => $departmentsList));
	}
?>
	
<?php
	echo $this->Form->End(__('Editar conta'));
?>