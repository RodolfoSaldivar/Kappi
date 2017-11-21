<?php
App::uses('AppController', 'Controller');
/**
 * Colegios Controller
 *
 * @property Colegio $Colegio
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class ColegiosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'Colegios');


//-------------------------------------------------------------------------


	public function isAuthorized($user)
	{
        //Acceso para Admins
        if (in_array($this->action, array('index', 'datos', 'editar', 'agregar_nivel', 'agregar_grado')))
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
		$colegios = $this->Colegio->find('all', array(
	    	'order' => array(
	    		'activo' => 'desc',
	    		'nombre_comercial' => 'asc'
	    	),
	    	'fields' => array(
	    		'Colegio.nombre_comercial', 'Colegio.nombre_corto', 'Colegio.telefono', 'Colegio.activo', 'Colegio.id', 'Colegio.imagene_id', 'Imagene.nombre', 'Imagene.ruta'
	    	),
	    	'recursive' => 0
	    ));

	    foreach ($colegios as $key => $colegio)
	    {
	    	$id_encriptada = $this->Colegio->encriptacion($colegio['Colegio']['id']);
	    	$colegios[$key]['Colegio']['encriptada'] = $id_encriptada;
	    }

		$this->Session->write("ColegiosCompletos", $colegios);

	    $this->set("colegios", $colegios);
	}
	

//-------------------------------------------------------------------------
	

	public function barra_busqueda_filtro()
	{
		$this->layout='ajax';

		$data = $this->request->data;
		$data['colegios'] = unserialize(base64_decode($data['colegios']));
		
		$todoVacio = false;
		if (empty($data['nombre_comercial']) &&
			empty($data['nombre_corto']) &&
			empty($data['telefono']))
		{
			$todoVacio = true;
		}


		if (!empty($data['nombre_comercial']))
		{
			foreach ($data['colegios'] as $key => $colegio)
			{
				if (stripos($colegio['Colegio']['nombre_comercial'], $data['nombre_comercial']) === false)
				{
					unset($data['colegios'][$key]);
				}
			}	
		}

		if (!empty($data['nombre_corto']))
		{
			foreach ($data['colegios'] as $key => $colegio)
			{
				if (stripos($colegio['Colegio']['nombre_corto'], $data['nombre_corto']) === false)
				{
					unset($data['colegios'][$key]);
				}
			}	
		}

		if (!empty($data['telefono']))
		{
			foreach ($data['colegios'] as $key => $colegio)
			{
				if (stripos($colegio['Colegio']['telefono'], $data['telefono']) === false)
				{
					unset($data['colegios'][$key]);
				}
			}	
		}

		if ($todoVacio)
			$this->set("colegios", $this->Session->read("ColegiosCompletos"));
		else
			$this->set("colegios", $data['colegios']);
		
	}
	

//-------------------------------------------------------------------------
	

	public function activo_actualizar()
	{
		$this->layout='ajax';

		$id = $this->Colegio->desencriptacion($this->request->data['id']);
		$this->request->data['Colegio']['id'] = $id;

		$this->request->data['Colegio']['encriptada'] = $this->request->data['id'];

		if ($this->request->data['activo'] == 1)
			$this->request->data['Colegio']['activo'] = 0;
		else
			$this->request->data['Colegio']['activo'] = 1;

		$this->Colegio->query("
			UPDATE kappi.colegios
			SET activo = ".$this->request->data["Colegio"]["activo"]."
			WHERE id = ".$this->request->data["Colegio"]["id"]."
		");

		$this->set("colegio", $this->request->data);
	}


//-------------------------------------------------------------------------


	public function datos($id = null)
	{
		$this->loadModel("Nivele");
		$this->loadModel("Grado");

		$id = $this->Colegio->desencriptacion($id);

		if (!$this->Colegio->exists($id, "colegios"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese colegio no está registrado.");
			$this->redirect("/no_autorizado");
		}

		//Obtiene el colegio
		$colegio = $this->Colegio->find('first', array(
			'conditions' => array('Colegio.id' => $id),
			'recursive' => 0,
			'fields' => array(
	    		'Colegio.nombre_comercial', 'Colegio.nombre_corto', 'Colegio.telefono', 'Colegio.activo', 'Colegio.razon_social', 'Colegio.imagene_id', 'Imagene.nombre', 'Imagene.ruta'
	    	)
		));

		//Obtiene los niveles
		$niveles = $this->Nivele->find('all', array(
			'conditions' => array('Nivele.colegio_id' => $id),
			'fields' => array('Nivele.id', 'Nivele.nombre'),
			'recursive' => 0
		));

		//Obtienen los grados
		foreach ($niveles as $key => $nivel)
		{
			$colegio['Nivele'][$key]['nombre'] = $nivel['Nivele']['nombre'];

			$grados = $this->Grado->find('all', array(
				'conditions' => array('Grado.nivele_id' => $nivel['Nivele']['id']),
				'fields' => array('Grado.identificador', 'Grado.nombre'),
				'recursive' => 0
			));

			$colegio['Nivele'][$key]['grados'] = $grados;
		}

		$this->set('colegio', $colegio);
	}
	

//-------------------------------------------------------------------------


	public function agregar()
	{
		if (!empty($this->request->data))
		{
			$imagenObligatoria = true;
			$agregado = $this->Colegios->agregarBDD($this->request->data, $imagenObligatoria);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla de colegios
	        	$this->redirect("/colegios");
	        }
		}
	}
	

//-------------------------------------------------------------------------


	public function editar($id = null)
	{
		$this->loadModel("Nivele");
		$this->loadModel("Grado");

		$id = $this->Colegio->desencriptacion($id);
		
		if (!$this->Colegio->exists($id, "colegios"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese colegio no está registrado.");
			$this->redirect("/no_autorizado");
		}


		if (!empty($this->request->data))
		{
        	$this->request->data['Img']['id'] = $this->Session->read('Img.id');
        	$this->request->data['Img']['ruta'] = $this->Session->read('Img.ruta').$this->Session->read('Img.nombre');
        	$this->Session->delete('Img');

			$imagenObligatoria = false;
			$agregado = $this->Colegios->agregarBDD($this->request->data, $imagenObligatoria);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla de colegios
	        	$this->redirect("/colegios");
	        }
		}


		//Obtiene el colegio
		$colegio = $this->Colegio->find('first', array(
			'conditions' => array('Colegio.id' => $id),
			'recursive' => 0,
			'fields' => array(
				'Colegio.id', 'Colegio.telefono', 'Colegio.razon_social', 'Colegio.nombre_comercial', 'Colegio.nombre_corto', 'Colegio.imagene_id', 'Imagene.id', 'Imagene.nombre', 'Imagene.ruta'
			)
		));

		//Obtiene los niveles
		$niveles = $this->Nivele->find('all', array(
			'conditions' => array('Nivele.colegio_id' => $id),
			'fields' => array('Nivele.id', 'Nivele.nombre'),
			'recursive' => 0
		));

		//Obtienen los grados
		foreach ($niveles as $key => $nivel)
		{
			$colegio['Nivele'][$key]['id'] = $nivel['Nivele']['id'];
			$colegio['Nivele'][$key]['nombre'] = $nivel['Nivele']['nombre'];

			$grados = $this->Grado->find('all', array(
				'conditions' => array('Grado.nivele_id' => $nivel['Nivele']['id']),
				'fields' => array('Grado.identificador', 'Grado.nombre'),
				'recursive' => 0
			));

			$colegio['Nivele'][$key]['grados'] = $grados;
		}
		$colegio['Colegio']['id'] = $this->Colegio->encriptacion($colegio['Colegio']['id']);
		
		$this->set('colegio', $colegio);

		$this->Session->write('Img.id', $colegio['Imagene']['id']);
		$this->Session->write('Img.ruta', $colegio['Imagene']['ruta']);
		$this->Session->write('Img.nombre', $colegio['Imagene']['nombre']);
	}


//-------------------------------------------------------------------------


	public function agregar_nivel()
	{
		$this->layout='ajax';

		$data = $this->request->data;

		$this->set('nivel', $data['nivel']);
	}


//-------------------------------------------------------------------------


	public function agregar_grado()
	{
		$this->layout='ajax';

		$data = $this->request->data;

		$this->set('nivel', $data['nivel']);
		$this->set('grado', $data['grado']);
	}
	
}
