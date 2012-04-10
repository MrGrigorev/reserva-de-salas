<h1><?php echo __('Página Inicial do Usuário'); ?></h1>

Aqui o usuário já está logado.
<?php echo $this->Html
		->link(__('Veja pedidos de ativação'),
				array('controller' => 'Users',
						'action' => 'listActivationRequests'));
?>