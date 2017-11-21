<?php
App::uses('DestinoExtra', 'Model');

/**
 * DestinoExtra Test Case
 */
class DestinoExtraTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.destino_extra',
		'app.user',
		'app.imagene',
		'app.colegio',
		'app.nivele',
		'app.grado',
		'app.salone',
		'app.ciclo',
		'app.comunicado',
		'app.destinatario',
		'app.archivo',
		'app.imagenes_comunicado',
		'app.alumnos_inscrito',
		'app.familia',
		'app.dispositivo',
		'app.extra'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->DestinoExtra = ClassRegistry::init('DestinoExtra');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->DestinoExtra);

		parent::tearDown();
	}

}
