<?php
App::uses('Familia', 'Model');

/**
 * Familia Test Case
 */
class FamiliaTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.familia',
		'app.user',
		'app.imagene',
		'app.colegio',
		'app.nivele',
		'app.grado',
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
		$this->Familia = ClassRegistry::init('Familia');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Familia);

		parent::tearDown();
	}

}
