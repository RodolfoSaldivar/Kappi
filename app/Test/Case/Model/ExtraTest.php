<?php
App::uses('Extra', 'Model');

/**
 * Extra Test Case
 */
class ExtraTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.extra',
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
		'app.dispositivo',
		'app.archivo',
		'app.imagenes_comunicado',
		'app.destino_extra'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Extra = ClassRegistry::init('Extra');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Extra);

		parent::tearDown();
	}

}
