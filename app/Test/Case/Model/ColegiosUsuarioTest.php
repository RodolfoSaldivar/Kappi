<?php
App::uses('ColegiosUsuario', 'Model');

/**
 * ColegiosUsuario Test Case
 */
class ColegiosUsuarioTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.colegios_usuario',
		'app.colegio',
		'app.nivele',
		'app.user',
		'app.familia',
		'app.alumnos_inscrito',
		'app.ciclos_escolare',
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
		$this->ColegiosUsuario = ClassRegistry::init('ColegiosUsuario');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ColegiosUsuario);

		parent::tearDown();
	}

}
