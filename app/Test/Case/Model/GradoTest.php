<?php
App::uses('Grado', 'Model');

/**
 * Grado Test Case
 */
class GradoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.grado',
		'app.nivele',
		'app.colegio',
		'app.imagene',
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
		$this->Grado = ClassRegistry::init('Grado');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Grado);

		parent::tearDown();
	}

}
