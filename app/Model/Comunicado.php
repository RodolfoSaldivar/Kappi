<?php
App::uses('AppModel', 'Model');
/**
 * Comunicado Model
 *
 * @property User $User
 * @property Ciclo $Ciclo
 * @property Destinatario $Destinatario
 * @property Imagene $Imagene
 */
class Comunicado extends AppModel {
	

//-------------------------------------------------------------------------


	public function comunicadoAgregarValidar($info)
	{
		$asunto = $info["Comunicado"]["asunto"];
		$mensaje = $info["Comunicado"]["mensaje"];

		//Checa que los campos esten llenos
		$lleno = 1;
		if (empty($asunto)) $lleno = 0;
		if (empty($mensaje)) $lleno = 0;

		if ($lleno == 0)
			return 'Favor de llenar todos los datos.';
		else
		{	
			//Checa que los campos sean alfanumerico
			$valido = 1;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $asunto)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>\r\n\u0085\u2028\u2029]/i', $mensaje)) $valido = 0;

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

	
	public function traerMensajesEnviados($user_id, $user_tipo, $colegio_id, $com_tipo)
	{
		$colegio_model = ClassRegistry::init('Colegio');
		$enviados;

		if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador")
		{
			$enviados = $this->query("
				SELECT com.id as com_id, user_id, com.ciclo_id as com_ciclo_id, mensaje, com.tipo as com_tipo, asunto, fecha, guardado, firmado, cic.nombre as cic_nombre, usu.id as usu_id, usu.nombre as usu_nombre, a_paterno, a_materno
				FROM kappi.comunicados as com, kappi.ciclos as cic, kappi.users as usu
				WHERE com.tipo = N'$com_tipo'
					AND cic.colegio_id = $colegio_id
					AND cic.activo = 1
					AND cic.id = com.ciclo_id
					AND usu.id = com.user_id
				ORDER BY com.created DESC
			");
		}
		else
		{
			$enviados = $this->query("
				SELECT com.id as com_id, user_id, com.ciclo_id as com_ciclo_id, mensaje, com.tipo as com_tipo, asunto, fecha, guardado, firmado, cic.nombre as cic_nombre, usu.id as usu_id 
				FROM kappi.comunicados as com, kappi.ciclos as cic, kappi.users as usu
				WHERE com.tipo = N'$com_tipo'
					AND com.user_id = $user_id
					AND cic.colegio_id = $colegio_id
					AND cic.activo = 1
					AND cic.id = com.ciclo_id
					AND usu.id = com.user_id
				ORDER BY com.created DESC
			");
		}


	    foreach ($enviados as $key => $mensaje)
	    {
	    	$enviados[$key]['Comunicado']['id'] = $mensaje[0]['com_id'];
	    	$enviados[$key]['Comunicado']['user_id'] = $mensaje[0]['user_id'];
	    	$enviados[$key]['Comunicado']['ciclo_id'] = $mensaje[0]['com_ciclo_id'];
	    	$enviados[$key]['Comunicado']['mensaje'] = $mensaje[0]['mensaje'];
	    	$enviados[$key]['Comunicado']['tipo'] = $mensaje[0]['com_tipo'];
	    	$enviados[$key]['Comunicado']['asunto'] = $mensaje[0]['asunto'];
	    	$enviados[$key]['Comunicado']['fecha'] = $mensaje[0]['fecha'];
	    	$enviados[$key]['Comunicado']['firmado'] = $mensaje[0]['firmado'];

	    	$id_encriptada = $colegio_model->encriptacion($mensaje[0]['com_id']);
	    	$enviados[$key]['Comunicado']['encriptada'] = $id_encriptada;

	    	$enviados[$key]['Ciclo']['nombre'] = $mensaje[0]['cic_nombre'];

	    	$enviados[$key]['User']['id'] = $mensaje[0]['usu_id'];
	    	@$enviados[$key]['User']['nombre'] = $mensaje[0]['usu_nombre'];
	    	@$enviados[$key]['User']['a_paterno'] = $mensaje[0]['a_paterno'];
	    	@$enviados[$key]['User']['a_materno'] = $mensaje[0]['a_materno'];

	    	unset($enviados[$key][0]);
	    }

	    return $enviados;
	}
	

//-------------------------------------------------------------------------

	
	public function traerMensajesGuardados($user_id, $user_tipo, $colegio_id, $com_tipo)
	{
		$colegio_model = ClassRegistry::init('Colegio');
		$enviados;

		if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador")
		{
			$enviados = $this->query("
				SELECT com.id as com_id, user_id, com.ciclo_id as com_ciclo_id, mensaje, com.tipo as com_tipo, asunto, fecha, guardado, firmado, cic.nombre as cic_nombre, usu.id as usu_id, usu.nombre as usu_nombre, a_paterno, a_materno
				FROM kappi.comunicados as com, kappi.ciclos as cic, kappi.users as usu
				WHERE com.tipo = N'$com_tipo'
					AND com.guardado = 1
					AND cic.colegio_id = $colegio_id
					AND cic.activo = 1
					AND cic.id = com.ciclo_id
					AND usu.id = com.user_id
				ORDER BY com.created DESC
			");
		}
		else
		{
			$enviados = $this->query("
				SELECT com.id as com_id, user_id, com.ciclo_id as com_ciclo_id, mensaje, com.tipo as com_tipo, asunto, fecha, guardado, firmado, cic.nombre as cic_nombre, usu.id as usu_id 
				FROM kappi.comunicados as com, kappi.ciclos as cic, kappi.users as usu
				WHERE com.tipo = N'$com_tipo'
					AND com.guardado = 1
					AND com.user_id = $user_id
					AND cic.colegio_id = $colegio_id
					AND cic.activo = 1
					AND cic.id = com.ciclo_id
					AND usu.id = com.user_id
				ORDER BY com.created DESC
			");
		}


	    foreach ($enviados as $key => $mensaje)
	    {
	    	$enviados[$key]['Comunicado']['id'] = $mensaje[0]['com_id'];
	    	$enviados[$key]['Comunicado']['user_id'] = $mensaje[0]['user_id'];
	    	$enviados[$key]['Comunicado']['ciclo_id'] = $mensaje[0]['com_ciclo_id'];
	    	$enviados[$key]['Comunicado']['mensaje'] = $mensaje[0]['mensaje'];
	    	$enviados[$key]['Comunicado']['tipo'] = $mensaje[0]['com_tipo'];
	    	$enviados[$key]['Comunicado']['asunto'] = $mensaje[0]['asunto'];
	    	$enviados[$key]['Comunicado']['fecha'] = $mensaje[0]['fecha'];
	    	$enviados[$key]['Comunicado']['guardado'] = $mensaje[0]['guardado'];
	    	$enviados[$key]['Comunicado']['firmado'] = $mensaje[0]['firmado'];

	    	$id_encriptada = $colegio_model->encriptacion($mensaje[0]['com_id']);
	    	$enviados[$key]['Comunicado']['encriptada'] = $id_encriptada;

	    	$enviados[$key]['Ciclo']['nombre'] = $mensaje[0]['cic_nombre'];

	    	$enviados[$key]['User']['id'] = $mensaje[0]['usu_id'];
	    	@$enviados[$key]['User']['nombre'] = $mensaje[0]['usu_nombre'];
	    	@$enviados[$key]['User']['a_paterno'] = $mensaje[0]['a_paterno'];
	    	@$enviados[$key]['User']['a_materno'] = $mensaje[0]['a_materno'];

	    	unset($enviados[$key][0]);
	    }

	    return $enviados;
	}
	

//-------------------------------------------------------------------------

	
	public function traerMensajesRecibidos($user_id, $user_tipo, $mensaje_tipo, $mi_hijo)
	{
		$colegio_model = ClassRegistry::init('Colegio');
		$destinatario_model = ClassRegistry::init('Destinatario');

		if (in_array($user_tipo, array("Madre", "Padre")))
			$dest_query = 	" AND Destinatario.hijo = ".$mi_hijo."
							AND Destinatario.comunicado_id = Comunicado.id";
		else
			$dest_query = " AND Destinatario.comunicado_id = Comunicado.id";

		$recibidos = $destinatario_model->query(
			"SELECT Destinatario.*, usu.a_paterno, usu.a_materno, usu.nombre, Comunicado.id as com_id, Comunicado.asunto, Comunicado.fecha as com_fecha
			FROM kappi.destinatarios as Destinatario, kappi.users as usu, kappi.comunicados as Comunicado, kappi.ciclos as Ciclo
			WHERE Destinatario.user_id = $user_id $dest_query
				AND Comunicado.tipo = N'$mensaje_tipo'
				AND usu.id = Comunicado.user_id
				AND Comunicado.ciclo_id = Ciclo.id
				AND Ciclo.activo = 1
			ORDER BY Comunicado.created DESC"
		);

	    foreach ($recibidos as $key => $datos)
	    {
	    	$recibidos[$key]['Comunicado']['id'] = $datos[0]['com_id'];
	    	$recibidos[$key]['Comunicado']['asunto'] = $datos[0]['asunto'];
	    	$recibidos[$key]['Comunicado']['fecha'] = $datos[0]['com_fecha'];

	    	$id_encriptada = $colegio_model->encriptacion($datos[0]['com_id']);
	    	$recibidos[$key]['Comunicado']['encriptada'] = $id_encriptada;

	    	$recibidos[$key]['User']['a_paterno'] = $datos[0]['a_paterno'];
	    	$recibidos[$key]['User']['a_materno'] = $datos[0]['a_materno'];
	    	$recibidos[$key]['User']['nombre'] = $datos[0]['nombre'];

	    	$recibidos[$key]['Destinatario']['id'] = $datos[0]['id'];
	    	$recibidos[$key]['Destinatario']['user_id'] = $datos[0]['user_id'];
	    	$recibidos[$key]['Destinatario']['comunicado_id'] = $datos[0]['comunicado_id'];
	    	$recibidos[$key]['Destinatario']['visto'] = $datos[0]['visto'];
	    	$recibidos[$key]['Destinatario']['fecha_visto'] = $datos[0]['fecha_visto'];
	    	$recibidos[$key]['Destinatario']['firmado'] = $datos[0]['firmado'];
	    	$recibidos[$key]['Destinatario']['fecha_firmado'] = $datos[0]['fecha_firmado'];
	    	$recibidos[$key]['Destinatario']['hijo'] = $datos[0]['hijo'];
	    	$recibidos[$key]['Destinatario']['created'] = $datos[0]['created'];
	    	$recibidos[$key]['Destinatario']['modified'] = $datos[0]['modified'];

	    	unset($recibidos[$key][0]);
	    }

	    return $recibidos;
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
		'Ciclo' => array(
			'className' => 'Ciclo',
			'foreignKey' => 'ciclo_id',
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
		'Destinatario' => array(
			'className' => 'Destinatario',
			'foreignKey' => 'comunicado_id',
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
		'Archivo' => array(
			'className' => 'Archivo',
			'foreignKey' => 'comunicado_id',
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


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Imagene' => array(
			'className' => 'Imagene',
			'joinTable' => 'imagenes_comunicados',
			'foreignKey' => 'comunicado_id',
			'associationForeignKey' => 'imagene_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

}
