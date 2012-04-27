<?php
class ProfessorCategory extends AppModel {
	public $name = 'ProfessorCategory';

	public $useDbConfig = 'cadastrodepessoas';

	public $hasMany = array(
			'Professors' => array('className' => 'Professor',
					'dependent' => false));
}
