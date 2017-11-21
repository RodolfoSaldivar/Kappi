<?php
App::uses('AppModel', 'Model');
/**
 * Familia Model
 *
 * @property User $User
 */
class Familia extends AppModel {
	

//-------------------------------------------------------------------------


	public function familiaValidarNombre($nombre)
	{
		//Checa que los campos esten llenos
		$lleno = 1;
		if (empty($nombre)) $lleno = 0;

		if ($lleno == 0)
			return 'Favor de llenar el nombre.';
		else
		{	
			//Checa que los campos sean alfanumericos
			$valido = 1;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $nombre)) $valido = 0;

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


	public function familiaValidarIdentificador($identificador)
	{
		//Checa que los campos esten llenos
		$lleno = 1;
		if (empty($identificador)) $lleno = 0;

		if ($lleno == 0)
			return 'Favor de llenar el identificador.';
		else
		{	
			//Checa que los campos sean alfanumericos
			$valido = 1;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $identificador)) $valido = 0;

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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'familia_id',
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
