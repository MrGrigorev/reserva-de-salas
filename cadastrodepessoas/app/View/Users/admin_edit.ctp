<?php
	echo $this->Html->script('create_account'); 
	echo $this->Html->css('Users/users');

	$departmentsList = array();
	foreach ($departments as $department)
		$departmentsList[$department['Department']['id']] = $department['Department']['name'];

	$coursesList = array();
	foreach ($courses as $course)
		$coursesList[$course['Course']['id']] = $course['Course']['name'];
	
	$categoriesList = array();
	foreach ($categories as $category)
		$categoriesList[$category['ProfessorCategory']['id']] = $category['ProfessorCategory']['name'];
	
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
	echo $this->Form->Input('profile', array('legend' => __('Tipo de usuário'), 'options' => array('Student' => __('Estudante'), 'Employee' => __('Funcionário (Não Docente)'), 'Professor' => __('Funcionário (Docente)')), 'type' => 'radio', 'class' => 'profileRadio'));
?>

<div id="Employee" class="profile">
	<?php
		echo $this->Form->Input('Employee.id', array('type' => 'hidden'));
		echo $this->Form->Input('Employee.occupation', array('label' => __('Cargo')));
		echo $this->Form->Input('Employee.telephone', array('label' => __('Ramal')));
		echo $this->Form->Input('Employee.room', array('label' => __('Sala')));
	?>
</div>

<div id="Student" class="profile">
	<?php
		echo $this->Form->Input('Student.id', array('type' => 'hidden'));
		echo $this->Form->Input('Student.course_id', array('label' => __('Curso'), 'type' => 'select', 'options' => $coursesList));
	?>
</div>
	
<div id="Professor" class="profile">
	<?php
		echo $this->Form->Input('Professor.id', array('type' => 'hidden'));
		echo $this->Form->Input('Professor.department_id', array('label' => __('Departamento'), 'type' => 'select', 'options' => $departmentsList));
		echo $this->Form->Input('Professor.professor_category_id', array('label' => __('Categoria'), 'type' => 'select', 'options' => $categoriesList));
		echo $this->Form->Input('Professor.telephone', array('label' => __('Ramal')));
		echo $this->Form->Input('Professor.room', array('label' => __('Sala')));
	?>
</div>
	
<?php
	echo $this->Form->End(__('Salvar'));
	echo $this->element('back');
?>
