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
			'nusp' => array('type' => 'string', 'null' => false, 'length' => 10,
					'default' => NULL),
			'description' => array('type' => 'text', 'null' => true,
					'default' => NULL, 'collate' => 'latin1_swedish_ci',
					'charset' => 'latin1'),
			'is_activated' => array('type' => 'integer', 'null' => false,
					'default' => 0, 'length' => 1),
			'created' => array('type' => 'datetime', 'null' => true,
					'default' => NULL),
			'indexes' => array(
					'PRIMARY' => array('column' => 'id', 'unique' => 1),
					'room_id' => array('column' => 'room_id', 'unique' => 0)),
			'tableParameters' => array('charset' => 'latin1',
					'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'));

	public $records = array(
			array('id' => 1, 'start_time' => '2012-06-30 10:25:25',
					'end_time' => '2012-06-30 11:25:25', 'room_id' => 1,
					'nusp' => '12345678', 'description' => 'Super reserva',
					'is_activated' => 1),
			array('id' => 2, 'start_time' => '2012-06-30 11:25:25',
					'end_time' => '2012-06-30 12:25:25', 'room_id' => 2,
					'nusp' => '111111', 'description' => 'Super reserva',
					'is_activated' => 0)
			);
}
