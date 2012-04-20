<?php
class ProfessorCategory extends AppModel {
	public $name = 'ProfessorCategory';
	
	public $hasMany = array(
			'Professors' => array('className' => 'Professor',
					'dependent' => false));
}
