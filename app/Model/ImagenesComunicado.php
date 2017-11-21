<?php
App::uses('AppModel', 'Model');
/**
 * ImagenesComunicado Model
 *
 * @property Imagene $Imagene
 * @property Comunicado $Comunicado
 */
class ImagenesComunicado extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Imagene' => array(
			'className' => 'Imagene',
			'foreignKey' => 'imagene_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Comunicado' => array(
			'className' => 'Comunicado',
			'foreignKey' => 'comunicado_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
