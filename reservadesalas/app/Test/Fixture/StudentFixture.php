<?php
/**
 * StudentFixture
 *
 */
class StudentFixture extends CakeTestFixture {

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
			'course_id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'index'),
			'indexes' => array(
					'PRIMARY' => array('column' => 'id', 'unique' => 1),
					'user_id' => array('column' => 'user_id', 'unique' => 0),
					'course_id' => array('column' => 'course_id', 'unique' => 0)),
			'tableParameters' => array('charset' => 'latin1',
					'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'));

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
			array('id' => 1, 'user_id' => 1, 'course_id' => 1),
			array('id' => 2, 'user_id' => 6, 'course_id' => 1),);
}
