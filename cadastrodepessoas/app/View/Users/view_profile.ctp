<?php include_once 'sharedFunctions.php'; ?>

<table>
<?php
	$cells = array();

	$photo = '..' . DS . 'app' . DS . 'webroot' . DS . 'photos' . DS . 'thumb' . DS . 'small' . DS . $user['User']['photo'];
	if ($user['User']['photo'] == '')
		$photo = '..' . DS . 'app' . DS . 'webroot' . DS . 'img' . DS . 'defaultPhoto.png';
	
	$name = "<h1 class='profileName'>" . $user['User']['name'] . "</h1>";
	$profile = "<h2>" . getTranslatedProfile($user) . "</h2>";
	$subProfile = "<h3>" . $user['User']['subProfile'] . "</h3>";
		
	$cells[] = array($this->Html->Image($photo, array('class' => 'profilePhoto')), $name . $profile . $subProfile);
	
	echo $this->Html->tableCells($cells);
?>
</table>
<table>
<?php
	$cells = array();
	
	$cells[] = array(__('Número USP'), $user['User']['nusp']);
	$cells[] = array(__('E-Mail'), $user['User']['email']);
	
	if($user['User']['webpage'] != null)
		$cells[] = array(__('Página Web'), $this->Html->link($user['User']['webpage'], $user['User']['webpage'], array('target' => '_blank')));
	
	if($user['User']['lattes'] != null)
		$cells[] = array(__('Lattes'), $this->Html->link($user['User']['lattes'], $user['User']['lattes'], array('target' => '_blank')));
	
	echo $this->Html->tableCells($cells);
?>
</table>
<br />
<?php
	if ($loggedUser['user_type'] == 'admin') {
		echo $this->Html->link('Editar perfil', array('controller' => 'Users', 'action' => 'adminEdit', $user['User']['id']), array('class' => 'linkStylized'));
	} else if ($loggedUser['id'] == $user['User']['id']) {
		echo $this->Html->link('Editar perfil', array('controller' => 'Users', 'action' => 'editProfile'), array('class' => 'linkStylized')); 
	}
?>