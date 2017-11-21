<?php 

App::uses('Component', 'Controller');

class ComunicadosComponent extends Component
{
    // the other component your component uses
    public $components = array('Qimage', 'Session');

    public function agregarBDD($destinatarios, $data)
    {
    	//Carga modelos necesarios
    	$colegioModel = ClassRegistry::init('Colegio');
    	$comunicadoModel = ClassRegistry::init('Comunicado');
    	$destinatarioModel = ClassRegistry::init('Destinatario');
    	$imagComModel = ClassRegistry::init('ImagenesComunicado');
    	$imageneModel = ClassRegistry::init('Imagene');
    	$archivoModel = ClassRegistry::init('Archivo');

        //Obtiene el metodo directamente del modelo de Ciclo
		$agregar_valido = $comunicadoModel->comunicadoAgregarValidar($data);
        
        if ($agregar_valido != 1)
        {
        	return $agregar_valido;
        }
        else
        {
        	$todasImagenesValidas = 1;
        	foreach ($data["Comunicado"]["Imagenes"] as $key => $imagen)
        	{
        		//Obtiene el metodo directamente del modelo de Colegio
	        	$imagen_valida = $colegioModel->logoAgregarValidar($imagen, false);

	        	if ($imagen_valida != 1)
	        	{
	        		$todasImagenesValidas = 0;
	        	}
        	}

        	if ($todasImagenesValidas != 1)
        	{
        		return "Alguna imagen no es valida, tiene formato incorrecto o pesa mas de 500 KB.";
        	}
        	else
        	{
	        	//Fecha de envio del mensaje
				date_default_timezone_set('America/Mexico_City');
	        	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
				$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
				 
				$fecha = $dias[date('w')].", ".date('g:i a')." - ".$meses[date('n')-1]." ".date('d'). ", ".date('Y');

				$data["Comunicado"]["fecha"] = $fecha;

	        	//Se verifica si se quizo guardar
	        	if (!empty($data["guardar"]))
	        		$data["Comunicado"]["guardado"] = 1;
	        	else
	        		$data["Comunicado"]["guardado"] = 0;

	        	//Se verifica tenga solicitud de firma
	        	if (!empty($data["firma"]))
	        		$data["Comunicado"]["firmado"] = 1;
	        	else
	        		$data["Comunicado"]["firmado"] = 0;

	        	//Se guarda el mensaje
	        	$comunicadoModel->query("
	        		INSERT INTO kappi.comunicados (asunto, mensaje, tipo, user_id, ciclo_id, fecha, guardado, firmado)
	        		VALUES
	        		(
	        			'".$data['Comunicado']['asunto']."',
	        			'".$data['Comunicado']['mensaje']."',
	        			'".$data['Comunicado']['tipo']."',
	        			".$data['Comunicado']['user_id'].",
	        			".$data['Comunicado']['ciclo_id'].",
	        			'".$data['Comunicado']['fecha']."',
	        			".$data['Comunicado']['guardado'].",
	        			".$data['Comunicado']['firmado']."
	        		)
	        	");

        		$datos_destinatario;

	        	//Se verifica tenga solicitud de firma
	        	if (!empty($data["firma"]))
	        		$datos_destinatario["Destinatario"]["firmado"] = 0;
	        	else
	        		$datos_destinatario["Destinatario"]["firmado"] = 2;

	        	//Se obtiene el id del mensaje recien creado
	        	$comunicado_id = $comunicadoModel->query("
	        		SELECT id
					FROM kappi.comunicados
					WHERE user_id = ".$data['Comunicado']['user_id']."
						AND created =
							(	SELECT MAX(created)
								FROM kappi.comunicados
								WHERE user_id = ".$data['Comunicado']['user_id']."
							)
	        	");
	        	$comunicado_id = $comunicado_id[0][0]['id'];

		        $datos_imagCom['ImagenesComunicado']['comunicado_id'] = $comunicado_id;
		        $datos_destinatario["Destinatario"]["comunicado_id"] = $comunicado_id;
		        $datos_archivo["Archivo"]["comunicado_id"] = $comunicado_id;

		        //Guarda los pdf reusados
		        if (!empty($data["Comunicado"]["PDF_reusado"]))
		        {
		        	foreach ($data["Comunicado"]["PDF_reusado"] as $key => $pdf)
		        	{
		        		$pdf_id = $colegioModel->desencriptacion($pdf);
		        		$usado = $archivoModel->find('first', array(
		        			'conditions' => array("Archivo.id" => $pdf_id),
		        			'fields' => array("Archivo.id", "Archivo.nombre")
		        		));

			            $datos_archivo["Archivo"]["nombre"] = $usado["Archivo"]["nombre"];

		        		//Crea y guarda
			        	$archivoModel->query("
			        		INSERT INTO kappi.archivos (nombre, comunicado_id)
			        		VALUES
			        		(
			        			'".$datos_archivo['Archivo']['nombre']."',
			        			".$datos_archivo['Archivo']['comunicado_id']."
			        		)
			        	");
		        	}
		        }

		        //Guarda las imagenes reusadas
	        	if (!empty($data["Comunicado"]["Imagenes_reusadas"]))
	        	{	
	        		foreach ($data["Comunicado"]["Imagenes_reusadas"] as $key => $imagen)
		        	{
		        		$imagen_r_id = $colegioModel->desencriptacion($imagen);
			        	$datos_imagCom['ImagenesComunicado']['imagene_id'] = $imagen_r_id;

			        	//Se guarda la relacion
			        	$imagComModel->query("
			        		INSERT INTO kappi.imagenes_comunicados (imagene_id, comunicado_id)
			        		VALUES
			        		(
			        			".$datos_imagCom['ImagenesComunicado']['imagene_id'].",
			        			".$datos_imagCom['ImagenesComunicado']['comunicado_id']."
			        		)
			        	");
		        	}
		        }		        

		        //Guarda los pdf
        		foreach ($data["Comunicado"]["PDF"] as $key => $pdf)
	        	{
		        	if (!empty($pdf['name']))
	        		{	
			            if(!empty($pdf['name']))
			            {
			                $file = $pdf;
			                $file['name'] = $this->limpiar($file['name']);
			                $datos_archivo["Archivo"]["nombre"] = substr(time(), 0, -1).$file['name'];
			              	
			                $guardado = $archivoModel->query("
				        		INSERT INTO kappi.archivos (nombre, comunicado_id)
				        		VALUES
				        		(
				        			'".$datos_archivo['Archivo']['nombre']."',
				        			".$datos_archivo['Archivo']['comunicado_id']."
				        		)
				        	");

			                if($guardado)
			                {
			                    move_uploaded_file($file['tmp_name'], WWW_ROOT.'/pdf/'.DS.substr(time(), 0, -1) .$file['name']);
			                }
			            }
			        }
	        	}

		        //Guarda las imagenes
        		foreach ($data["Comunicado"]["Imagenes"] as $key => $imagen)
	        	{
		        	if (!empty($imagen['name']))
	        		{	
		        		//Ruta en donde sera guardada la imagen
		        		$ruta = '/img/mensajes/';

			        	//Se produce el nombre de la imagen
			        	$nombre = $this->Qimage->traerNombre($ruta, $imagen);

			        	//Se prepara para guardarse en la bdd
			        	$datos_imagen['Imagene']['nombre'] = $nombre;
			        	$datos_imagen['Imagene']['ruta'] = $ruta;

			        	//Crea y guarda
			        	$imagComModel->query("
			        		INSERT INTO kappi.imagenes (nombre, ruta)
			        		VALUES
			        		(
			        			'".$datos_imagen['Imagene']['nombre']."',
			        			'".$datos_imagen['Imagene']['ruta']."'
			        		)
			        	");

			        	//Se obtiene el id de la imagen recien guardada
			        	$imagen_id = $imageneModel->query("
			        		SELECT id
							FROM kappi.imagenes
							WHERE nombre = '".$datos_imagen['Imagene']['nombre']."'
								AND created =
									(	SELECT MAX(created)
										FROM kappi.imagenes
										WHERE nombre = '".$datos_imagen['Imagene']['nombre']."'
									)
			        	");
			        	$imagen_id = $imagen_id[0][0]['id'];

			        	$datos_imagCom['ImagenesComunicado']['imagene_id'] = $imagen_id;

			        	//Se guarda la relacion
			        	$imagComModel->query("
			        		INSERT INTO kappi.imagenes_comunicados (imagene_id, comunicado_id)
			        		VALUES
			        		(
			        			".$datos_imagCom['ImagenesComunicado']['imagene_id'].",
			        			".$datos_imagCom['ImagenesComunicado']['comunicado_id']."
			        		)
			        	");
			        }
	        	}

	        	//Manda el mensaje a todos los destinatarios si el mensaje no fue solo guardado
	        	if ($data["action"] != "sg")
	        	{
		        	foreach ($destinatarios as $key => $destinatario)
		        	{
		        		unset($datos_destinatario['Destinatario']['hijo']);

			        	if ($data["modo"] == 1 || $data["modo"] == 2)
			        	{
				        	if (!empty($destinatario["Alumno"]))
				        	{
				        		$alumno_id = $destinatario["Alumno"];
				        		$datos_destinatario["Destinatario"]["user_id"] = $alumno_id;

				        		//Crea y guarda
					        	$destinatarioModel->query("
					        		INSERT INTO kappi.destinatarios (firmado, comunicado_id, user_id)
					        		VALUES
					        		(
					        			".$datos_destinatario["Destinatario"]["firmado"].",
					        			".$datos_destinatario["Destinatario"]["comunicado_id"].",
					        			".$datos_destinatario["Destinatario"]["user_id"]."
					        		)
					        	");
					        }
			        	}

			        	//Si tiene padres registrados, se les manda tambien
			        	if (!empty($destinatario["Padre"]))
			        	{
			        		$padre_id = $destinatario["Padre"];
			        		$datos_destinatario["Destinatario"]["user_id"] = $padre_id;
			        		if (empty($destinatario["Alumno"]))
			        			$datos_destinatario['Destinatario']['hijo'] = $destinatario["hijo_id"];
			        		else
			        			$datos_destinatario['Destinatario']['hijo'] = $destinatario["Alumno"];

			        		//Crea y guarda
				        	$destinatarioModel->query("
				        		INSERT INTO kappi.destinatarios (firmado, comunicado_id, user_id, hijo)
				        		VALUES
				        		(
				        			".$datos_destinatario["Destinatario"]["firmado"].",
				        			".$datos_destinatario["Destinatario"]["comunicado_id"].",
				        			".$datos_destinatario["Destinatario"]["user_id"].",
				        			".$datos_destinatario["Destinatario"]["hijo"]."
				        		)
				        	");
			        	}

			        	if (!empty($destinatario["Madre"]))
			        	{
			        		$madre_id = $destinatario["Madre"];
			        		$datos_destinatario["Destinatario"]["user_id"] = $madre_id;
			        		if (empty($destinatario["Alumno"]))
			        			$datos_destinatario['Destinatario']['hijo'] = $destinatario["hijo_id"];
			        		else
			        			$datos_destinatario['Destinatario']['hijo'] = $destinatario["Alumno"];

			        		//Crea y guarda
				        	$destinatarioModel->query("
				        		INSERT INTO kappi.destinatarios (firmado, comunicado_id, user_id, hijo)
				        		VALUES
				        		(
				        			".$datos_destinatario["Destinatario"]["firmado"].",
				        			".$datos_destinatario["Destinatario"]["comunicado_id"].",
				        			".$datos_destinatario["Destinatario"]["user_id"].",
				        			".$datos_destinatario["Destinatario"]["hijo"]."
				        		)
				        	");
			        	}
		        	}
	        	}

	        	return 1;
	        }
    	}
    }


//-------------------------------------------------------------------------


	function limpiar($string, $force_lowercase = true, $sucio = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]","}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;","â€”", "â€“", ",", "<",">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($sucio) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
    }
	

//-------------------------------------------------------------------------


	public function traerDestNiveles($colegio_id)
    {
    	//Carga modelos necesarios
    	$niveleModel = ClassRegistry::init('Nivele');

    	$niveles = $niveleModel->find('list', array(
    		'conditions' => array(
    			'Nivele.colegio_id' => $colegio_id
    		),
    		'fields' => array('id', 'id')
    	));

    	$alumnos = array();
    	foreach ($niveles as $nivel)
    	{
    		$alumons_nivel = $this->traerDestGrados($nivel);

    		$alumnos = $alumnos + $alumons_nivel;
    	}
		
		return $alumnos;
    }
	

//-------------------------------------------------------------------------


	public function traerDestGrados($nivele_id)
	{
    	//Carga modelos necesarios
    	$gradoModel = ClassRegistry::init('Grado');

    	$grados = $gradoModel->find('list', array(
    		'conditions' => array(
    			'Grado.nivele_id' => $nivele_id
    		),
    		'fields' => array('id', 'id')
    	));

    	$alumnos = array();
    	foreach ($grados as $grado)
    	{
    		$alumons_grado = $this->traerDestSalones($grado);

    		$alumnos = $alumnos + $alumons_grado;
    	}
		
		return $alumnos;
	}
	

//-------------------------------------------------------------------------


	public function traerDestSalones($grado_id)
	{
    	//Carga modelos necesarios
    	$saloneModel = ClassRegistry::init('Salone');

    	$salones = $saloneModel->find('all', array(
    		'recursive' => 0,
    		'conditions' => array(
    			'Salone.grado_id' => $grado_id,
    			'Ciclo.activo' => 1
    		),
    		'fields' => array(
    			'Salone.id'
    		)
    	));

    	$alumnos = array();
    	foreach ($salones as $key => $salon)
    	{
    		$alumons_salon = $this->destSalonEspecifico($salon['Salone']['id']);

    		$alumnos = $alumnos + $alumons_salon;
    	}
		
		return $alumnos;
	}
	

//-------------------------------------------------------------------------


	public function destSalonEspecifico($salone_id)
	{
    	//Carga modelos necesarios
    	$aluInsModel = ClassRegistry::init('AlumnosInscrito');

    	$alumnos = $aluInsModel->find('list', array(
    		'recursive' => 0,
    		'conditions' => array(
    			'AlumnosInscrito.salone_id' => $salone_id,
    		),
    		'fields' => array(
    			'AlumnosInscrito.user_id',
    			'AlumnosInscrito.user_id'
    		)
    	));

    	return $alumnos;
	}


//-------------------------------------------------------------------------


	public function traerFamilia($alumnos)
    {
    	//Carga modelos necesarios
    	$userModel = ClassRegistry::init('User');

    	$destinatarios;

        foreach (@$alumnos as $key => $value)
		{
			$user_id = $key;
			$familia_id = $userModel->find('first', array(
				'conditions' => array('User.id' => $user_id),
				'fields' => array('User.familia_id')
			));
			$familia_id = $familia_id["User"]["familia_id"];

			$destinatarios[$user_id]["Alumno"] = $user_id;
			
			if (!empty($familia_id))
			{
				$padre = $userModel->find('first', array(
					'conditions' => array(
						'User.familia_id' => $familia_id,
						'User.tipo' => 'Padre',
						'User.activo' => 1
					),
					'fields' => array('User.id')
				));

				$madre = $userModel->find('first', array(
					'conditions' => array(
						'User.familia_id' => $familia_id,
						'User.tipo' => 'Madre',
						'User.activo' => 1
					),
					'fields' => array('User.id')
				));

				@$padre_id = $padre["User"]["id"];
				@$madre_id = $madre["User"]["id"];

				if (!empty($padre_id))
					$destinatarios[$key]["Padre"] = $padre_id;
				if (!empty($madre_id))
					$destinatarios[$key]["Madre"] = $madre_id;
			}
		}

		return $destinatarios; 
    }
	

//-------------------------------------------------------------------------


	public function filtrarFamilia($toda_familia, $data)
	{
		//Cuando se manda a toda la familia
    	if (!empty($data['checkA']))
    	{
    		$destinatarios = $toda_familia;
    	}
    	else
    	{	//Cuando NO se manda a los alumnos
    		foreach ($toda_familia as $key => $familia)
    		{

    			$toda_familia[$key]['hijo_id'] = $toda_familia[$key]['Alumno'];
    			unset($toda_familia[$key]['Alumno']);

	    		//Cuando NO se manda a las madres
	    		if (empty($data['checkM']))
	    		{
	    			unset($toda_familia[$key]['Madre']);
	    		}

	    		//Cuando NO se manda a las padres
	    		if (empty($data['checkP']))
	    		{
	    			unset($toda_familia[$key]['Padre']);
	    		}
    		}

    		$destinatarios = $toda_familia;
    	}

    	return $destinatarios;
	}


}

?>