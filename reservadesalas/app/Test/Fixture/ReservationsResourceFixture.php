<?php
/**
 * ReservationsResourceFixture
 *
 */
class ReservationsResourceFixture extends CakeTestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'primary'),
			'reservation_id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'index'),
			'resource_id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'index'),
			'indexes' => array(
					'PRIMARY' => array('column' => 'id', 'unique' => 1),
					'reservation_id' => array('column' => 'reservation_id',
							'unique' => 0),
					'resource_id' => array('column' => 'resource_id',
							'unique' => 0)),
			'tableParameters' => array('charset' => 'latin1',
					'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'));

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
			array('id' => 1, 'reservation_id' => 1, 'resource_id' => 1),);
}
