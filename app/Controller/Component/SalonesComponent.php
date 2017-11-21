<?php 

	App::uses('Component', 'Controller');

	class SalonesComponent extends Component
	{
	    public function agregarBDD($data, $accion)
	    {	
	    	//Carga modelos necesarios
	    	$saloneModel = ClassRegistry::init('Salone');
	    	$aluInsModel = ClassRegistry::init('AlumnosInscrito');
	    	$colegioModel = ClassRegistry::init('Colegio');

	        //Obtiene el metodo directamente del modelo de Salone
			$valido = $saloneModel->saloneAgregarValidar($data);
	        
	        if ($valido != 1)
	        {
	        	return $valido;
	        }
	        else
	        {
	        	//Se obtiene el id del salon editado
	        	if (!empty($data['Salone']['id']))
	        	{
	        		$id_desencriptado = $colegioModel->desencriptacion($data['Salone']['id']);
	        		$data['Salone']['id'] = $id_desencriptado;
	        		$salone_id = $id_desencriptado;
	        	}

	        	//Se desencriptan los datos
	        	$data["Salone"]["ciclo_id"] = $colegioModel->desencriptacion($data["Salone"]["ciclo_id"]);
	        	$data["Salone"]["user_id"] = $colegioModel->desencriptacion($data["Salone"]["user_id"]);
	        	$data["Salone"]["grado_id"] = $colegioModel->desencriptacion($data["Salone"]["grado_id"]);

	        	//Se guarda el salon
		        if ($accion == "agregar")
		        {
		        	$saloneModel->query("
		        		INSERT INTO kappi.salones (nombre, ciclo_id, user_id, grado_id)
		        		VALUES
		        		(	
		        			'".$data["Salone"]["nombre"]."',
		        			".$data["Salone"]["ciclo_id"].",
		        			".$data["Salone"]["user_id"].",
		        			".$data["Salone"]["grado_id"]."
		        		)
		        	");
		        }
		        else
		        {
		        	$saloneModel->query("
		        		UPDATE kappi.salones
		        		SET nombre = '".$data["Salone"]["nombre"]."',
		        			user_id = ".$data["Salone"]["user_id"]."
		        		WHERE id = ".$data["Salone"]["id"]."
		        	");
		        }
		        	

	        	//Se obtiene el id del salon recien guardado
	        	if (empty($data['Salone']['id']))
	        	{
	        		$salone_id = $saloneModel->query("
		        		SELECT id
						FROM kappi.salones
						WHERE user_id = ".$data['Salone']['user_id']."
							AND created =
								(	SELECT MAX(created)
									FROM kappi.salones
									WHERE user_id = ".$data['Salone']['user_id']."
								)
		        	");
		        	$salone_id = $salone_id[0][0]['id'];
		        }

	        	//Borra cada registro de alumnos inscritos antes de editar el salon
	        	if ($accion != "agregar")
	        	{
	        		foreach ($accion as $key => $inscrito)
	        		{
	        			$aluInsModel->query("
	        				DELETE FROM kappi.alumnos_inscritos
	        				WHERE id = ".$inscrito["AlumnosInscrito"]["id"]."
	        			");
	        		}
	        	}

	        	//Por cada alumno que se inscribio
		        if (!empty($data['Inscritos']))
		        {
		        	//Se crea el registro del alumno inscrito en el salon
		        	foreach ($data['Inscritos'] as $key => $alumno)
		        	{
		        		$valido = $colegioModel->validarIdSelect($key);
			        	if ($valido == 1)
			        	{
			        		$user_id = $colegioModel->desencriptacion($key);

			        		//Se juntan los datos del usuario
			        		$datos_inscribir['AlumnosInscrito']['user_id'] = $user_id;
			        		$datos_inscribir['AlumnosInscrito']['salone_id'] = $salone_id;

			        		//Se edita el usuario
			        		$aluInsModel->query("
			        			INSERT INTO kappi.alumnos_inscritos (user_id, salone_id)
			        			VALUES
			        			(
			        				".$datos_inscribir['AlumnosInscrito']['user_id'].",
			        				".$datos_inscribir['AlumnosInscrito']['salone_id']."
			        			)
			        		");
			        	}
		        	}
		        }

		        return 1;	
	        }
	    }
	}

?>