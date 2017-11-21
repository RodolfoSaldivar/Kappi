<?php
App::uses('AppModel', 'Model');
/**
 * Ciclo Model
 *
 * @property Comunicado $Comunicado
 * @property Salone $Salone
 */
class Ciclo extends AppModel {
	

//-------------------------------------------------------------------------


	public function cicloAgregarValidar($info)
	{
		$nombre = $info["Ciclo"]["nombre"];
		$fecha_inicio = $info["Ciclo"]["fecha_inicio"];
		$fecha_fin = $info["Ciclo"]["fecha_fin"];

		//Checa que los campos esten llenos
		$lleno = 1;
		if (empty($nombre)) $lleno = 0;
		if (empty($fecha_inicio)) $lleno = 0;
		if (empty($fecha_fin)) $lleno = 0;

		if ($lleno == 0)
			return 'Favor de llenar todos los datos.';
		else
		{	
			//Checa que el nombre sea alfanumerico y las fechas solo de numeros y /
			$valido = 1;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $nombre)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $fecha_inicio)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $fecha_fin)) $valido = 0;

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


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Comunicado' => array(
			'className' => 'Comunicado',
			'foreignKey' => 'ciclo_id',
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
		'Salone' => array(
			'className' => 'Salone',
			'foreignKey' => 'ciclo_id',
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
