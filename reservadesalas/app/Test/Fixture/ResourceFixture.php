<?php
/**
 * ResourceFixture
 *
 */
class ResourceFixture extends CakeTestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'primary'),
			'room_id' => array('type' => 'integer', 'null' => true,
					'default' => NULL),
			'serial_number' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 50,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'name' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 70,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'description' => array('type' => 'text', 'null' => false,
					'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1')
			);

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
			array('id' => 1,
					'room_id' => 1,
					'serial_number' => 'R1',
					'name' => 'Projetor Epson',
					'description' => 'Funciona'),
			array('id' => 2,
					'room_id' => 2,
					'serial_number' => 'R2D2',
					'name' => 'Projetor HP',
					'description' => 'Quebrado'),
			array('id' => 3,
					'room_id' => null,
					'serial_number' => 'R3',
					'name' => 'Projetor Positivo',
					'description' => 'Mais ou menos'),
			);
			
}
