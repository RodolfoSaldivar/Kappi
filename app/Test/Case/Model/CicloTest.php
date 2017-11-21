<?php
App::uses('Ciclo', 'Model');

/**
 * Ciclo Test Case
 */
class CicloTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.ciclo',
		'app.comunicado',
		'app.salone',
		'app.user',
		'app.imagene',
		'app.colegio',
		'app.nivele',
		'app.grado',
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
		$this->Ciclo = ClassRegistry::init('Ciclo');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Ciclo);

		parent::tearDown();
	}

}
