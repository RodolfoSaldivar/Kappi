<?php
App::uses('AppModel', 'Model');
/**
 * Extra Model
 *
 * @property Imagene $Imagene
 * @property DestinoExtra $DestinoExtra
 */
class Extra extends AppModel {
	

//-------------------------------------------------------------------------


	public function validarDescripcion($descripcion)
	{
		//Checa que los campos esten llenos
		$lleno = 1;
		if (empty($descripcion)) $lleno = 0;

		if ($lleno == 0)
			return 'Favor de llenar el descripción.';
		else
		{	
			//Checa que los campos sean alfanumericos
			$valido = 1;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $descripcion)) $valido = 0;

			if ($valido == 0)
				return 'Solo letras y números.';
			else
			{
				//Pasó las condiciones
				return 1;
			}
		}
	}
	

//-------------------------------------------------------------------------


	public function traerExtrasEnviados($user_id, $user_tipo, $colegio_id, $extra_tipo)
	{
		$user_model = ClassRegistry::init('User');
		$colegio_model = ClassRegistry::init('Colegio');
		$destExtr_model = ClassRegistry::init('DestinoExtra');
		$enviados;

		if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador")
		{
			$enviados = $destExtr_model->find('all', array(
				'fields' => array(
					'MIN(DestinoExtra.id) as id', 'MIN(fecha) as fecha', 'MIN(emisor) as emisor'
				),
				'conditions' => array(
					'Extra.tipo' => $extra_tipo,
					'Extra.colegio_id' => $colegio_id
				),
				'order' => array(
					'MIN(DestinoExtra.created)' => 'desc'
				),
				'group' => array(
					'DestinoExtra.fecha', 'DestinoExtra.emisor'
				),
				'recursive' => 0
			));

			foreach ($enviados as $key => $enviado)
			{
				$profe = $user_model->find('first', array(
					'conditions' => array(
						'User.id' => $enviado[0]['emisor']
					),
					'fields' => array(
						'nombre', 'a_paterno', 'a_materno'
					)
				));

				$enviados[$key]['Profe'] = $profe['User'];
			}
		}
		else
		{
			$enviados = $destExtr_model->find('all', array(
				'fields' => array(
					'MIN(DestinoExtra.id) as id', 'MIN(fecha) as fecha', 'MIN(emisor) as emisor'
				),
				'conditions' => array(
					'Extra.tipo' => $extra_tipo,
					'Extra.colegio_id' => $colegio_id,
					'DestinoExtra.emisor' => $user_id
				),
				'order' => array(
					'MIN(DestinoExtra.created)' => 'desc'
				),
				'group' => array(
					'DestinoExtra.fecha'
				),
				'recursive' => 0
			));
		}	

	    foreach ($enviados as $key => $enviado)
	    {
	    	$enviados[$key]['DestinoExtra']['id'] = $enviado[0]['id'];
	    	$enviados[$key]['DestinoExtra']['fecha'] = $enviado[0]['fecha'];
	    	$enviados[$key]['DestinoExtra']['emisor'] = $enviado[0]['emisor'];

	    	$emi_encrip = $colegio_model->encriptacion($enviado[0]['emisor']);
	    	$enviados[$key]['DestinoExtra']['emi_encrip'] = $emi_encrip;

	    	$id_encriptada = $colegio_model->encriptacion($enviado[0]['id']);
	    	$enviados[$key]['DestinoExtra']['id_encriptada'] = $id_encriptada;

	    	unset($enviados[$key][0]);
	    }

	    return $enviados;
	}
	

//-------------------------------------------------------------------------


	public function traerExtrasRecibidos($user_id, $user_tipo, $extra_tipo, $mi_hijo)
	{
		$colegio_model = ClassRegistry::init('Colegio');
		$imagene_model = ClassRegistry::init('Imagene');
		$destExtr_model = ClassRegistry::init('DestinoExtra');

		$condiciones = array(
			'DestinoExtra.user_id' => $user_id,
			'Extra.tipo' => $extra_tipo
		);

		if (in_array($user_tipo, array("Madre", "Padre")))
			$condiciones['DestinoExtra.hijo'] = $mi_hijo;

		$fechas = $destExtr_model->find('all', array(
			'conditions' => $condiciones,
			'group' => array('DestinoExtra.fecha'),
			'fields' => array('DestinoExtra.fecha', 'MIN(DestinoExtra.created) as cre'),
			'order' => array('cre' => 'desc'),
			'limit' => 50,
			'recursive' => 0
		));

		$recibidos;
		foreach ($fechas as $keyFe => $fecha)
		{
			$condiciones = array(
				'DestinoExtra.user_id' => $user_id,
				'DestinoExtra.fecha' => $fecha['DestinoExtra']['fecha'],
				'Extra.tipo' => $extra_tipo
			);

			if (in_array($user_tipo, array("Madre", "Padre")))
				$condiciones['DestinoExtra.hijo'] = $mi_hijo;
			
			$extras = $destExtr_model->find('all', array(
				'conditions' => $condiciones,
				'fields' => array('Extra.descripcion', 'Extra.imagene_id'),
				'order' => array('DestinoExtra.created' => 'desc'),
				'recursive' => 0
			));

			foreach ($extras as $keyEx => $extra)
			{
				$imagen = $imagene_model->find('first', array(
					'conditions' => array('Imagene.id' => $extra['Extra']['imagene_id']),
					'fields' => array('nombre', 'ruta')
				));

				$extras[$keyEx]['Imagene'] = $imagen['Imagene'];
			}

			$recibidos[$keyFe]['Fecha'] = $fecha['DestinoExtra']['fecha'];
			$recibidos[$keyFe]['Extras'] = $extras;
		}

	    return @$recibidos;
	}
	

//-------------------------------------------------------------------------


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
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'DestinoExtra' => array(
			'className' => 'DestinoExtra',
			'foreignKey' => 'extra_id',
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
