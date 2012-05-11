<?php
/**
 * RoomFixture
 *
 */
class RoomFixture extends CakeTestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'primary'),
			'building_id' => array('type' => 'integer', 'null' => false,
					'default' => NULL),
			'name' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 70,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'number' => array('type' => 'integer', 'null' => false,
					'default' => NULL),
			'floor' => array('type' => 'integer', 'null' => false,
					'default' => NULL),
			'room_type' => array('type' => 'string', 'null' => false, 'default' => 'normal'),
			'description' => array('type' => 'text', 'null' => true,
					'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'capacity' => array('type' => 'integer', 'null' => false,
					'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true,
					'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true,
					'default' => NULL)
			);

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
			array('id' => 1,
					'building_id' => 1,
					'name' => 'Antonio Gilioli',
					'number' => 120,
					'floor' => 1,
					'room_type' => 'auditorium',
					'description' => 'Sala Bonita',
					'capacity' => 100
					),
			array('id' => 2,
					'building_id' => 1,
					'name' => 'Corredor',
					'number' => 125,
					'floor' => 1,
					'room_type' => 'normal',
					'description' => 'Rede Linux',
					'capacity' => 30
					),
			array('id' => 3,
					'building_id' => 1,
					'name' => 'Herois',
					'number' => 126,
					'floor' => 1,
					'room_type' => 'normal',
					'description' => 'Rede Linux',
					'capacity' => 30
					),
			array('id' => 4,
					'building_id' => 1,
					'name' => 'Aquario',
					'number' => 122,
					'floor' => 1,
					'room_type' => 'normal',
					'description' => 'Rede Linux',
					'capacity' => 30
					),
			array('id' => 5,
					'building_id' => 1,
					'name' => 'BCC',
					'number' => 258,
					'floor' => 1,
					'room_type' => 'normal',
					'description' => 'Rede Linux',
					'capacity' => 30
					),
			array('id' => 6,
					'building_id' => 2,
					'name' => 'CEC Principal',
					'number' => 10,
					'floor' => 0,
					'room_type' => 'normal',
					'description' => 'Rede Linux',
					'capacity' => 30
					)					
			);
			
}
