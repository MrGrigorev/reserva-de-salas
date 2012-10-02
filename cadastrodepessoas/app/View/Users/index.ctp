<h1><?php echo __('Página Inicial'); ?></h1>

<?php
	$greeting = "Olá";
	$welcomeMessage = "Seja bem-vindo ao sistema de Cadastro de Pessoas do IME-USP.";
	echo $greeting . ", " . $loggedUser['name'] . ".";
?>
<br /><br />
<?php
	echo $welcomeMessage;
?>
<br />
<br />
<?php 
	if ($loggedUser['user_type'] == "admin") {
		echo $this->Html
				->link(__('Veja pedidos de ativação'),
						array('controller' => 'Users',
								'action' => 'listActivationRequests'));
		echo "<br />";
		echo $this->Html
				->link(__('Veja os usuários cadastrados'),
						array('controller' => 'Users',
								'action' => 'listUsers'));
		echo "<br />";
	}

	echo $this->Html
			->link(__('Trocar senha'),
					array('controller' => 'Users',
							'action' => 'changePassword'));
?>