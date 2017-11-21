<?php
App::uses('AppModel', 'Model');
/**
 * Salone Model
 *
 * @property Ciclo $Ciclo
 * @property User $User
 * @property Grado $Grado
 * @property AlumnosInscrito $AlumnosInscrito
 */
class Salone extends AppModel {
	

//-------------------------------------------------------------------------


	public function saloneAgregarValidar($info)
	{
		$nombre = $info["Salone"]["nombre"];
		$ciclo_id = $info["Salone"]["ciclo_id"];
		@$user_id = $info["Salone"]["user_id"];
		$grado_id = $info["Salone"]["grado_id"];

		//Checa que los campos esten llenos
		$lleno = 1;
		if (empty($nombre)) $lleno = 0;
		if (empty($ciclo_id)) $lleno = 0;
		if (empty($user_id)) $lleno = 0;
		if (empty($grado_id)) $lleno = 0;

		if ($lleno == 0)
			return 'Favor de llenar todos los datos.';
		else
		{	
			//Checa que el nombre sea alfanumerico y las fechas solo de numeros y /
			$valido = 1;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $nombre)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $ciclo_id)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $user_id)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $grado_id)) $valido = 0;

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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Ciclo' => array(
			'className' => 'Ciclo',
			'foreignKey' => 'ciclo_id',
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
		),
		'Grado' => array(
			'className' => 'Grado',
			'foreignKey' => 'grado_id',
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
		'AlumnosInscrito' => array(
			'className' => 'AlumnosInscrito',
			'foreignKey' => 'salone_id',
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
