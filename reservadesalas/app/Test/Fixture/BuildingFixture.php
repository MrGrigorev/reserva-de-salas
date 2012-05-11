<?php
/**
 * BuildingFixture
 *
 */
class BuildingFixture extends CakeTestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'primary'),
			'name' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 70,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'floor_number' => array('type' => 'integer', 'null' => false,
					'default' => 0),
			);

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
			array('id' => 1,
					'name' => 'A',
					'floor_number' => '2'),
			array('id' => 2,
					'name' => 'B',
					'floor_number' => '1'),
			array('id' => 3,
					'name' => 'C',
					'floor_number' => '2')
			);
			
}
