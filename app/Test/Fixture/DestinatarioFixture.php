<?php
/**
 * Destinatario Fixture
 */
class DestinatarioFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'comunicado_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'fecha' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'visto' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'firmado' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'des_usu_id_idx' => array('column' => 'user_id', 'unique' => 0),
			'des_com_id_idx' => array('column' => 'comunicado_id', 'unique' => 0)
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
			'user_id' => 1,
			'comunicado_id' => 1,
			'fecha' => 'Lorem ipsum dolor sit amet',
			'visto' => 1,
			'firmado' => 1,
			'created' => '2016-10-11 04:17:54',
			'modified' => '2016-10-11 04:17:54'
		),
	);

}
