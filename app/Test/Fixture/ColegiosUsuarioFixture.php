<?php
/**
 * ColegiosUsuario Fixture
 */
class ColegiosUsuarioFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'activo' => array('type' => 'integer', 'null' => true, 'default' => '1', 'unsigned' => false),
		'colegio_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'cous_col_id_idx' => array('column' => 'colegio_id', 'unique' => 0),
			'cous_usu_id_idx' => array('column' => 'user_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'activo' => 1,
			'colegio_id' => 1,
			'user_id' => 1,
			'created' => '2016-08-02 15:37:31',
			'modified' => '2016-08-02 15:37:31'
		),
	);

}
