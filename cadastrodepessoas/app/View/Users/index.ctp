<h1><?php echo __('Página Inicial do Usuário'); ?></h1>

Aqui o usuário já está logado.
<br />
<br />
<?php echo $this->Html
		->link(__('Veja pedidos de ativação'),
				array('controller' => 'Users',
						'action' => 'listActivationRequests'));
echo "<br />";
echo $this->Html
		->link(__('Veja os usuários cadastrados'),
				array('controller' => 'Users',
						'action' => 'listUsers'));
?>