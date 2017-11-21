<?php
App::uses('AppModel', 'Model');
/**
 * Destinatario Model
 *
 * @property User $User
 * @property Comunicado $Comunicado
 */
class Destinatario extends AppModel {
	

//-------------------------------------------------------------------------

	
	public function traerMensajesRecibidos($colegio_id, $tipo)
	{
		$colegio_model = ClassRegistry::init('Colegio');

		$mensajes = $this->find('all', array(
			'conditions' => array(
				'Comunicado.tipo' => $tipo,
				'Ciclo.colegio_id' => $colegio_id
			),
			'order' => array(
				'Comunicado.created' => 'desc'
			),
			'recursive' => 0
		));

	    foreach ($mensajes as $key => $mensaje)
	    {
	    	$id_encriptada = $colegio_model->encriptacion($mensaje['Comunicado']['id']);
	    	$mensajes[$key]['Comunicado']['encriptada'] = $id_encriptada;
	    }

	    return $mensajes;
	}
	

//-------------------------------------------------------------------------


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
		'Comunicado' => array(
			'className' => 'Comunicado',
			'foreignKey' => 'comunicado_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
