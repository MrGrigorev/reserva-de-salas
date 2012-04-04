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
<h1>Criar conta</h1>

<?php
	echo $this->Form->Create('User', array('type' => 'file'));
	echo $this->Form->Input('nusp', array('label' => 'Número USP') );
	echo $this->Form->Input('name', array('label' => 'Nome') );
	echo $this->Form->Input('email', array('label' => 'E-mail') );
	echo $this->Form->Input('password', array('label' => 'Senha') );
	echo $this->Form->Input('passwordConfirmation', array('label' => 'Confirmação da senha', 'type' => 'password') );
	echo $this->Form->Input('photo', array('label' => 'Foto', 'type' => 'file'));
	echo $this->Form->Input('webpage', array('label' => 'Página na Web'));
	echo $this->Form->Input('lattes', array('label' => 'Currículo Lattes'));
	echo $this->Form->Input('userType', array('legend' => 'Tipo de usuário', 'options' => array('Employee' => 'Empregado', 'Student' => 'Estudante', 'Professor' => 'Professor') ,'type' => 'radio', 'class' => 'userTypeRadio'));
?>

<div id="Employee" class="userType">
	<?php
		echo $this->Form->Input('Employee.occupation', array('label' => 'Cargo'));
	?>
</div>

<div id="Student" class="userType">
	<?php
		echo $this->Form->Input('Student.course_id', array('label' => 'Curso', 'type' => 'select', 'options' => $coursesList));
	?>
</div>
	
<div id="Professor" class="userType">
	<?php
		echo $this->Form->Input('Professor.department_id', array('label' => 'Departamento', 'type' => 'select', 'options' => $departmentsList));
	?>
</div>
	
<?php
	echo $this->Form->End('Criar conta');
?>