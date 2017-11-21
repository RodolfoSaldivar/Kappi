<?php 

	App::uses('Component', 'Controller');
	App::uses('File', 'Utility');

	class ExtrasComponent extends Component
	{
	    // the other component your component uses
	    public $components = array('Qimage');

	    public function agregarBDD($data, $obligatoria)
	    {	
	    	//Carga modelos necesarios
	    	$extraModel = ClassRegistry::init('Extra');
	    	$imageneModel = ClassRegistry::init('Imagene');
	    	$colegioModel = ClassRegistry::init('Colegio');

	        //Obtiene el metodo directamente del modelo de Familia
			$descripcion_valida = $extraModel->validarDescripcion($data['Extra']['descripcion']);
	        
	        if ($descripcion_valida != 1)
	        {
	        	return $descripcion_valida;
	        }
	        else
	        {
	        	//Obtiene el metodo directamente del modelo de Colegio
	        	if (empty($data['Extra']['imagen']['name']) && !$obligatoria)
	        	{
	        		$imagen_valida = 1;
	        	}
	        	else
	        		$imagen_valida = $colegioModel->logoAgregarValidar($data['Extra']['imagen'], $obligatoria);

	        	if ($imagen_valida != 1)
	        	{
	        		return $imagen_valida;
	        	}
	        	else
	        	{
	        		if (!empty($data['Extra']['imagen']['name']))
		        	{
		        		//Ruta en donde sera guardada la imagen
		        		$ruta = '/img/extras/';

			        	//Se produce el nombre de la imagen
			        	$nombre = $this->Qimage->traerNombre($ruta, $data['Extra']['imagen']);

			        	//Se prepara para guardarse en la bdd
			        	$datos_imagen['Imagene']['nombre'] = $nombre;
			        	$datos_imagen['Imagene']['ruta'] = $ruta;

			        	//Crea y guarda
			        	$imageneModel->query("
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

			        	$data['Extra']['imagene_id'] = $imagen_id;
		        	}


	        		//Se obtiene el id de la extra editada
		        	if (!empty($data['Extra']['id']))
		        	{
		        		$id_desencriptado = $colegioModel->desencriptacion($data['Extra']['id']);
		        		$data['Extra']['id'] = $id_desencriptado;
		        	}

		        	//Se guarda la extra o se edita
		        	if (empty($data['Extra']['id']))
		        	{
		        		$extraModel->query("
			        		INSERT INTO kappi.extras (descripcion, tipo, colegio_id, imagene_id)
			        		VALUES
			        		(
			        			'".$data['Extra']['descripcion']."',
			        			'".$data['Extra']['tipo']."',
			        			".$data['Extra']['colegio_id'].",
			        			".$data['Extra']['imagene_id']."
			        		)
			        	");
		        	}
		        	else
		        	{
		        		if (!empty($data['Extra']['imagene_id']))
		        			$query = " , imagene_id = ".$data['Extra']['imagene_id']." ";
		        		else
		        			$query = "";

		        		$extraModel->query("
			        		UPDATE kappi.extras
			        		SET descripcion = '".$data['Extra']['descripcion']."'
			        			$query
			        		WHERE id = ".$data['Extra']['id']."
			        	");
		        	}
			       
		        	//Se borra la imagen vieja y se quita de la carpeta
		        	if (!empty($data['Img']['ruta']) && !empty($data['Extra']['imagen']['name']))
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




	    public function agregarDestinos($destinatarios, $data)
	    {
	    	//Carga modelos necesarios
	    	$destExtrModel = ClassRegistry::init('DestinoExtra');
	    	$colegioModel = ClassRegistry::init('Colegio');
	    	

	    	//Fecha de envio del mensaje
			date_default_timezone_set('America/Mexico_City');
        	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			 
			$fecha = $dias[date('w')]." - ".$meses[date('n')-1]." ".date('d'). ", ".date('Y');

			$datos_destinatario["DestinoExtra"]["fecha"] = $fecha;
			$datos_destinatario["DestinoExtra"]["emisor"] = $data['DestinoExtra']['emisor'];


			//Por cada extra que se escogio
			foreach ($data['Extras'] as $keyExtra => $extra)
			{	
				//Como el id de cada extra se guarda en la llave, esa es la que se desencripta
				$extra_id = $colegioModel->desencriptacion($keyExtra);
        		$datos_destinatario["DestinoExtra"]["extra_id"] = $extra_id;

				//Manda el mensaje a todos los destinatarios
		    	foreach ($destinatarios as $keyDes => $destinatario)
	        	{
	        		unset($datos_destinatario['DestinoExtra']['hijo']);

		        	$alumno_id = $colegioModel->desencriptacion($destinatario["Alumno"]);
	        		$datos_destinatario["DestinoExtra"]["user_id"] = $alumno_id;

	        		//Crea y guarda
		        	$destExtrModel->query("
		        		INSERT INTO kappi.destino_extras (fecha, emisor, extra_id, user_id)
		        		VALUES
		        		(
		        			'".$datos_destinatario["DestinoExtra"]["fecha"]."',
		        			".$datos_destinatario["DestinoExtra"]["emisor"].",
		        			".$datos_destinatario["DestinoExtra"]["extra_id"].",
		        			".$datos_destinatario["DestinoExtra"]["user_id"]."
		        		)
		        	");

		        	//Si tiene padres registrados, se les manda tambien
		        	if (!empty($destinatario["Padre"]))
		        	{
		        		$padre_id = $colegioModel->desencriptacion($destinatario["Padre"]);
		        		$datos_destinatario["DestinoExtra"]["user_id"] = $padre_id;
		        		$datos_destinatario['DestinoExtra']['hijo'] = $alumno_id;

		        		//Crea y guarda
			        	$destExtrModel->query("
			        		INSERT INTO kappi.destino_extras (fecha, emisor, extra_id, user_id, hijo)
			        		VALUES
			        		(
			        			'".$datos_destinatario["DestinoExtra"]["fecha"]."',
			        			".$datos_destinatario["DestinoExtra"]["emisor"].",
			        			".$datos_destinatario["DestinoExtra"]["extra_id"].",
			        			".$datos_destinatario["DestinoExtra"]["user_id"].",
			        			".$datos_destinatario["DestinoExtra"]["hijo"]."
			        		)
			        	");
		        	}

		        	if (!empty($destinatario["Madre"]))
		        	{
		        		$madre_id = $colegioModel->desencriptacion($destinatario["Madre"]);
		        		$datos_destinatario["DestinoExtra"]["user_id"] = $madre_id;
		        		$datos_destinatario['DestinoExtra']['hijo'] = $alumno_id;

		        		//Crea y guarda
			        	$destExtrModel->query("
			        		INSERT INTO kappi.destino_extras (fecha, emisor, extra_id, user_id, hijo)
			        		VALUES
			        		(
			        			'".$datos_destinatario["DestinoExtra"]["fecha"]."',
			        			".$datos_destinatario["DestinoExtra"]["emisor"].",
			        			".$datos_destinatario["DestinoExtra"]["extra_id"].",
			        			".$datos_destinatario["DestinoExtra"]["user_id"].",
			        			".$datos_destinatario["DestinoExtra"]["hijo"]."
			        		)
			        	");
		        	}
	        	}
	        }

	        return 1;
	    }
	}

?>