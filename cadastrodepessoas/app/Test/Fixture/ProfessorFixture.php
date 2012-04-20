<?php
/**
 * ProfessorFixture
 *
 */
class ProfessorFixture extends CakeTestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'primary'),
			'user_id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'index'),
			'department_id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'index'),
			'professor_category_id' => array('type' => 'integer',
					'null' => false, 'default' => NULL, 'key' => 'index'),
			'indexes' => array(
					'PRIMARY' => array('column' => 'id', 'unique' => 1),
					'user_id' => array('column' => 'user_id', 'unique' => 0),
					'department_id' => array('column' => 'department_id',
							'unique' => 0),
					'professor_category_id' => array(
							'column' => 'professor_category_id', 'unique' => 0)),
			'tableParameters' => array('charset' => 'latin1',
					'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'));

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
			array('id' => 1, 'user_id' => 1, 'department_id' => 1,
					'professor_category_id' => 1),);
}
