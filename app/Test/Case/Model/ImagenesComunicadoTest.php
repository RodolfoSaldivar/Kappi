<?php
App::uses('ImagenesComunicado', 'Model');

/**
 * ImagenesComunicado Test Case
 */
class ImagenesComunicadoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.imagenes_comunicado',
		'app.imagene',
		'app.colegio',
		'app.nivele',
		'app.grado',
		'app.salone',
		'app.ciclo',
		'app.comunicado',
		'app.user',
		'app.familia',
		'app.alumnos_inscrito',
		'app.destinatario',
		'app.dispositivo'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ImagenesComunicado = ClassRegistry::init('ImagenesComunicado');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ImagenesComunicado);

		parent::tearDown();
	}

}
