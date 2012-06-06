<?php
/**
 * ReservationFixture
 *
 */
class ReservationFixture extends CakeTestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'primary'),
			'start_time' => array('type' => 'datetime', 'null' => false,
					'default' => NULL),
			'end_time' => array('type' => 'datetime', 'null' => false,
					'default' => NULL),
			'room_id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'index'),
			'user_id' => array('type' => 'integer', 'null' => false,
					'default' => NULL),
			'description' => array('type' => 'text', 'null' => true,
					'default' => NULL, 'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1'),
			'is_activated' => array('type' => 'text', 'null' => false,
					'default' => 'b\'0\'', 'length' => 1),
			'created' => array('type' => 'datetime', 'null' => true,
					'default' => NULL),
			'indexes' => array(
					'PRIMARY' => array('column' => 'id', 'unique' => 1),
					'room_id' => array('column' => 'room_id', 'unique' => 0)),
			'tableParameters' => array('charset' => 'latin1',
					'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'));
}
