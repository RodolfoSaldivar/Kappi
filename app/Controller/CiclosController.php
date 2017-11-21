<?php
App::uses('AppController', 'Controller');
/**
 * Ciclos Controller
 *
 * @property Ciclo $Ciclo
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class CiclosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'Ciclos');


//-------------------------------------------------------------------------


	public function isAuthorized($user)
	{
        //Acceso para Admins
        if (in_array($this->action, array('index', 'activo_actualizar', 'agregar', 'editar', 'filtrar_ciclos')))
        {
            if (isset($user['tipo']) && in_array($user['tipo'], array("Administrador")))
    		{
    			return true;
    		}
        }

	    return parent::isAuthorized($user);
    }


//-------------------------------------------------------------------------


	public function index()
	{
		$this->loadModel("Colegio");

		$colegio_id = $this->Session->read("mi_colegio");

		$ciclos = $this->Ciclo->find('all', array(
	    	'conditions' => array('Ciclo.colegio_id' => $colegio_id),
	    	'order' => array(
	    		'created' => 'desc'
	    	),
	    	'fields' => array('id', 'nombre', 'fecha_inicio', 'fecha_fin', 'activo')
	    ));

	    foreach ($ciclos as $key => $ciclo)
	    {
	    	$id_encriptada = $this->Colegio->encriptacion($ciclo['Ciclo']['id']);
	    	$ciclos[$key]['Ciclo']['encriptada'] = $id_encriptada;
	    }

		$this->Session->write("CiclosCompletos", $ciclos);

	    $this->set("ciclos", $ciclos);
	}
	

//-------------------------------------------------------------------------
	

	public function activo_actualizar()
	{
		$this->layout='ajax';

		$ciclos_activos = $this->Ciclo->find('count', array(
			'conditions' => array(
				'Ciclo.colegio_id' => $this->Session->read("mi_colegio"),
				'Ciclo.activo' => 1
			)
		));

		$this->loadModel("Colegio");

		$id = $this->Colegio->desencriptacion($this->request->data['id']);
		$this->request->data['Ciclo']['id'] = $id;

		$this->request->data['Ciclo']['encriptada'] = $this->request->data['id'];

		if ($ciclos_activos == 1)
		{
			if ($this->request->data['activo'] == 0)
				$this->request->data['mensaje'] = "Solo 1 ciclo activo a la vez.";

			$this->request->data['Ciclo']['activo'] = 0;
		}
		else
		{
			$this->request->data['Ciclo']['activo'] = 1;
		}

		$this->Ciclo->query("
			UPDATE kappi.ciclos
			SET activo = ".$this->request->data["Ciclo"]["activo"]."
			WHERE id = ".$this->request->data["Ciclo"]["id"]."
		");

		$this->set("ciclo", $this->request->data);
	}
	

//-------------------------------------------------------------------------
	

	public function filtrar_ciclos()
	{
		$this->layout='ajax';

		$data = $this->request->data;
		$data['ciclos'] = unserialize(base64_decode($data['ciclos']));
		
		$todoVacio = false;
		if (empty($data['nombre']) &&
			empty($data['fecha_inicio']) &&
			empty($data['fecha_fin']))
		{
			$todoVacio = true;
		}


		if (!empty($data['nombre']))
		{
			foreach ($data['ciclos'] as $key => $ciclo)
			{
				if (stripos($ciclo['Ciclo']['nombre'], $data['nombre']) === false)
				{
					unset($data['ciclos'][$key]);
				}
			}	
		}

		if (!empty($data['fecha_inicio']))
		{
			foreach ($data['ciclos'] as $key => $ciclo)
			{
				if (stripos($ciclo['Ciclo']['fecha_inicio'], $data['fecha_inicio']) === false)
				{
					unset($data['ciclos'][$key]);
				}
			}	
		}

		if (!empty($data['fecha_fin']))
		{
			foreach ($data['ciclos'] as $key => $ciclo)
			{
				if (stripos($ciclo['Ciclo']['fecha_fin'], $data['fecha_fin']) === false)
				{
					unset($data['ciclos'][$key]);
				}
			}	
		}

		if ($todoVacio)
			$this->set("ciclos", $this->Session->read("CiclosCompletos"));
		else
			$this->set("ciclos", $data['ciclos']);
		
	}
	

//-------------------------------------------------------------------------
	

	public function agregar()
	{
		if (!empty($this->request->data))
		{
			$colegio_id = $this->Session->read("mi_colegio");
			$this->request->data["Ciclo"]["colegio_id"] = $colegio_id;

			//Desactivar el ciclo activo actual si hay
			$ciclo_actual = $this->Ciclo->find('first', array(
				'conditions' => array(
					'Ciclo.colegio_id' => $colegio_id,
					'Ciclo.activo' => 1
				),
				'fields' => array('id')
			));
			$ciclo_actual['Ciclo']['activo'] = 0;

	        if (!empty($ciclo_actual['Ciclo']['id']))
	        {
	        	$this->Ciclo->query("
	        		UPDATE kappi.ciclos
	        		SET activo = 0
	        		WHERE id = ".$ciclo_actual['Ciclo']['id']."
	        	");
	        }
	        	

			//Ultimo ciclo hecho por el colegio
			$ciclo_pasado = $this->Ciclo->query(
				"SELECT TOP 1 id
				FROM kappi.ciclos
				WHERE colegio_id = $colegio_id
				ORDER BY id DESC"
			);
			$this->request->data["Ciclo"]["ciclo_id"] = $ciclo_pasado[0][0]["id"];

			$agregado = $this->Ciclos->agregarBDD($this->request->data);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla de ciclos
	        	$this->redirect("/ciclos");
	        }
		}
	}
	

//-------------------------------------------------------------------------


	public function editar($id = null)
	{
		$this->loadModel("Colegio");

		$id = $this->Colegio->desencriptacion($id);
		
		if (!$this->Ciclo->exists($id, "ciclos"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese ciclo no está registrado.");
			$this->redirect("/no_autorizado");
		}


		if (!empty($this->request->data))
		{
			$agregado = $this->Ciclos->agregarBDD($this->request->data);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla de ciclos
	        	$this->redirect("/ciclos");
	        }
		}


		//Obtiene el ciclo
		$ciclo = $this->Ciclo->find('first', array(
			'conditions' => array('Ciclo.id' => $id),
			'fields' => array('id', 'nombre', 'fecha_inicio', 'fecha_fin')
		));
		$ciclo['Ciclo']['id'] = $this->Colegio->encriptacion($ciclo['Ciclo']['id']);
		
		$this->set('ciclo', $ciclo);
	}

}
