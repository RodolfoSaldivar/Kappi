<?php
App::uses('AppModel', 'Model');
App::uses('File', 'Utility');
/**
 * Imagene Model
 *
 * @property Colegio $Colegio
 * @property User $User
 */
class Imagene extends AppModel {


	public function afterDelete() {
		$file = new File(WWW_ROOT.$this->ruta);
		$file->delete();
	}


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Colegio' => array(
			'className' => 'Colegio',
			'foreignKey' => 'imagene_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'imagene_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ImagenesComunicado' => array(
			'className' => 'ImagenesComunicado',
			'foreignKey' => 'imagene_id',
			'dependent' => false,
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
