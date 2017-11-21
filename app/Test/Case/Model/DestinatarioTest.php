<?php
App::uses('Destinatario', 'Model');

/**
 * Destinatario Test Case
 */
class DestinatarioTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.destinatario',
		'app.user',
		'app.imagene',
		'app.colegio',
		'app.nivele',
		'app.grado',
		'app.salone',
		'app.ciclo',
		'app.comunicado',
		'app.imagenes_comunicado',
		'app.alumnos_inscrito',
		'app.familia',
		'app.dispositivo'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Destinatario = ClassRegistry::init('Destinatario');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Destinatario);

		parent::tearDown();
	}

}
