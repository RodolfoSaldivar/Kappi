<?php
App::uses('User', 'Model');

/**
 * User Test Case
 */
class UserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		'app.destino_extra',
		'app.extra'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

}
