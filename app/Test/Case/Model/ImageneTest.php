<?php
App::uses('Imagene', 'Model');

/**
 * Imagene Test Case
 */
class ImageneTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.imagene',
		'app.colegio',
		'app.nivele',
		'app.user',
		'app.familia',
		'app.alumnos_inscrito',
		'app.ciclos_escolare',
		'app.colegios_usuario',
		'app.comunicado',
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
		$this->Imagene = ClassRegistry::init('Imagene');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Imagene);

		parent::tearDown();
	}

}
