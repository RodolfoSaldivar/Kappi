<?php
App::uses('Colegio', 'Model');

/**
 * Colegio Test Case
 */
class ColegioTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.colegio',
		'app.imagene',
		'app.nivele'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Colegio = ClassRegistry::init('Colegio');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Colegio);

		parent::tearDown();
	}

}
