<?php
class ProfessorCategory extends AppModel {
	public $name = 'ProfessorCategory';

	public $useDBConfig = 'cadastrodepessoas';

	public $hasMany = array(
			'Professors' => array('className' => 'Professor',
					'dependent' => false));
}
