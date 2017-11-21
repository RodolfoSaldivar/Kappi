<?php
App::uses('AppModel', 'Model');
/**
 * DestinoExtra Model
 *
 * @property User $User
 * @property Extra $Extra
 */
class DestinoExtra extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Extra' => array(
			'className' => 'Extra',
			'foreignKey' => 'extra_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
