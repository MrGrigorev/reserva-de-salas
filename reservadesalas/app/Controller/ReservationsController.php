<?php
class ReservationsController extends AppController {
	public $name = 'Reservations';

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		return parent::isAuthorized($user);
	}
	
	public function chooseDate() {
	}
}
?>