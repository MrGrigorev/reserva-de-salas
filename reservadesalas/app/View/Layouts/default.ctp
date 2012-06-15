<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>Reserva de Salas - IME - USP</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('styles');
		echo $this->Html->css('blitzer/jquery-ui-1.8.18.custom');
		echo $this->Html->css('datepicker');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->Html->script('lib/jquery-1.7.2.min');
		echo $this->Html->script('lib/jquery-ui-1.8.18.custom.min');
		echo $this->Html->script('general');
		echo $this->Html->script('lib/jquery-ui-timepicker-addon');
		echo $this->Html->script('lib/jquery.json');
		echo $this->Html->script('lib/development-bundle/ui/i18n/jquery.ui.datepicker-pt-BR');
		echo $this->Html->script('lib/development-bundle/ui/i18n/jquery.ui.timepicker-pt-BR');
	?>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700|PT+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
	<div id="headerContainer">
		<div id="header">
			<div id="title">
				<?php $logo = "logo.png"; ?>
				<?php echo $this->Html->link($this->Html->Image($logo), array('controller' => 'Users', 'action' => 'index'), array('escape' => false)); ?>
			</div>
			<div id="menu">
				<ul>
					<?php 
						if ($isLogged == false) {
					?>
					<li>
						<?php echo $this->Html->link(__('Entrar'), array('controller' => 'Users', 'action' => 'login') ); ?>
					</li>
					<?php } else {?>
					<li>
						<?php echo $loggedUser['name']; ?>
					</li>
					<li>
						<?php echo $this->Html->link(__('Sair'), array('controller' => 'Users', 'action' => 'logout') ); ?>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<div id="content" class="roundedBorders">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
</body>
</html>
