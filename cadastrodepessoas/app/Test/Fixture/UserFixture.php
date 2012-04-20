<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'null' => false,
					'default' => NULL, 'key' => 'primary'),
			'nusp' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 10, 'key' => 'unique',
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'name' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 70,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'email' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 50, 'key' => 'unique',
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'password' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 50,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'photo' => array('type' => 'string', 'null' => true,
					'default' => NULL, 'length' => 100,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'webpage' => array('type' => 'string', 'null' => true,
					'default' => NULL, 'length' => 100,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'lattes' => array('type' => 'string', 'null' => true,
					'default' => NULL, 'length' => 100,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'hash' => array('type' => 'string', 'null' => false,
					'default' => NULL, 'length' => 40,
					'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'user_type' => array('type' => 'string', 'default' => 'user'),
			'activation_status' => array('type' => 'string', 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true,
					'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true,
					'default' => NULL),
			'indexes' => array(
					'PRIMARY' => array('column' => 'id', 'unique' => 1),
					'nusp' => array('column' => 'nusp', 'unique' => 1),
					'email' => array('column' => 'email', 'unique' => 1)),
			'tableParameters' => array('charset' => 'latin1',
					'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'));

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
			array('id' => 1, 'nusp' => '54321',
					'name' => 'Lorem ipsum dolor sit amet',
					'email' => 'test@cadastrodepessoas.com.br',
					'password' => '5d4ec527e929f6b5d1715451f7b3d37f4da56326',
					'photo' => 'Lorem ipsum dolor sit amet',
					'hash' => 'Lorem ipsum dolor sit amet',
					'user_type' => 'user', 'created' => '2012-03-23 15:48:25',
					'modified' => '2012-03-23 15:48:25',
					'activation_status' => 'waiting_activation'),
			array('id' => 2, 'nusp' => '12345',
					'name' => 'Lorem ipsum dolor sit amet',
					'email' => 'test2@cadastrodepessoas.com.br',
					'password' => '5d4ec527e929f6b5d1715451f7b3d37f4da56326',
					'photo' => 'Lorem ipsum dolor sit amet',
					'hash' => 'MyBeautifulHash', 'user_type' => 'user',
					'created' => '2012-03-23 15:48:25',
					'modified' => '2012-03-23 15:48:25',
					'activation_status' => 'waiting_validation'),
			array('id' => 3, 'nusp' => '12345678',
					'name' => 'Lorem ipsum dolor sit amet',
					'email' => 'test3@cadastrodepessoas.com.br',
					'password' => '5d4ec527e929f6b5d1715451f7b3d37f4da56326',
					'photo' => 'Lorem ipsum dolor sit amet',
					'hash' => 'dhjlkhfdajlkhf', 'user_type' => 'user',
					'created' => '2012-03-23 15:48:25',
					'modified' => '2012-03-23 15:48:25',
					'activation_status' => 'active'),
			array('id' => 4, 'nusp' => '11111111',
					'name' => 'Lorem ipsum dolor sit amet',
					'email' => 'test4@cadastrodepessoas.com.br',
					'password' => '5d4ec527e929f6b5d1715451f7b3d37f4da56326',
					'photo' => 'Lorem ipsum dolor sit amet',
					'hash' => 'dhjlkhfdajlkhf', 'user_type' => 'user',
					'created' => '2012-03-23 15:48:25',
					'modified' => '2012-03-23 15:48:25',
					'activation_status' => 'waiting_activation'),
			array('id' => 5, 'nusp' => '9999999',
					'name' => 'Roberto Hirata',
					'email' => 'test5@cadastrodepessoas.com.br',
					'password' => '5d4ec527e929f6b5d1715451f7b3d37f4da56326',
					'photo' => 'Lorem ipsum dolor sit amet',
					'hash' => 'dhjlkhfdajlkhf', 'user_type' => 'admin',
					'created' => '2012-03-23 15:48:25',
					'modified' => '2012-03-23 15:48:25',
					'activation_status' => 'active'),
			array('id' => 6, 'nusp' => '424242',
					'name' => 'Lorem ipsum dolor sit amet',
					'email' => 'test6@cadastrodepessoas.com.br',
					'password' => '5d4ec527e929f6b5d1715451f7b3d37f4da56326',
					'photo' => 'Lorem ipsum dolor sit amet',
					'hash' => 'dhjlkhfdajlkhf', 'user_type' => 'user',
					'created' => '2012-03-23 15:48:25',
					'modified' => '2012-03-23 15:48:25',
					'activation_status' => 'active')
			);
			
}
