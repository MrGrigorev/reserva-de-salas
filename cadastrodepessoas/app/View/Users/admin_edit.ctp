<?php
	echo $this->Html->script('create_account'); 
	echo $this->Html->css('Users/users');

	$departmentsList = array();
	foreach ($departments as $department)
		$departmentsList[$department['Department']['id']] = $department['Department']['name'];

	$coursesList = array();
	foreach ($courses as $course)
		$coursesList[$course['Course']['id']] = $course['Course']['name'];
	
	$userTypes = array('admin' => __('Administrador'), 'user' => __('Usuário Comum'));
	
	$possibleStatus = array('active' => __('Ativo'), 'waiting_activation' => __('Esperando Ativação'), 'waiting_validation' => __('Esperando Validação'));
?>
<h1><?php echo __('Editar conta'); ?></h1>

<?php
	echo $this->Form->Create('User', array('type' => 'file'));
	echo $this->Form->Input('id', array('type' => 'hidden'));
	echo $this->Form->Input('user_type', array('label' => __('Pefil'), 'type' => 'select', 'options' => $userTypes));
	echo $this->Form->Input('activation_status', array('label' => __('Status'), 'type' => 'select', 'options' => $possibleStatus));
	echo $this->Form->Input('nusp', array('label' => __('Número USP')));
	echo $this->Form->Input('name', array('label' => __('Nome Completo')));
	echo $this->Form->Input('email', array('label' => __('E-mail')));
	echo $this->Form->Input('photo', array('label' => __('Trocar Foto'), 'type' => 'file'));
	echo $this->Form->Input('webpage', array('label' => __('Página na Web')));
	echo $this->Form->Input('lattes', array('label' => __('Currículo Lattes')));
	echo $this->Form->Input('profile', array('legend' => __('Tipo de usuário'), 'options' => array('Student' => __('Estudante'), 'Employee' => __('Funcionário (Não Docente)'), 'Professor' => __('Funcionário (Docente)')), 'type' => 'radio', 'class' => 'userTypeRadio'));
?>

<div id="Employee" class="userType">
	<?php
		echo $this->Form->Input('Employee.id', array('type' => 'hidden'));
		echo $this->Form->Input('Employee.occupation', array('label' => __('Cargo')));
	?>
</div>

<div id="Student" class="userType">
	<?php
		echo $this->Form->Input('Student.id', array('type' => 'hidden'));
		echo $this->Form->Input('Student.course_id', array('label' => __('Curso'), 'type' => 'select', 'options' => $coursesList));
	?>
</div>
	
<div id="Professor" class="userType">
	<?php
		echo $this->Form->Input('Professor.id', array('type' => 'hidden'));
		echo $this->Form->Input('Professor.department_id', array('label' => __('Departamento'), 'type' => 'select', 'options' => $departmentsList));
	?>
</div>
	
<?php
	echo $this->Form->End(__('Criar conta'));
?>