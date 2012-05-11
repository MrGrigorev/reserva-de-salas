<?php
function getTranslatedRoomType($type) {
	switch ($type) {
	case 'normal':
		return __('Normal');
	case 'auditorium':
		return __('Auditório');
	case 'noble':
		return __('Sala nobre');
	}
}
?>
