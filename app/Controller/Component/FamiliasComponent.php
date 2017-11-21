<?php 

	App::uses('Component', 'Controller');

	class FamiliasComponent extends Component
	{
	    public function agregarBDD($data)
	    {	
	    	//Carga modelos necesarios
	    	$familiaModel = ClassRegistry::init('Familia');
	    	$usuarioModel = ClassRegistry::init('User');
	    	$colegioModel = ClassRegistry::init('Colegio');

	        //Obtiene el metodo directamente del modelo de Familia
			$nombre_valido = $familiaModel->familiaValidarNombre($data['Familia']['nombre']);
	        
	        if ($nombre_valido != 1)
	        {
	        	return $nombre_valido;
	        }
	        else
	        {
				$identificador_valido = $familiaModel->familiaValidarIdentificador($data['Familia']['identificador']);

		        if ($identificador_valido != 1)
		        {
		        	return $identificador_valido;
		        }
		        else
		        {
		        	//Se obtiene el id de la familia editada
		        	if (!empty($data['Familia']['id']))
		        	{
		        		$id_desencriptado = $colegioModel->desencriptacion($data['Familia']['id']);
		        		$data['Familia']['id'] = $id_desencriptado;
		        		$familia_id = $id_desencriptado;

		        		//Se guarda la familia
		        		$familiaModel->query("
		        			UPDATE kappi.familias
		        			SET identificador = '".$data['Familia']['identificador']."',
		        				nombre = '".$data['Familia']['nombre']."'
		        			WHERE id = ".$data['Familia']['id']."
		        		");
		        	}
		        	else
		        	{
		        		//Se guarda la familia
		        		$familiaModel->query("
		        			INSERT INTO kappi.familias (identificador, nombre)
		        			VALUES 
		        			(
		        				'".$data['Familia']['identificador']."',
		        				'".$data['Familia']['nombre']."'
		        			)
		        		");

		        		$familia_id = $familiaModel->query("
			        		SELECT id
							FROM kappi.familias
							WHERE identificador = '".$data['Familia']['identificador']."'
								AND created =
									(	SELECT MAX(created)
										FROM kappi.familias
										WHERE identificador = '".$data['Familia']['identificador']."'
									)
			        	");
			        	$familia_id = $familia_id[0][0]['id'];
		        	}


		        	//Por cada miembro que haya
			        if (!empty($data['Miembros']))
			        {
			        	//Se le pone la nueva o la recien creada
			        	foreach ($data['Miembros'] as $key => $id)
			        	{
			        		$valido = $colegioModel->validarIdSelect($id);
				        	if ($valido == 1)
				        	{
				        		$id = $colegioModel->desencriptacion($id);
				        		
				        		//Se juntan los datos del usuario
				        		$datos_usuario['User']['id'] = $id;
				        		$datos_usuario['User']['familia_id'] = $familia_id;

				        		//Se edita el usuario
				        		$usuarioModel->query("
				        			UPDATE kappi.users
				        			SET familia_id = $familia_id
				        			WHERE id = $id
				        		");
				        	}
			        	}
			        }

			        return 1;
				}	
	        }
	    }
	}

?>