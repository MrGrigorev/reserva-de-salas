<?php
function getTranslatedProfile($user) {
	switch ($user['User']['profile']) {
	case 'Student':
		return __('Estudante');
	case 'Professor':
		return __('Funcionário Docente');
	case 'Employee':
		return __('Funcionário Não-docente');
	}
}

function getTranslatedStatus($user) {
	switch ($user['User']['activation_status']) {
	case 'active':
		return __('Ativo');
	case 'waiting_validation':
		return __('Esperando validação');
	case 'waiting_activation':
		return __('Esperando ativação');
	}
}
?>