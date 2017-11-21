<?php
App::uses('Comunicado', 'Model');

/**
 * Comunicado Test Case
 */
class ComunicadoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.comunicado',
		'app.user',
		'app.imagene',
		'app.colegio',
		'app.nivele',
		'app.grado',
		'app.salone',
		'app.ciclo',
		'app.alumnos_inscrito',
		'app.familia',
		'app.destinatario',
		'app.dispositivo',
		'app.imagenes_comunicado'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Comunicado = ClassRegistry::init('Comunicado');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Comunicado);

		parent::tearDown();
	}

}
