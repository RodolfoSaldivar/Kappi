<?php 

	App::uses('Component', 'Controller');
	App::uses('File', 'Utility');
	App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

	class UsuariosComponent extends Component
	{
	    // the other component your component uses
	    public $components = array('Qimage', 'Session');

	    public function agregarBDD($data, $imgObligatoria, $correoYcelularObligatorio, $tipo, $editado)
	    {	
	    	//Carga modelos necesarios
	    	$userModel = ClassRegistry::init('User');
	    	$colegioModel = ClassRegistry::init('Colegio');
	    	$imageneModel = ClassRegistry::init('Imagene');

	        //Obtiene el metodo directamente del modelo de Colegio
			$agregar_valido = $userModel->agregarUsuarioValidar($data, $correoYcelularObligatorio);
	        
	        if ($agregar_valido != 1)
	        {
	        	return $agregar_valido;
	        }
	        else
	        {
	        	//Obtiene el metodo directamente del modelo de Colegio
	        	if (empty($data['User']['foto']['name']) && !$imgObligatoria)
	        	{
	        		$imagen_valida = 1;
	        	}
	        	else
	        		$imagen_valida = $colegioModel->logoAgregarValidar($data['User']['foto'], $imgObligatoria);

	        	if ($imagen_valida != 1)
	        	{
	        		return $imagen_valida;
	        	}
	        	else
	        	{
	        		if (!empty($data['User']['foto']['name']))
		        	{
		        		//Ruta en donde sera guardada la imagen
		        		$ruta = '/img/usuarios/';

			        	//Se produce el nombre de la imagen
			        	$nombre = $this->Qimage->traerNombre($ruta, $data['User']['foto']);

			        	//Se prepara para guardarse en la bdd
			        	$datos_imagen['Imagene']['nombre'] = $nombre;
			        	$datos_imagen['Imagene']['ruta'] = $ruta;

			        	//Crea y guarda
			        	$imageneModel->query("
			        		INSERT INTO kappi.imagenes (nombre, ruta)
			        		VALUES ('$nombre', '$ruta')
			        	");

			        	//Se obtiene el id de la imagen recien guardada
			        	$imagen_id = $imageneModel->query("
			        		SELECT id
							FROM kappi.imagenes
							WHERE nombre = '$nombre'
								AND created =
									(	SELECT MAX(created)
										FROM kappi.imagenes
										WHERE nombre = '$nombre'
									)
			        	");
			        	$imagen_id = $imagen_id[0][0]['id'];

			        	$data['User']['imagene_id'] = $imagen_id;
		        	}

		        	//Si no cambió la contraseña se quita para que no vuelva a encriptar
	        		if (!empty($data['Img']['usuario_password']))
	        		{
	        			if ($data['User']['password'] === $data['Img']['usuario_password'])
	        				unset($data['User']['password']);
	        		}

		        	//Se crea el username: primer letra nombre, primer apellido, número de repeticiones de ese apellido
			        if (!$editado)
			        {
			        	$username;
			        	$primerLetra = substr($data['User']['nombre'], 0, 1);
			        	$a_paterno = $data['User']['a_paterno'];
			        	$username = $primerLetra.$a_paterno;

			        	//Se reemplazan los acentos y espacios
			        	$username = str_replace("á", "a", $username);
			        	$username = str_replace("é", "e", $username);
			        	$username = str_replace("í", "i", $username);
			        	$username = str_replace("ó", "o", $username);
			        	$username = str_replace("ú", "u", $username);
			        	$username = str_replace("ñ", "n", $username);
			        	$username = str_replace(" ", "", $username);

			        	//Se pasa a minusculas
			        	$username = strtolower($username);
			        	
		    			$cantidad = strlen($username);

			        	$repeticion = $userModel->find('count', array(
			        		'conditions' => array('SUBSTRING(User.username, 1, '.$cantidad.')' => $username)
			        	));

			        	if ($repeticion != 0)
			        		$username = $username.$repeticion;

			        	$data['User']['username'] = $username;

			        	$data['User']['tipo'] = $tipo;

			        	//Se crea clave
			        	$data['User']['clave'] = $userModel->randomString();
			        }

		        	//Se obtiene el id del usuario
		        	if (!empty($data['User']['id']))
		        	{
		        		$id = $colegioModel->desencriptacion($data['User']['id']);
		        		$data['User']['id'] = $id;
		        	}

		        	//Si se escogio la familia a la que pertenece
		        	if (!empty($data['Familia']))
		        	{
		        		$valido = $colegioModel->validarIdSelect($data['Familia']);
		        		if ($valido == 1)
		        		{
		        			$familia_id = $colegioModel->desencriptacion($data['Familia']);
			        		$data['User']['familia_id'] = $familia_id;
		        		}
		        	}

		        	//Se pone el colegio
		        	$data['User']['colegio_id'] = $this->Session->read('mi_colegio');
			        
		        	//Si es alumno se le asigna el primer grado al que se inscribe
			        if (!empty($data['User']['grado_id']))
			        {
			        	$valido = $colegioModel->validarIdSelect($data['User']['grado_id']);
			        	if ($valido == 1)
			        	{
			        		$grado_id = $colegioModel->desencriptacion($data['User']['grado_id']);
			        		$data['User']['grado_id'] = $grado_id;
			        	}
			        }

				    if (!$editado)
				    {
				        $blowF = new BlowfishPasswordHasher();
						$contra_encr = $blowF->hash($data["User"]["password"]);
						$data["User"]["password"] = $contra_encr;

				    	$campos = "identificador, username, password, nombre, a_paterno, a_materno, tipo, clave, colegio_id";
				        $valores = "
				        	'".$data["User"]["identificador"]."',
		        			'".$data["User"]["username"]."',
		        			'".$data["User"]["password"]."',
		        			'".$data["User"]["nombre"]."',
		        			'".$data["User"]["a_paterno"]."',
		        			'".$data["User"]["a_materno"]."',
		        			'".$data["User"]["tipo"]."',
		        			'".$data["User"]["clave"]."',
		        			".$data["User"]["colegio_id"]."
				        ";

			        	if (!empty($data['User']['correo']))
			        	{
			        		$campos = $campos.", correo";
			        		$valores = $valores.", '".$data["User"]["correo"]."'";
			        	}
			        	if (!empty($data['User']['celular']))
			        	{
			        		$campos = $campos.", celular";
			        		$valores = $valores.", '".$data["User"]["celular"]."'";
			        	}
			        	if (!empty($data['User']['imagene_id']))
			        	{
			        		$campos = $campos.", imagene_id";
			        		$valores = $valores.", ".$data["User"]["imagene_id"];
			        	}
			        	if (!empty($data['User']['familia_id']))
			        	{
			        		$campos = $campos.", familia_id";
			        		$valores = $valores.", ".$data["User"]["familia_id"];
			        	}
			        	if (!empty($data['User']['grado_id']))
			        	{
			        		$campos = $campos.", grado_id";
			        		$valores = $valores.", ".$data["User"]["grado_id"];
			        	}

			        	//Se guarda el usuario
			        	$userModel->query("
			        		INSERT INTO kappi.users ($campos)
			        		VALUES ($valores)
			        	");
				    }
				    else
				    {
				    	$valores = "
				    		identificador = '".$data["User"]["identificador"]."',
				    		nombre = '".$data["User"]["nombre"]."',
				    		a_paterno = '".$data["User"]["a_paterno"]."',
				    		a_materno = '".$data["User"]["a_materno"]."'
				    	";

			        	if (!empty($data['User']['password']))
				        {
				        	$blowF = new BlowfishPasswordHasher();
							$contra_encr = $blowF->hash($data["User"]["password"]);
			        		$valores = $valores.", password = '".$contra_encr."'";
			        	}
			        	if (!empty($data['User']['correo']))
			        	{
			        		$valores = $valores.", correo = '".$data["User"]["correo"]."'";
			        	}
			        	else
			        		$valores = $valores.", correo = NULL";

			        	if (!empty($data['User']['celular']))
			        	{
			        		$valores = $valores.", celular = '".$data["User"]["celular"]."'";
			        	}
			        	else
			        		$valores = $valores.", celular = NULL";

			        	if (!empty($data['User']['imagene_id']))
			        	{
			        		$valores = $valores.", imagene_id = ".$data["User"]["imagene_id"];
			        	}

			        	if (!empty($data['User']['familia_id']))
			        	{
			        		$valores = $valores.", familia_id = ".$data["User"]["familia_id"];
			        	}

			        	if (!empty($data['User']['grado_id']))
			        	{
			        		$valores = $valores.", grado_id = ".$data["User"]["grado_id"];
			        	}


			        	//Se edita el usuario
			        	$userModel->query("
			        		UPDATE kappi.users
			        		SET $valores
			        		WHERE id = ".$data["User"]["id"]."
			        	");
				    }
				        

		        	//Se borra la imagen vieja y se quita de la carpeta
		        	if (!empty($data['Img']['ruta']) && !empty($data['User']['foto']['name']))
		        	{
		        		$ruta_borrar = $data['Img']['ruta'];
		        		@$imageneModel->query("
		        			DELETE FROM kappi.imagenes WHERE id = ".$data['Img']['id']."
		        		");
		        		$file = new File(WWW_ROOT.$ruta_borrar);
						$file->delete();
		        	}

			        return 1;
	        	}
	        }
	    }
	}

?>