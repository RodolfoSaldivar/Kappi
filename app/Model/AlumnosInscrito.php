<?php
App::uses('AppModel', 'Model');
/**
 * AlumnosInscrito Model
 *
 * @property Salone $Salone
 * @property User $User
 */
class AlumnosInscrito extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Salone' => array(
			'className' => 'Salone',
			'foreignKey' => 'salone_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
