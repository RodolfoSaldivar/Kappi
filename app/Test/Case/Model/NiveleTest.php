<?php
App::uses('Nivele', 'Model');

/**
 * Nivele Test Case
 */
class NiveleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		'app.dispositivo',
		'app.grado'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Nivele = ClassRegistry::init('Nivele');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Nivele);

		parent::tearDown();
	}

}
