<?php
/**
 * DepartmentFixture
 *
 */
class DepartmentFixture extends CakeTestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'primary'),
			'name' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 50,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'indexes' => array(
					'PRIMARY' => array('column' => 'id', 'unique' => 1)),
			'tableParameters' => array('charset' => 'latin1',
					'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'));

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
			array('id' => 1, 'name' => 'Lorem ipsum dolor sit amet'),);
}
