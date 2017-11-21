<?php
App::uses('Salone', 'Model');

/**
 * Salone Test Case
 */
class SaloneTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.salone',
		'app.ciclo',
		'app.user',
		'app.imagene',
		'app.colegio',
		'app.nivele',
		'app.grado',
		'app.familia',
		'app.alumnos_inscrito',
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
		$this->Salone = ClassRegistry::init('Salone');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Salone);

		parent::tearDown();
	}

}
