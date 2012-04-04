<?php
	echo $this->Html->script('create_account'); 
	echo $this->Html->css('Users/users');
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
	echo $this->Form->Input('userType', array('legend' => 'Tipo de usuário', 'options' => array('Employee' => 'Empregado', 'Student' => 'Estudante', 'Professor' => 'Professor') ,'type' => 'radio', 'class' => 'userTypeRadio'));
?>

<div id="Employee" class="userType">
	<?php
		echo $this->Form->Input('Employee.occupation', array('label' => 'Cargo') );
	?>
</div>

<div id="Student" class="userType">
	<?php
		echo $this->Form->Input('Student.course', array('label' => 'Curso') );
	?>
</div>
	
<div id="Professor" class="userType">
	<?php
		echo $this->Form->Input('Professor.department', array('label' => 'Departamento') );
		echo $this->Form->Input('Professor.webpage', array('label' => 'Página na Web'));
		echo $this->Form->Input('Professor.lattes', array('label' => 'Currículo Lattes'));
	?>
</div>
	
<?php
	echo $this->Form->End('Criar conta');
?>