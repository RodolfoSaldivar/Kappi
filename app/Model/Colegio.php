<?php
App::uses('AppModel', 'Model');
/**
 * Colegio Model
 *
 * @property Imagene $Imagene
 * @property Nivele $Nivele
 */
class Colegio extends AppModel {
	

//-------------------------------------------------------------------------


	public function encriptacion($id)
	{
		$id_encriptada = base64_encode(base64_encode(base64_encode($id)));
		$id_encriptada = substr($id_encriptada, 0, -1);

		return $id_encriptada;
	}
	

//-------------------------------------------------------------------------


	public function desencriptacion($id)
	{
		$id = $id."=";
		$id_desencriptada =	base64_decode(base64_decode(base64_decode($id)));

		return $id_desencriptada;
	}
	

//-------------------------------------------------------------------------


	public function validarIdSelect($id_encriptada)
	{
		$valido = 1;
		if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $id_encriptada))
			$valido = 0;

		return $valido;
	}
	

//-------------------------------------------------------------------------


	public function colegioAgregarValidar($info)
	{
		$comercial = $info["Colegio"]["nombre_comercial"];
		$corto = $info["Colegio"]["nombre_corto"];
		$telefono = $info["Colegio"]["telefono"];
		$razon = $info["Colegio"]["razon_social"];

		//Checa que los campos esten llenos
		$lleno = 1;
		if (empty($comercial)) $lleno = 0;
		if (empty($corto)) $lleno = 0;
		if (empty($telefono)) $lleno = 0;
		if (empty($razon)) $lleno = 0;

		if ($lleno == 0)
			return 'Favor de llenar todos los datos.';
		else
		{	
			//Checa que los campos sean alfanumericos
			$valido = 1;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $comercial)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $corto)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $telefono)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $razon)) $valido = 0;

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


	public function logoAgregarValidar($imagen, $obligatoria)
	{
		if ($imagen['error'] != null && $obligatoria)
		{
			return "Hay un error en esa foto.";
		}
		else
		{
			$valido = 1;

			$file_name = $imagen['name'];
			$file_size = $imagen['size'];
			$file_tmp = $imagen['tmp_name'];
			$file_type = $imagen['type'];
			$file_ext = @strtolower(end(explode('.',$imagen['name'])));

			$expensions = array("jpeg", "jpg", "png", "gif", "");

			if(in_array($file_ext,$expensions) === false)
			{
				$valido = 0;
				return "Solo extensiones Jpg, Png o Gif.";
			}

			if($file_size > 512000)
			{
				$valido = 0;
				return "La imagen exede los 500 KB.";
			}

			if ($valido == 1)
			{
				return 1;
			}
		}
	}
	

//-------------------------------------------------------------------------


	public function nivelesGradosValidos($niveles)
	{
		$valido = 1;

		foreach ($niveles as $key => $nivel)
		{
			//Checa que los campos esten llenos
			$lleno = 1;
			if (empty($nivel['nombre'])) $lleno = 0;

			foreach ($nivel['grado'] as $key => $grado)
			{
				if (empty($grado['nombre'])) $lleno = 0;
			}

			//Checa que los campos sean alfanumericos
			$valido = 1;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $nivel['nombre'])) $valido = 0;

			foreach ($nivel['grado'] as $key => $grado)
			{
				if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $grado['nombre'])) $valido = 0;
				if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $grado['identificador'])) $valido = 0;
			}
		}

		if ($lleno == 0)
			return 'Favor de llenar todos los datos.';
		else
		{
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
		'Nivele' => array(
			'className' => 'Nivele',
			'foreignKey' => 'colegio_id',
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
			'foreignKey' => 'colegio_id',
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
