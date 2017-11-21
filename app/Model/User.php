<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * User Model
 *
 * @property Imagene $Imagene
 * @property Familia $Familia
 * @property AlumnosInscrito $AlumnosInscrito
 * @property Salone $Salone
 * @property Comunicado $Comunicado
 * @property Destinatario $Destinatario
 * @property Dispositivo $Dispositivo
 */
class User extends AppModel {

	
//-------------------------------------------------------------------------

	
	public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['password'])) {
	        $passwordHasher = new BlowfishPasswordHasher();
	        $this->data[$this->alias]['password'] = $passwordHasher->hash(
	            $this->data[$this->alias]['password']
	        );
	    }
	    return true;
	}

	
//-------------------------------------------------------------------------

	
	public function randomString()
	{
		$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20) . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
		return $randomString;
	}
	

//-------------------------------------------------------------------------


	public function agregarUsuarioValidar($info, $obligatorio)
	{
		$identificador = $info["User"]["identificador"];
		$nombre = $info["User"]["nombre"];
		$a_paterno = $info["User"]["a_paterno"];
		$a_materno = $info["User"]["a_materno"];
		$password = $info["User"]["password"];
		@$celular = $info["User"]["celular"];
		@$correo = $info["User"]["correo"];

		//Checa que minimo 1 campo este lleno
		$lleno = 1;
		if (empty($identificador)) $lleno = 0;
		if (empty($nombre)) $lleno = 0;
		if (empty($a_paterno)) $lleno = 0;
		if (empty($a_materno)) $lleno = 0;
		if (empty($password)) $lleno = 0;
		if ($obligatorio)
		{
			if (empty($correo)) $lleno = 0;
			if (empty($celular)) $lleno = 0;
		}
			

		if ($lleno == 0)
			return 'Favor de llenar todos los datos.';
		else
		{	
			//Checa que los campos sean alfanumericos
			$valido = 1;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $identificador)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $nombre)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $a_paterno)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $a_materno)) $valido = 0;
			if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $password)) $valido = 0;
			if (!empty($correo))
				if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $correo)) $valido = 0;
			if (!empty($celular))
				if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $celular)) $valido = 0;

			if ($valido == 0)
				return 'Solo letras y números.';
			else
			{
				//Paso las condiciones
				return 1;
			}
		}
	}


//-------------------------------------------------------------------------

	
	public function traerUsuarios($colegio_id, $tipo)
	{
		$colegio_model = ClassRegistry::init('Colegio');

		$usuarios = $this->find('all', array(
			'conditions' => array(
				'User.tipo' => $tipo,
				'User.colegio_id' => $colegio_id
			),
			'order' => array(
				'User.activo' => 'desc',
				'User.a_paterno' => 'asc',
				'User.a_materno' => 'asc',
				'User.nombre' => 'asc'
			),
			'fields' => array(
				'User.nombre', 'User.a_paterno', 'User.a_materno',
				'User.identificador', 'User.username', 'User.correo',
				'User.activo', 'User.imagene_id', 'Imagene.ruta', 'Imagene.nombre'
			),
			'limit' => 75,
			'recursive' => 0
		));

	    foreach ($usuarios as $key => $usuario)
	    {
	    	$id_encriptada = $colegio_model->encriptacion($usuario['User']['id']);
	    	$usuarios[$key]['User']['encriptada'] = $id_encriptada;
	    }

	    return $usuarios;
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
		),
		'Familia' => array(
			'className' => 'Familia',
			'foreignKey' => 'familia_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Colegio' => array(
			'className' => 'Colegio',
			'foreignKey' => 'colegio_id',
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
			'foreignKey' => 'user_id',
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
			'foreignKey' => 'user_id',
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
		'Comunicado' => array(
			'className' => 'Comunicado',
			'foreignKey' => 'user_id',
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
		'Destinatario' => array(
			'className' => 'Destinatario',
			'foreignKey' => 'user_id',
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
		'DestinoExtra' => array(
			'className' => 'DestinoExtra',
			'foreignKey' => 'user_id',
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
