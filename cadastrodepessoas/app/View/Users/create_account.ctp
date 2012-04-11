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
<h1><?php echo __('Criar conta'); ?></h1>

<?php
	echo $this->Form->Create('User', array('type' => 'file'));
	echo $this->Form->Input('nusp', array('label' => __('Número USP')));
	echo $this->Form->Input('name', array('label' => __('Nome Completo')));
	echo $this->Form->Input('email', array('label' => __('E-mail')));
	echo $this->Form->Input('password', array('label' => __('Senha')));
	echo $this->Form->Input('passwordConfirmation', array('label' => __('Confirmação da senha'), 'type' => 'password'));
	echo $this->Form->Input('photo', array('label' => __('Foto'), 'type' => 'file'));
	echo $this->Form->Input('webpage', array('label' => __('Página na Web')));
	echo $this->Form->Input('lattes', array('label' => __('Currículo Lattes')));
	echo $this->Form->Input('userType', array('legend' => __('Tipo de usuário'), 'options' => array('Student' => __('Estudante'), 'Employee' => __('Funcionário (Não Docente)'), 'Professor' => __('Funcionário (Docente)')), 'type' => 'radio', 'class' => 'userTypeRadio'));
?>

<div id="Employee" class="userType">
	<?php
		echo $this->Form->Input('Employee.occupation', array('label' => __('Cargo')));
	?>
</div>

<div id="Student" class="userType">
	<?php
		echo $this->Form->Input('Student.course_id', array('label' => __('Curso'), 'type' => 'select', 'options' => $coursesList));
	?>
</div>
	
<div id="Professor" class="userType">
	<?php
		echo $this->Form->Input('Professor.department_id', array('label' => __('Departamento'), 'type' => 'select', 'options' => $departmentsList));
	?>
</div>
	
<?php
	echo $this->Form->End(__('Criar conta'));
?>