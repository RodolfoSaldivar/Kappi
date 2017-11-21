<?php 

	App::uses('Component', 'Controller');

	class CiclosComponent extends Component
	{
	    // the other component your component uses
	    public $components = array('Qimage', 'Session');

	    public function agregarBDD($data)
	    {	
	    	//Carga modelos necesarios
	    	$colegioModel = ClassRegistry::init('Colegio');
	    	$cicloModel = ClassRegistry::init('Ciclo');

	        //Obtiene el metodo directamente del modelo de Ciclo
			$agregar_valido = $cicloModel->cicloAgregarValidar($data);
	        
	        if ($agregar_valido != 1)
	        {
	        	return $agregar_valido;
	        }
	        else
	        {
				//Se obtiene el id del ciclo
	        	if (!empty($data['Ciclo']['id']))
	        	{
	        		$id = $colegioModel->desencriptacion($data['Ciclo']['id']);
	        		$data['Ciclo']['id'] = $id;
	        	}

	        	//Se guarda el ciclo o se edita
	        	if (empty($data['Ciclo']['id']))
	        	{
		        	if (!empty($data['Ciclo']['ciclo_id']))
		        	{
		        		$cicloModel->query("
		        			INSERT INTO kappi.ciclos (nombre, fecha_inicio, fecha_fin, colegio_id, ciclo_id)
		        			VALUES
		        			(
		        				'".$data['Ciclo']['nombre']."',
		        				'".$data['Ciclo']['fecha_inicio']."',
		        				'".$data['Ciclo']['fecha_fin']."',
		        				".$data['Ciclo']['colegio_id'].",
		        				".$data['Ciclo']['ciclo_id']."
		        			)
			        	");
		        	}
		        	else
		        		$cicloModel->query("
		        			INSERT INTO kappi.ciclos (nombre, fecha_inicio, fecha_fin, colegio_id)
		        			VALUES
		        			(
		        				'".$data['Ciclo']['nombre']."',
		        				'".$data['Ciclo']['fecha_inicio']."',
		        				'".$data['Ciclo']['fecha_fin']."',
		        				".$data['Ciclo']['colegio_id']."
		        			)
			        	");
	        	}
	        	else
	        	{
	        		$cicloModel->query("
	        			UPDATE kappi.ciclos
	        			SET nombre = '".$data['Ciclo']['nombre']."',
	        				fecha_inicio = '".$data['Ciclo']['fecha_inicio']."',
	        				fecha_fin = '".$data['Ciclo']['fecha_fin']."'
	        			WHERE id = ".$data['Ciclo']['id']."
	        		");
	        	}

		        return 1;
        	}
	    }
	}

?>