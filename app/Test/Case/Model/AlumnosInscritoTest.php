<?php
App::uses('AlumnosInscrito', 'Model');

/**
 * AlumnosInscrito Test Case
 */
class AlumnosInscritoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.alumnos_inscrito',
		'app.salone',
		'app.ciclo',
		'app.comunicado',
		'app.user',
		'app.imagene',
		'app.colegio',
		'app.nivele',
		'app.grado',
		'app.familia',
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
		$this->AlumnosInscrito = ClassRegistry::init('AlumnosInscrito');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AlumnosInscrito);

		parent::tearDown();
	}

}
