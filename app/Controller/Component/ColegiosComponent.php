<?php 

	App::uses('Component', 'Controller');
	App::uses('File', 'Utility');

	class ColegiosComponent extends Component
	{
	    // the other component your component uses
	    public $components = array('Qimage', 'Session');

	    public function agregarBDD($data, $obligatoria)
	    {	
	    	//Carga modelos necesarios
	    	$colegioModel = ClassRegistry::init('Colegio');
	    	$imageneModel = ClassRegistry::init('Imagene');
	    	$niveleModel = ClassRegistry::init('Nivele');
	    	$gradoModel = ClassRegistry::init('Grado');

	        //Obtiene el metodo directamente del modelo de Colegio
			$agregar_valido = $colegioModel->colegioAgregarValidar($data);
	        
	        if ($agregar_valido != 1)
	        {
	        	return $agregar_valido;
	        }
	        else
	        {
	        	//Obtiene el metodo directamente del modelo de Colegio
	        	if (empty($data['Colegio']['logo']['name']) && !$obligatoria)
	        	{
	        		$imagen_valida = 1;
	        	}
	        	else
	        		$imagen_valida = $colegioModel->logoAgregarValidar($data['Colegio']['logo'], $obligatoria);

	        	if ($imagen_valida != 1)
	        	{
	        		return $imagen_valida;
	        	}
	        	else
	        	{
	        		//Obtiene el metodo directamente del modelo de Colegio
		        	$niv_gra_validos = $colegioModel->nivelesGradosValidos($data['Colegio']['nivel']);

		        	if ($niv_gra_validos != 1)
		        	{
		        		return $niv_gra_validos;
		        	}
		        	else
		        	{
			        	if (!empty($data['Colegio']['logo']['name']))
			        	{
			        		//Ruta en donde sera guardada la imagen
			        		$ruta = '/img/logos/';

				        	//Se produce el nombre de la imagen
				        	$nombre = $this->Qimage->traerNombre($ruta, $data['Colegio']['logo']);

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

				        	$data['Colegio']['imagene_id'] = $imagen_id;
			        	}

			        	//Se guardan los niveles que tendran y se borran de la info especifica del colegio 
			        	$niveles = $data['Colegio']['nivel'];
			        	unset($data['Colegio']['nivel']);

			        	//Se obtiene el id del colegio
			        	if (!empty($data['Colegio']['id']))
			        	{
			        		$id = $colegioModel->desencriptacion($data['Colegio']['id']);
			        		$data['Colegio']['id'] = $id;
			        	}

				        if (empty($data['Colegio']['id']))
				        {
				        	//Se guarda el colegio
				        	$colegioModel->query("
				        		INSERT INTO kappi.colegios (telefono, razon_social, nombre_comercial, nombre_corto, imagene_id)
				        		VALUES
				        		(
				        			'".$data['Colegio']['telefono']."',
				        			'".$data['Colegio']['razon_social']."',
				        			'".$data['Colegio']['nombre_comercial']."',
				        			'".$data['Colegio']['nombre_corto']."',
				        			".$data['Colegio']['imagene_id']."
				        		)

				        	");
				        }
				        else
				        {
				        	if (!empty($data['Colegio']['imagene_id']))
				        		$query = " , imagene_id = ".$data['Colegio']['imagene_id']." ";
				        	else
				        		$query = "";

				        	$colegioModel->query("
				        		UPDATE kappi.colegios
				        		SET telefono = '".$data['Colegio']['telefono']."',
				        			razon_social = '".$data['Colegio']['razon_social']."',
				        			nombre_comercial = '".$data['Colegio']['nombre_comercial']."',
				        			nombre_corto = '".$data['Colegio']['nombre_corto']."'
				        			$query
				        		WHERE id = ".$data['Colegio']['id']."

				        	");
				        }
				        	

			        	//Se borra la imagen vieja y se quita de la carpeta
			        	if (!empty($data['Img']['ruta']) && !empty($data['Colegio']['logo']['name']))
			        	{
			        		$ruta_borrar = $data['Img']['ruta'];
			        		@$imageneModel->query("
			        			DELETE FROM kappi.imagenes WHERE id = ".$data['Img']['id']."
			        		");
			        		$file = new File(WWW_ROOT.$ruta_borrar);
							$file->delete();
			        	}

			        	//Se obtiene el id del colegio recien creado o del editado
			        	if (empty($data['Colegio']['id']))
			        	{
			        		$colegio_id = $colegioModel->query("
				        		SELECT id
								FROM kappi.colegios
								WHERE nombre_comercial = '".$data['Colegio']['nombre_comercial']."'
									AND created =
										(	SELECT MAX(created)
											FROM kappi.colegios
											WHERE nombre_comercial = '".$data['Colegio']['nombre_comercial']."'
										)
				        	");
				        	$colegio_id = $colegio_id[0][0]['id'];
				        }
			        	else
			        		$colegio_id = $data['Colegio']['id'];
			        	

			        	//Por cada nivel que haya
			        	foreach ($niveles as $key => $nivel)
			        	{
			        		//Se juntan los datos del nivel
			        		$datos_nivel['Nivele']['colegio_id'] = $colegio_id;
			        		$datos_nivel['Nivele']['nombre'] = $nivel['nombre'];

				        	if (!empty($nivel['id']))
				        		$datos_nivel['Nivele']['id'] = $nivele_id = $nivel['id'];
				        	else
				        		unset($datos_nivel['Nivele']['id']);

			        		//Se crea y guarda el nivel; se obtiene el id de este
				        	if (empty($nivel['id']))
				        	{
								$niveleModel->query("
				        			INSERT INTO kappi.niveles (colegio_id, nombre)
				        			VALUES
				        			(
				        				".$datos_nivel['Nivele']['colegio_id'].",
				        				'".$datos_nivel['Nivele']['nombre']."'
				        			)
				        		");
				        	}
				        	else
				        	{
				        		$niveleModel->query("
				        			UPDATE kappi.niveles
				        			SET nombre = '".$datos_nivel['Nivele']['nombre']."'
				        			WHERE id = ".$datos_nivel['Nivele']['id']."
				        		");
				        	}
				        		
				        	if (empty($nivel['id']))
				        	{
				        		$nivele_id = $niveleModel->query("
					        		SELECT id
									FROM kappi.niveles
									WHERE nombre = '".$datos_nivel['Nivele']['nombre']."'
										AND created =
											(	SELECT MAX(created)
												FROM kappi.niveles
												WHERE nombre = '".$datos_nivel['Nivele']['nombre']."'
											)
					        	");
					        	$nivele_id = $nivele_id[0][0]['id'];
				        	}

			        		//Se pone en 0 como booleano
			        		$grado_id = 0;

				        	//Por cada grado que tenga el nivel recien guardado
				        	foreach ($nivel['grado'] as $keyGrado => $grado)
				        	{
				        		//Para saber que grado va antes de que grado
				        		if ($grado_id != 0)
				        			$datos_grado['Grado']['grado_id'] = $grado_id;
				        		else
				        			if (!empty($datos_grado['Grado']['grado_id']))
				        				unset($datos_grado['Grado']['grado_id']);

				        		$datos_grado['Grado']['nivele_id'] = $nivele_id;
				        		$datos_grado['Grado']['nombre'] = $grado["nombre"];
				        		$datos_grado['Grado']['identificador'] = $grado["identificador"];

				        		if (!empty($grado['id']))
				        			$datos_grado['Grado']['id'] = $grado["id"];
				        		else
				        			unset($datos_grado['Grado']['id']);

					        	if (empty($grado['id']))
					        	{
					        		$campos = "identificador, nombre, nivele_id";
					        		$valores = "
					        			'".$datos_grado["Grado"]["identificador"]."',
					        			'".$datos_grado["Grado"]["nombre"]."',
					        			".$datos_grado["Grado"]["nivele_id"]."
					        		";

					        		if (!empty($datos_grado['Grado']['grado_id']))
						        	{
						        		$campos = $campos.", grado_id";
						        		$valores = $valores.", ".$datos_grado["Grado"]["grado_id"]."";
						        	}

					        		//Se crea y guarda el grado
					        		$gradoModel->query("
					        			INSERT INTO kappi.grados ($campos)
					        			VALUES ($valores)
					        		");
					        	}
					        	else
					        	{
					        		$valores = "
					        			identificador = '".$datos_grado["Grado"]["identificador"]."',
					        			nombre = '".$datos_grado["Grado"]["nombre"]."'
					        		";

					        		if (!empty($datos_grado['Grado']['grado_id']))
						        	{
						        		$valores = $valores.", grado_id = ".$datos_grado["Grado"]["grado_id"]."";
						        	}

					        		//Se crea y guarda el grado
					        		$gradoModel->query("
					        			UPDATE kappi.grados
					        			SET $valores
					        			WHERE id = ".$datos_grado['Grado']['id']."
					        		");
					        	}
					        		

					        	if (empty($grado['id']))
					        	{
					        		$grado_id = $niveleModel->query("
						        		SELECT id
										FROM kappi.grados
										WHERE nombre = '".$datos_grado['Grado']['nombre']."'
											AND created =
												(	SELECT MAX(created)
													FROM kappi.grados
													WHERE nombre = '".$datos_grado['Grado']['nombre']."'
												)
						        	");
						        	$grado_id = $grado_id[0][0]['id'];
					        	}
					        	else
					        		$grado_id = $grado["id"];
				        	}
			        	}

				        return 1;
		        	}
	        	}	
	        }
	    }
	}

?>