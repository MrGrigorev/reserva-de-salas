<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>Cadastro de Pessoas - IME - USP</title>
	<?php
		echo $this->Html->meta('icon');
		//echo $this->Html->css('cake.generic');
		echo $this->Html->css('styles');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->Html->script('lib/jquery-1.7.2.min');
	?>
</head>
<body>
	<div id="headerContainer">
		<div id="header">
			<div id="title">
				<h1>
					Cadastro de Pessoas
				</h1>
			</div>
			<div id="menu">
				<ul>
					<li>
						<a href="#">Criar conta</a>
					</li>
					<li>
						<a href="#">login</a>
					</li>
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
