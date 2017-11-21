<?php
App::uses('AppModel', 'Model');
/**
 * Nivele Model
 *
 * @property Colegio $Colegio
 * @property Grado $Grado
 */
class Nivele extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Colegio' => array(
			'className' => 'Colegio',
			'foreignKey' => 'colegio_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Grado' => array(
			'className' => 'Grado',
			'foreignKey' => 'nivele_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
