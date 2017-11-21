<?php
App::uses('AppController', 'Controller');
/**
 * Extras Controller
 *
 * @property Extra $Extra
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class ExtrasController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'Extras');


//-------------------------------------------------------------------------


	public function isAuthorized($user)
	{
		//Acceso para todos
		if (in_array($this->action, array('distinciones', 'reportes')))
		{
            return true;
        }

        //Acceso para Admins solamente
        if (in_array($this->action, array('index', 'agregar', 'editar')))
        {
            if (isset($user['tipo']) && in_array($user['tipo'], array("Administrador")))
    		{
    			return true;
    		}
        }

        //Acceso para Maestros solamente
        if (in_array($this->action, array('mandar_extra', 'alumnos_especificos')))
        {
            if (isset($user['tipo']) && in_array($user['tipo'], array("Maestro")))
    		{
    			return true;
    		}
        }

        //Acceso para Admins y Maestros
        if (in_array($this->action, array('filtrar_enviados', 'datos')))
        {
            if (isset($user['tipo']) && in_array($user['tipo'], array("Administrador", "Maestro")))
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

		$extras = $this->Extra->find('all', array(
	    	'conditions' => array(
	    		'Extra.colegio_id' => $colegio_id
	    	),
	    	'order' => array(
	    		'descripcion' => 'asc'
	    	),
	    	'fields' => array(
	    		'Extra.id',
	    		'Extra.tipo',
	    		'Extra.descripcion',
	    		'Imagene.nombre',
	    		'Imagene.ruta'
	    	),
	    	'recursive' => 0
	    ));

	    foreach ($extras as $key => $extra)
	    {
	    	$id_encriptada = $this->Colegio->encriptacion($extra['Extra']['id']);
	    	$extras[$key]['Extra']['encriptada'] = $id_encriptada;
	    }

	    $this->set("extras", $extras);
	}
	

//-------------------------------------------------------------------------
	

	public function agregar($extra_tipo = null)
	{
		$this->loadModel("Colegio");

		$colegio_id = $this->Session->read("mi_colegio");

		if ($extra_tipo == "distinciones")
	    	$this->set("extra_tipo", "Distinción");
		if ($extra_tipo == "reportes")
	    	$this->set("extra_tipo", "Reporte");

		if (!empty($this->request->data))
		{
			$this->request->data['Extra']['colegio_id'] = $colegio_id;
			$this->request->data['Extra']['tipo'] = $extra_tipo;
			$imagenObligatoria = true;

			$agregado = $this->Extras->agregarBDD($this->request->data, $imagenObligatoria);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla de extras
		        $this->redirect("/extras");
	        }
		}
	}
	

//-------------------------------------------------------------------------


	public function editar($extra_tipo = null, $id = null)
	{
		$this->loadModel("Colegio");

		$colegio_id = $this->Session->read("mi_colegio");
		$id = $this->Colegio->desencriptacion($id);

		if ($extra_tipo == "distinciones")
	    	$this->set("extra_tipo", "Distinción");
		if ($extra_tipo == "reportes")
	    	$this->set("extra_tipo", "Reporte");
		
		if (!$this->Extra->exists($id, "extras"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese extra no existe.");
			$this->redirect("/no_autorizado");
		}

		if (!empty($this->request->data))
		{
        	$this->request->data['Img']['id'] = $this->Session->read('Img.id');
        	$this->request->data['Img']['ruta'] = $this->Session->read('Img.ruta').$this->Session->read('Img.nombre');
        	$this->Session->delete('Img');

			$this->request->data['Extra']['colegio_id'] = $colegio_id;
			$this->request->data['Extra']['tipo'] = $extra_tipo;
			$imagenObligatoria = false;
			$agregado = $this->Extras->agregarBDD($this->request->data, $imagenObligatoria);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla de extras
	        	$this->redirect("/extras");
	        }
		}


		//Obtiene el extra
		$extra = $this->Extra->find('first', array(
			'conditions' => array('Extra.id' => $id),
			'recursive' => 0,
	    	'fields' => array(
	    		'Extra.id',
	    		'Extra.tipo',
	    		'Extra.descripcion',
	    		'Imagene.id',
	    		'Imagene.nombre',
	    		'Imagene.ruta'
	    	)
		));
		$extra['Extra']['id'] = $this->Colegio->encriptacion($extra['Extra']['id']);
		
		$this->set('extra', $extra);

		$this->Session->write('Img.id', $extra['Imagene']['id']);
		$this->Session->write('Img.ruta', $extra['Imagene']['ruta']);
		$this->Session->write('Img.nombre', $extra['Imagene']['nombre']);
	}


//-------------------------------------------------------------------------


	public function alumnos_especificos()
	{
		$this->layout = 'ajax';

		$this->loadModel('Colegio');
		$this->loadModel("AlumnosInscrito");

		$salone_id = $this->Colegio->desencriptacion($this->request->data["salon"]);

		$alumnos = $this->AlumnosInscrito->find('all', array(
	    	'recursive' => 0,
	    	'conditions' => array(
	    		'AlumnosInscrito.salone_id' => $salone_id,
	    		'User.activo' => 1
	    	),
	    	'fields' => array(
	    		'User.id', 'User.nombre', 'User.a_paterno', 'User.a_materno'
	    	),
	    	'order' => array(
				'User.a_paterno' => 'asc',
				'User.a_materno' => 'asc',
				'User.nombre' => 'asc'
			)
	    ));

		foreach ($alumnos as $key => $alumno)
		{
			$id_encriptada = $this->Colegio->encriptacion($alumno["User"]["id"]);
			$alumnos[$key]["User"]["encriptada"] = $id_encriptada;
		}
		
	    $this->set("alumnos", $alumnos);
	}
	

//-------------------------------------------------------------------------
	

	public function filtrar_enviados()
	{
		$this->layout='ajax';

		$data = $this->request->data;
		$data['enviados'] = unserialize(base64_decode($data['enviados']));
		$user_tipo = $this->Session->read('Auth.User.tipo');

		$todoVacio = false;
		if (empty($data['fecha']) &&
			empty($data['emisor']))
		{	
			$todoVacio = true;
		}


		if (!empty($data['fecha']))
		{
			foreach ($data['enviados'] as $key => $enviado)
			{
				if (stripos($enviado['DestinoExtra']['fecha'], $data['fecha']) === false)
				{
					unset($data['enviados'][$key]);
				}
			}	
		}

		if (!empty($data['emisor']))
		{
			foreach ($data['enviados'] as $key => $enviado)
			{
				$nombre_completo = $enviado['Profe']['a_paterno']." ".$enviado['Profe']['a_materno'].", ".$enviado['Profe']['nombre'];

				if (stripos($nombre_completo, $data['emisor']) === false)
				{
					unset($data['enviados'][$key]);
				}
			}	
		}

		$this->set("tipo", $data['tipo']);
		$this->set("user_tipo", $user_tipo);
		if ($todoVacio)
			$this->set("enviados", $this->Session->read("EnviadosCompletos"));
		else
			$this->set("enviados", $data['enviados']);
	}
	

//-------------------------------------------------------------------------
	

	public function distinciones()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");
		$user_id = $this->Session->read('Auth.User.id');
		$user_tipo = $this->Session->read('Auth.User.tipo');
		$mi_hijo = $this->Session->read('mi_hijo');

		//Obtiene el metodo directamente del modelo de Extra
		if (in_array($user_tipo, array("Superadministrador", "Administrador", "Maestro")))
		{
			$enviados = $this->Extra->traerExtrasEnviados($user_id, $user_tipo, $colegio_id, "distinciones");
		}
		else
			$recibidos = $this->Extra->traerExtrasRecibidos($user_id, $user_tipo, "distinciones", $mi_hijo);

		@$this->Session->write("EnviadosCompletos", $enviados);
		@$this->Session->write("RecibidosCompletos", $recibidos);
		$this->Session->write("tipo", "Distinción");
		$this->Session->write("tipo_bread", "Distinciones");
		$this->Session->write("una_pag_antes", "/distinciones");

	    @$this->set("enviados", $enviados);
	    @$this->set("guardados", $guardados);
	    @$this->set("recibidos", $recibidos);
	    $this->set("tipo", "distinciones");
	    $this->set("user_tipo", $user_tipo);
	}
	

//-------------------------------------------------------------------------
	

	public function reportes()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");
		$user_id = $this->Session->read('Auth.User.id');
		$user_tipo = $this->Session->read('Auth.User.tipo');
		$mi_hijo = $this->Session->read('mi_hijo');

		//Obtiene el metodo directamente del modelo de Extra
		if (in_array($user_tipo, array("Superadministrador", "Administrador", "Maestro")))
		{
			$enviados = $this->Extra->traerExtrasEnviados($user_id, $user_tipo, $colegio_id, "reportes");
		}
		else
			$recibidos = $this->Extra->traerExtrasRecibidos($user_id, $user_tipo, "reportes", $mi_hijo);

		@$this->Session->write("EnviadosCompletos", $enviados);
		@$this->Session->write("RecibidosCompletos", $recibidos);
		$this->Session->write("tipo", "Reporte");
		$this->Session->write("tipo_bread", "Reportes");
		$this->Session->write("una_pag_antes", "/reportes");

	    @$this->set("enviados", $enviados);
	    @$this->set("guardados", $guardados);
	    @$this->set("recibidos", $recibidos);
	    $this->set("tipo", "reportes");
	    $this->set("user_tipo", $user_tipo);
	}
	

//-------------------------------------------------------------------------
	

	public function datos()
	{
		$this->loadModel("Colegio");
		$this->loadModel("DestinoExtra");

		if (!empty($this->request->data)) 
		{
			$emisor_id = $this->Colegio->desencriptacion($this->request->data['emisor']);
			$fecha = $this->request->data['fecha'];
			$tipo = substr($this->Session->read("una_pag_antes"), 1);

			$alumnos = $this->DestinoExtra->query("
				SELECT MIN(usu.id) as id, MIN(usu.nombre) as nombre, MIN(usu.a_paterno) as a_paterno, MIN(usu.a_materno) as a_materno
				FROM kappi.users as usu, kappi.destino_extras as des, kappi.extras as ex
				WHERE des.emisor = $emisor_id
					AND des.fecha = N'$fecha'
					AND des.extra_id = ex.id
					AND ex.tipo = N'$tipo'
					AND des.user_id = usu.id
					AND usu.tipo = N'Alumno'
				GROUP BY usu.id
				ORDER BY a_paterno ASC,
					a_materno ASC,
					nombre ASC
			");


			foreach ($alumnos as $keyAl => $alumno)
			{
				$alumno_id = $alumno[0]['id'];
				$extras = $this->DestinoExtra->query("
					SELECT ex.id
					FROM kappi.destino_extras as des, kappi.extras as ex
					WHERE des.emisor = $emisor_id
						AND des.fecha = N'$fecha'
						AND des.user_id = $alumno_id
						AND des.extra_id = ex.id
						AND ex.tipo = N'$tipo'
				");

				foreach ($extras as $keyEx => $extra)
				{
					$extra_id = $extra[0]['id'];
					$imagen = $this->Extra->query("
						SELECT TOP 1 ex.id, ex.descripcion, img.ruta, img.nombre
						FROM kappi.extras as ex, kappi.imagenes as img
						WHERE ex.id = $extra_id
							AND ex.imagene_id = img.id
					");

					$extras[$keyEx] = $imagen;
				}

				$alumnos[$keyAl]['Extras'] = $extras;
			}

		    $this->set("alumnos", $alumnos);
		    $this->set("fecha", $this->request->data["fecha"]);
		}
	}
	

//-------------------------------------------------------------------------
	

	public function mandar_extra()
	{
		$this->loadModel("Colegio");
		$this->loadModel("Imagene");
		$this->loadModel("Salone");
		$this->loadModel("Ciclo");
		$this->loadModel("User");
		$this->loadModel("AlumnosInscrito");

		$colegio_id = $this->Session->read("mi_colegio");

		//Consigue el ciclo actual
		$ciclo_id = $this->Ciclo->find('first', array(
	    	'conditions' => array(
	    		'Ciclo.colegio_id' => $colegio_id,
	    		'Ciclo.activo' => 1
	    	),
	    	'order' => array('Ciclo.created' => 'desc'),
	    	'fields' => array('Ciclo.id')
	    ));

		$ciclo_id = $ciclo_id["Ciclo"]["id"];
		$profe_id = $this->Session->read('Auth.User.id');

		//Consigue los salones del profesor de la sesion
		$salones = $this->Salone->find('all', array(
			'recursive' => 0,
			'conditions' => array(
				'Salone.ciclo_id' => $ciclo_id,
				'Salone.user_id' => $profe_id
			),
			'fields' => array('Salone.id', 'Salone.nombre')
		));

		foreach ($salones as $key => $salon)
		{
			$id_encriptada = $this->Colegio->encriptacion($salon["Salone"]["id"]);
			$salones[$key]["Salone"]["encriptada"] = $id_encriptada;
		}

		//Consigue el tipo de extras que tiene el colegio
		$extra_tipo = substr($this->Session->read("una_pag_antes"), 1);

		$extras = $this->Extra->find('all', array(
			'conditions' => array(
				'Extra.colegio_id' => $colegio_id,
				'Extra.tipo' => $extra_tipo
			),
	    	'order' => array(
	    		'descripcion' => 'asc'
	    	),
	    	'fields' => array('id', 'descripcion', 'imagene_id')
		));

		foreach ($extras as $key => $extra)
		{
			$imagen = $this->Imagene->find('first', array(
				'conditions' => array('id' => $extra['Extra']['imagene_id']),
				'fields' => array('ruta', 'nombre')
			));

			$extras[$key]["Imagene"] = $imagen["Imagene"];

			$id_encriptada = $this->Colegio->encriptacion($extra["Extra"]["id"]);
			$extras[$key]["Extra"]["encriptada"] = $id_encriptada;
		}

		if (!empty($this->request->data))
		{
			$data = $this->request->data;
			$data["DestinoExtra"]["emisor"] = $profe_id;
			$alumnos_escogidos;
			$destinatarios;

			if ($data["modo"] == 2)
				$alumnos_escogidos = $this->request->data["Alumnos"];

			if ($data["modo"] == 1)
			{
				@$valido = $this->Colegio->validarIdSelect($data["salon"]);
				if ($valido == 1)
				{
					@$salone_id = $this->Colegio->desencriptacion($data["salon"]);
					$inscritos = $this->AlumnosInscrito->find('list', array(
						'conditions' => array('AlumnosInscrito.salone_id' => $salone_id),
						'fields' => array('AlumnosInscrito.user_id', 'AlumnosInscrito.user_id')
					));
					
					foreach ($inscritos as $key => $value)
					{
						$id_encriptada = $this->Colegio->encriptacion($key);
						$alumnos_escogidos[$id_encriptada] = "on";
						//Solo por poner un valor
					}
				}
			} 

			foreach (@$alumnos_escogidos as $key => $value)
			{
				$user_id = $this->Colegio->desencriptacion($key);
				$familia_id = $this->User->find('first', array(
					'recursive' => 0,
					'conditions' => array('User.id' => $user_id),
					'fields' => array('User.familia_id')
				));
				$familia_id = $familia_id["User"]["familia_id"];

				$destinatarios[$key]["Alumno"] = $key;
				
				if (!empty($familia_id))
				{
					$padre = $this->User->find('first', array(
						'recursive' => 0,
						'conditions' => array(
							'User.familia_id' => $familia_id,
							'User.tipo' => 'Padre',
							'User.activo' => 1
						),
						'fields' => array('User.id')
					));

					$madre = $this->User->find('first', array(
						'recursive' => 0,
						'conditions' => array(
							'User.familia_id' => $familia_id,
							'User.tipo' => 'Madre',
							'User.activo' => 1
						),
						'fields' => array('User.id')
					));

					@$padre_id = $this->Colegio->encriptacion($padre["User"]["id"]);
					@$madre_id = $this->Colegio->encriptacion($madre["User"]["id"]);

					if (!empty($padre_id))
						$destinatarios[$key]["Padre"] = $padre_id;
					if (!empty($madre_id))
						$destinatarios[$key]["Madre"] = $madre_id;
				}
			}


			@$mandado = $this->Extras->agregarDestinos($destinatarios, $data);

			if ($mandado != 1)
	        {
	        	$this->Session->setFlash($mandado);
	        }
	        else
	        {
	        	//Regresa a la pantalla de los mensajes
	        	$this->redirect($this->Session->read("una_pag_antes"));
	        }
		}
		
	    $this->set("salones", $salones);
	    $this->set("extras", $extras);
	    //var_dump($extras);
	}

}
