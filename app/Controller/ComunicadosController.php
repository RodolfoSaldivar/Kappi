<?php
App::uses('AppController', 'Controller');
/**
 * Comunicados Controller
 *
 * @property Comunicado $Comunicado
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class ComunicadosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'Comunicados');


//-------------------------------------------------------------------------


	public function isAuthorized($user)
	{
		//Acceso para todos
		if (in_array($this->action, array('tareas', 'comunicados', 'descargar_imagenes', 'descargar_pdf', 'mensaje_abierto', 'alumnos_especificos', 'mensaje_abierto', 'mensaje_enviado', 'firmar_mensaje', 'datos', 'filtrar_enviados', 'filtrar_recibidos', 'filtrar_destinatarios', 'filtrar_guardados')))
		{
	        return true;
	    }

	    //Acceso para Admins y Maestros
	    if (in_array($this->action, array('agregar_imagen', 'agregar_pdf', 'descartar_guardado', 'guardar_mensaje', 'escribir')))
    	{
    		if (isset($user['tipo']) && in_array($user['tipo'], array("Administrador", "Maestro")))
    		{
    			return true;
    		}
    	}

	    //Acceso para Admins solamente
	    if (in_array($this->action, array('circulares', 'mostrar_grados', 'mostrar_salones')))
    	{
    		if (isset($user['tipo']) && !in_array($user['tipo'], array("Maestro")))
    		{
    			return true;
    		}
    	}
	    
	    return parent::isAuthorized($user);
	}


//-------------------------------------------------------------------------


	public function tareas()
	{
		//Por si cambio contraseña, se le indica que se cambio con exito
		$contra_cambiada = substr($this->request->referer(), -18);
		if ($contra_cambiada == "cambiar_contrasena")
			$this->Session->setFlash('Contraseña cambiada exitosamente.');

		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");
		$user_id = $this->Session->read('Auth.User.id');
		$user_tipo = $this->Session->read('Auth.User.tipo');
		$mi_hijo = $this->Session->read('mi_hijo');

		//Obtiene el metodo directamente del modelo de Comunicado
		if (in_array($user_tipo, array("Superadministrador", "Administrador", "Maestro")))
		{
			$enviados = $this->Comunicado->traerMensajesEnviados($user_id, $user_tipo, $colegio_id, "Tarea");
			$guardados = $this->Comunicado->traerMensajesGuardados($user_id, $user_tipo, $colegio_id, "Tarea");
		}
		else
			$recibidos = $this->Comunicado->traerMensajesRecibidos($user_id, $user_tipo, "Tarea", $mi_hijo);

		@$this->Session->write("EnviadosCompletos", $enviados);
		@$this->Session->write("GuardadosCompletos", $guardados);
		@$this->Session->write("RecibidosCompletos", $recibidos);
		$this->Session->write("tipo", "Tarea");
		$this->Session->write("tipo_bread", "Tareas");
		$this->Session->write("una_pag_antes", "/tareas");

	    @$this->set("enviados", $enviados);
	    @$this->set("guardados", $guardados);
	    @$this->set("recibidos", $recibidos);
	    $this->set("tipo", "tareas");
	    $this->set("user_tipo", $user_tipo);
	}


//-------------------------------------------------------------------------


	public function comunicados()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");
		$user_id = $this->Session->read('Auth.User.id');
		$user_tipo = $this->Session->read('Auth.User.tipo');
		$mi_hijo = $this->Session->read('mi_hijo');

		//Obtiene el metodo directamente del modelo de Comunicado
		if (in_array($user_tipo, array("Superadministrador", "Administrador", "Maestro")))
		{
			$enviados = $this->Comunicado->traerMensajesEnviados($user_id, $user_tipo, $colegio_id, "Comunicado");
			$guardados = $this->Comunicado->traerMensajesGuardados($user_id, $user_tipo, $colegio_id, "Comunicado");
		}
		else
			$recibidos = $this->Comunicado->traerMensajesRecibidos($user_id, $user_tipo, "Comunicado", $mi_hijo);

		@$this->Session->write("EnviadosCompletos", $enviados);
		@$this->Session->write("GuardadosCompletos", $guardados);
		@$this->Session->write("RecibidosCompletos", $recibidos);
		$this->Session->write("tipo", "Comunicado");
		$this->Session->write("tipo_bread", "Comunicados");
		$this->Session->write("una_pag_antes", "/comunicados");

	    @$this->set("enviados", $enviados);
	    @$this->set("guardados", $guardados);
	    @$this->set("recibidos", $recibidos);
	    $this->set("tipo", "comunicados");
	    //$this->set("escribir", "escribir_comunicado");
	    $this->set("user_tipo", $user_tipo);
	}


//-------------------------------------------------------------------------


	public function circulares()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");
		$user_id = $this->Session->read('Auth.User.id');
		$user_tipo = $this->Session->read('Auth.User.tipo');
		$mi_hijo = $this->Session->read('mi_hijo');

		//Obtiene el metodo directamente del modelo de Comunicado
		if (in_array($user_tipo, array("Superadministrador", "Administrador", "Maestro")))
		{
			$enviados = $this->Comunicado->traerMensajesEnviados($user_id, $user_tipo, $colegio_id, "Circular");
			$guardados = $this->Comunicado->traerMensajesGuardados($user_id, $user_tipo, $colegio_id, "Circular");
		}
		else
			$recibidos = $this->Comunicado->traerMensajesRecibidos($user_id, $user_tipo, "Circular", $mi_hijo);

		@$this->Session->write("EnviadosCompletos", $enviados);
		@$this->Session->write("GuardadosCompletos", $guardados);
		@$this->Session->write("RecibidosCompletos", $recibidos);
		$this->Session->write("tipo", "Circular");
		$this->Session->write("tipo_bread", "Circulares");
		$this->Session->write("una_pag_antes", "/circulares");

	    @$this->set("enviados", $enviados);
	    @$this->set("guardados", $guardados);
	    @$this->set("recibidos", $recibidos);
	    $this->set("tipo", "circulares");
	    $this->set("user_tipo", $user_tipo);
	}


//-------------------------------------------------------------------------


	public function agregar_imagen()
	{
		$this->layout = 'ajax';
	}

	public function agregar_pdf()
	{
		$this->layout = 'ajax';
	}


//-------------------------------------------------------------------------


	public function descartar_guardado($id = null)
	{
		$this->loadModel('Colegio');

		$id = $this->Colegio->desencriptacion($id);

		if (!$this->Comunicado->exists($id, "comunicados"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese mensaje no existe.");
			$this->redirect("/no_autorizado");
		}

		$this->Comunicado->query("
			UPDATE kappi.comunicados
			SET guardado = 0
			WHERE id = $id
		");

		$this->redirect($this->Session->read("una_pag_antes"));
	}


	public function guardar_mensaje($id = null)
	{
		$this->loadModel('Colegio');

		$id = $this->Colegio->desencriptacion($id);

		if (!$this->Comunicado->exists($id, "comunicados"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese mensaje no existe.");
			$this->redirect("/no_autorizado");
		}

		$this->Comunicado->query("
			UPDATE kappi.comunicados
			SET guardado = 1
			WHERE id = $id
		");

		$this->redirect($this->Session->read("una_pag_antes"));
	}


//-------------------------------------------------------------------------


    public function descargar_imagenes($id = null)
    {
		$this->loadModel('Colegio');
		$this->loadModel('Imagene');

		$id = $this->Colegio->desencriptacion($id);

        $files = $this->Imagene->find('first', array(
        	'conditions' => array('id' => $id),
        	'fields' => array('nombre', 'ruta')
        ));
        $filename = $files['Imagene']['nombre'];
        $name = explode('.',$filename);
        $this->viewClass = 'Media';

        $params = array(
            'id'        => $filename,
            'name'      => $name[0],
            'download'  => true,
            'extension' => $name[1],
            'path'      => webroot . $files['Imagene']['ruta'] . DS
        );

        $this->set($params);
    }


    public function descargar_pdf($id = null, $download = false)
    {
		$this->loadModel('Colegio');
		$this->loadModel('Archivo');

		$id = $this->Colegio->desencriptacion($id);

        $files = $this->Archivo->find('first', array(
        	'conditions' => array('id' => $id),
        	'fields' => array('nombre')
        ));
        $filename = $files['Archivo']['nombre'];
        $name = explode('.',$filename);
        $this->viewClass = 'Media';

        $params = array(
            'id'        => $filename,
            'name'      => $name[0],
            'download'  => $download,
            'extension' => $name[1],
            'path'      => WWW_ROOT . '/pdf/' . DS
        );

        $this->set($params);
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


	public function mostrar_grados()
	{
		$this->layout = 'ajax';

		$this->loadModel('Colegio');
		$this->loadModel("Grado");

		$nivele_id = $this->Colegio->desencriptacion($this->request->data["nivel"]);

		$grados = $this->Grado->find('all', array(
	    	'conditions' => array(
	    		'Grado.nivele_id' => $nivele_id
	    	),
	    	'fields' => array('id', 'nombre')
	    ));

		foreach ($grados as $key => $grado)
		{
			$id_encriptada = $this->Colegio->encriptacion($grado["Grado"]["id"]);
			$grados[$key]["Grado"]["encriptada"] = $id_encriptada;
		}
		
	    $this->set("grados", $grados);
	}


//-------------------------------------------------------------------------


	public function mostrar_salones()
	{
		$this->layout = 'ajax';

		$this->loadModel('Colegio');
		$this->loadModel("Salone");

		$grado_id = $this->Colegio->desencriptacion($this->request->data["grado"]);

		$salones = $this->Salone->find('all', array(
	    	'recursive' => 0,
	    	'conditions' => array(
	    		'Salone.grado_id' => $grado_id,
	    		'Ciclo.activo' => 1
	    	),
	    	'fields' => array('Salone.id', 'Salone.nombre')
	    ));

		foreach ($salones as $key => $salon)
		{
			$id_encriptada = $this->Colegio->encriptacion($salon["Salone"]["id"]);
			$salones[$key]["Salone"]["encriptada"] = $id_encriptada;
		}
		
	    $this->set("salones", $salones);
	}


//-------------------------------------------------------------------------


	public function mensaje_abierto($tipo = null, $id = null)
	{
		$this->loadModel('Colegio');
		$this->loadModel('Destinatario');

		$user_id = $this->Session->read('Auth.User.id');
		$comunicado_id = $this->Colegio->desencriptacion($id);

		$condiciones = array(
			'Destinatario.user_id' => $user_id,
			'Destinatario.comunicado_id' => $comunicado_id
		);

		if (!empty($this->Session->read("mi_hijo"))) 
			$condiciones['Destinatario.hijo'] = $this->Session->read("mi_hijo");

		$destinatario = $this->Destinatario->find('first', array(
			'conditions' => $condiciones,
			'fields' => array(
				'Destinatario.id',
				'Destinatario.visto',
				'Destinatario.fecha_visto'
			)
		));

		if (empty($destinatario["Destinatario"]["fecha_visto"]))
		{
			//Fecha de primer vista del mensaje
			date_default_timezone_set('America/Mexico_City');
        	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			 
			$fecha_visto = $dias[date('w')].", ".date('g:i a')." - ".$meses[date('n')-1]." ".date('d'). ", ".date('Y');

			$destinatario["Destinatario"]["fecha_visto"] = $fecha_visto;
		}

		$destinatario["Destinatario"]["visto"] = $destinatario["Destinatario"]["visto"] + 1;

    	$this->Destinatario->query("
    		UPDATE kappi.destinatarios
    		SET visto = ".$destinatario["Destinatario"]["visto"].",
    			fecha_visto = '".$destinatario["Destinatario"]["fecha_visto"]."'
    		WHERE id = ".$destinatario["Destinatario"]["id"]."
    	");

		$this->Session->write("status_mensaje", "Recibido");

		$this->redirect("/".$tipo."/datos/".$id);
	}


	public function mensaje_enviado($tipo = null, $id = null)
	{
		$this->Session->write("status_mensaje", "Enviado");

		$this->redirect("/".$tipo."/datos/".$id);
	}


	public function firmar_mensaje($id = null)
	{
		$this->loadModel('Colegio');
		$this->loadModel('Destinatario');

		$user_id = $this->Session->read('Auth.User.id');
		$comunicado_id = $this->Colegio->desencriptacion($id);

		$condiciones = array(
			'Destinatario.user_id' => $user_id,
			'Destinatario.comunicado_id' => $comunicado_id
		);

		if (!empty($this->Session->read("mi_hijo")))
			$condiciones['Destinatario.hijo'] = $this->Session->read("mi_hijo");

		$destinatario = $this->Destinatario->find('first', array(
			'conditions' => $condiciones,
			'fields' => array(
				'Destinatario.id',
				'Destinatario.fecha_firmado'
			)
		));

		if (empty($destinatario["Destinatario"]["fecha_firmado"]))
		{
			//Fecha de firma del mensaje
			date_default_timezone_set('America/Mexico_City');
        	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			 
			$fecha_firmado = $dias[date('w')].", ".date('g:i a')." - ".$meses[date('n')-1]." ".date('d'). ", ".date('Y');

			$destinatario["Destinatario"]["fecha_firmado"] = $fecha_firmado;
		}

		$destinatario["Destinatario"]["firmado"] = 1;

    	$this->Destinatario->query("
    		UPDATE kappi.destinatarios
    		SET firmado = ".$destinatario["Destinatario"]["firmado"].",
    			fecha_firmado = '".$destinatario["Destinatario"]["fecha_firmado"]."'
    		WHERE id = ".$destinatario["Destinatario"]["id"]."
    	");

		$this->redirect($this->Session->read("una_pag_antes"));
	}


//-------------------------------------------------------------------------


	public function datos($id = null)
	{
		$this->loadModel("Colegio");
		$this->loadModel("Destinatario");
		$this->loadModel("Archivo");
		$this->loadModel("ImagenesComunicado");

	    $this->set("comunicado_id", $id);

		$id = $this->Colegio->desencriptacion($id);
		$user_id = $this->Session->read('Auth.User.id');

		if (!$this->Comunicado->exists($id, "comunicados"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese mensaje no existe.");
			$this->redirect("/no_autorizado");
		}

		$mensaje = $this->Comunicado->find('first', array(
			'conditions' => array('Comunicado.id' => $id),
			'fields' => array(
				'guardado', 'asunto', 'mensaje', 'user_id'
			)
		));

		if ($this->Session->read("status_mensaje") == "Enviado")
		{	
			$destinatarios = $this->Destinatario->query("
				SELECT des.visto, des.fecha_visto, des.firmado, des.fecha_firmado, usu.a_paterno, usu.a_materno, usu.nombre, usu.tipo
				FROM kappi.destinatarios as des, kappi.users as usu
				WHERE des.comunicado_id = $id
					AND des.user_id = usu.id
				ORDER BY usu.a_paterno ASC,
					usu.a_materno ASC,
					usu.nombre ASC
			");
		}

		$imagenes = $this->ImagenesComunicado->query("
			SELECT img.id, img.ruta, img.nombre
			FROM kappi.imagenes as img, kappi.imagenes_comunicados as ic
			WHERE ic.comunicado_id = $id
				AND ic.imagene_id = img.id
		");

		foreach ($imagenes as $key => $imagen)
		{
			$id_encriptada = $this->Colegio->encriptacion($imagen[0]["id"]);
			$imagenes[$key][0]["encriptada"] = $id_encriptada;
		}

		$todos_pdf = $this->Archivo->find('all', array(
			'conditions' => array('Archivo.comunicado_id' => $id),
			'fields' => array('id', 'nombre')
		));

		foreach ($todos_pdf as $key => $pdf)
		{
			$id_encriptada = $this->Colegio->encriptacion($pdf["Archivo"]["id"]);
			$todos_pdf[$key]["Archivo"]["encriptada"] = $id_encriptada;
		}

		if ($this->Session->read("status_mensaje") == "Recibido")
		{
			$condiciones = array(
				'Destinatario.user_id' => $user_id,
				'Destinatario.comunicado_id' => $id
			);

			if (!empty($this->Session->read("mi_hijo"))) 
				$condiciones['Destinatario.hijo'] = $this->Session->read("mi_hijo");

			$recibido_por = $this->Destinatario->find('first', array(
				'conditions' => $condiciones,
				'fields' => array('Destinatario.id', "Destinatario.firmado")
			));
		}

		@$this->Session->write("DestinatariosCompletos", $destinatarios);

	    $this->set("mensaje", $mensaje);
	    $this->set("imagenes", $imagenes);
	    $this->set("todos_pdf", $todos_pdf);
	    @$this->set("destinatarios", $destinatarios);
	    @$this->set("recibido_por", $recibido_por);
	}
	

//-------------------------------------------------------------------------
	

	public function filtrar_enviados()
	{
		$this->layout='ajax';

		$data = $this->request->data;
		$data['enviados'] = unserialize(base64_decode($data['enviados']));
		$user_tipo = $this->Session->read('Auth.User.tipo');

		$todoVacio = false;
		if (empty($data['asunto']) &&
			empty($data['fecha']) &&
			empty($data['emisor']))
		{	
			$todoVacio = true;
		}


		if (!empty($data['asunto']))
		{
			foreach ($data['enviados'] as $key => $enviado)
			{
				if (stripos($enviado['Comunicado']['asunto'], $data['asunto']) === false)
				{
					unset($data['enviados'][$key]);
				}
			}	
		}

		if (!empty($data['fecha']))
		{
			foreach ($data['enviados'] as $key => $enviado)
			{
				if (stripos($enviado['Comunicado']['fecha'], $data['fecha']) === false)
				{
					unset($data['enviados'][$key]);
				}
			}	
		}

		if (!empty($data['emisor']))
		{
			foreach ($data['enviados'] as $key => $enviado)
			{
				$nombre_completo = $enviado['User']['a_paterno']." ".$enviado['User']['a_materno'].", ".$enviado['User']['nombre'];

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
	

	public function filtrar_guardados()
	{
		$this->layout='ajax';

		$data = $this->request->data;
		$data['guardados'] = unserialize(base64_decode($data['guardados']));
		$user_tipo = $this->Session->read('Auth.User.tipo');

		$todoVacio = false;
		if (empty($data['asunto']) &&
			empty($data['mensaje']) &&
			empty($data['emisor']))
		{	
			$todoVacio = true;
		}


		if (!empty($data['asunto']))
		{
			foreach ($data['guardados'] as $key => $enviado)
			{
				if (stripos($enviado['Comunicado']['asunto'], $data['asunto']) === false)
				{
					unset($data['guardados'][$key]);
				}
			}	
		}

		if (!empty($data['mensaje']))
		{
			foreach ($data['guardados'] as $key => $enviado)
			{
				if (stripos($enviado['Comunicado']['mensaje'], $data['mensaje']) === false)
				{
					unset($data['guardados'][$key]);
				}
			}	
		}

		if (!empty($data['emisor']))
		{
			foreach ($data['guardados'] as $key => $enviado)
			{
				$nombre_completo = $enviado['User']['a_paterno']." ".$enviado['User']['a_materno'].", ".$enviado['User']['nombre'];

				if (stripos($nombre_completo, $data['emisor']) === false)
				{
					unset($data['guardados'][$key]);
				}
			}	
		}

		$this->set("tipo", $data['tipo']);
		$this->set("user_tipo", $user_tipo);
		if ($todoVacio)
			$this->set("guardados", $this->Session->read("GuardadosCompletos"));
		else
			$this->set("guardados", $data['guardados']);
	}
	

//-------------------------------------------------------------------------
	

	public function filtrar_recibidos()
	{
		$this->layout='ajax';

		$data = $this->request->data;
		$data['recibidos'] = unserialize(base64_decode($data['recibidos']));
		$user_tipo = $this->Session->read('Auth.User.tipo');

		$todoVacio = false;
		if (empty($data['asunto']) &&
			empty($data['fecha']) &&
			empty($data['emisor']))
		{	
			$todoVacio = true;
		}


		if (!empty($data['asunto']))
		{
			foreach ($data['recibidos'] as $key => $enviado)
			{
				if (stripos($enviado['Comunicado']['asunto'], $data['asunto']) === false)
				{
					unset($data['recibidos'][$key]);
				}
			}	
		}

		if (!empty($data['fecha']))
		{
			foreach ($data['recibidos'] as $key => $enviado)
			{
				if (stripos($enviado['Comunicado']['fecha'], $data['fecha']) === false)
				{
					unset($data['recibidos'][$key]);
				}
			}	
		}

		if (!empty($data['emisor']))
		{
			foreach ($data['recibidos'] as $key => $enviado)
			{
				$nombre_completo = $enviado['User']['a_paterno']." ".$enviado['User']['a_materno'].", ".$enviado['User']['nombre'];

				if (stripos($nombre_completo, $data['emisor']) === false)
				{
					unset($data['recibidos'][$key]);
				}
			}	
		}

		$this->set("tipo", $data['tipo']);
		$this->set("user_tipo", $user_tipo);
		if ($todoVacio)
			$this->set("recibidos", $this->Session->read("RecibidosCompletos"));
		else
			$this->set("recibidos", $data['recibidos']);
	}
	

//-------------------------------------------------------------------------
	

	public function filtrar_destinatarios()
	{
		$this->layout='ajax';

		$data = $this->request->data;
		$data['destinatarios'] = unserialize(base64_decode($data['destinatarios']));
		
		$todoVacio = false;
		if (empty($data['nombre']) &&
			empty($data['tipo']) &&
			empty($data['visto']) &&
			empty($data['firmado']))
		{	
			$todoVacio = true;
		}


		if (!empty($data['nombre']))
		{
			foreach ($data['destinatarios'] as $key => $destinatario)
			{
				$nombre_completo = $destinatario[0]['a_paterno']." ".$destinatario[0]['a_materno'].", ".$destinatario[0]['nombre'];

				if (stripos($nombre_completo, $data['nombre']) === false)
				{
					unset($data['destinatarios'][$key]);
				}
			}	
		}

		if (!empty($data['tipo']))
		{
			foreach ($data['destinatarios'] as $key => $destinatario)
			{
				if (stripos($destinatario[0]['tipo'], $data['tipo']) === false)
				{
					unset($data['destinatarios'][$key]);
				}
			}	
		}

		if (!empty($data['visto']))
		{
			foreach ($data['destinatarios'] as $key => $destinatario)
			{
				$visto_valor;
				if ($destinatario[0]['visto'] == 0)
					$visto_valor = "No";
				else
					$visto_valor = "Si";

				if (stripos($visto_valor, $data['visto']) === false)
				{
					unset($data['destinatarios'][$key]);
				}
			}	
		}

		if (!empty($data['firmado']))
		{
			foreach ($data['destinatarios'] as $key => $destinatario)
			{
				$firmado_valor = "p";
				if ($destinatario[0]['firmado'] == 0)
					$firmado_valor = "No";
				if ($destinatario[0]['firmado'] == 1)
					$firmado_valor = "Si";

				if (stripos($firmado_valor, $data['firmado']) === false)
				{
					unset($data['destinatarios'][$key]);
				}
			}	
		}

		if ($todoVacio)
			$this->set("destinatarios", $this->Session->read("DestinatariosCompletos"));
		else
			$this->set("destinatarios", $data['destinatarios']);
	}
	

//-------------------------------------------------------------------------


	public function escribir($mensaje_id = null)
	{
		$this->loadModel("Colegio");
		$this->loadModel("Nivele");
		$this->loadModel("Salone");
		$this->loadModel("Ciclo");
		$this->loadModel("User");
		$this->loadModel("AlumnosInscrito");
		
		$colegio_id = $this->Session->read("mi_colegio");

		$ciclo_id = $this->Ciclo->find('first', array(
	    	'conditions' => array(
	    		'Ciclo.colegio_id' => $colegio_id,
	    		'Ciclo.activo' => 1
	    	),
	    	'order' => array('Ciclo.created' => 'desc'),
	    	'fields' => array('Ciclo.id')
	    ));

		$ciclo_id = $ciclo_id["Ciclo"]["id"];
		$emisor_id = $this->Session->read('Auth.User.id');

		if (!empty($this->request->data))
		{
			$data = $this->request->data;
			$data["Comunicado"]["tipo"] = $this->Session->read("tipo");
			$data["Comunicado"]["user_id"] = $emisor_id;
			$data["Comunicado"]["ciclo_id"] = $ciclo_id;
			$alumnos_escogidos;
			$destinatarios;

			if ($data["action"] != "sg")
			{	
				//Es tarea o comunicado
				if ($this->Session->read("tipo") != "Circular")
				{
					if ($data["modo"] == 2 || $data["modo"] == 4)
					{
						$alumnos = $this->request->data["Comunicado"]["Alumnos"];
						foreach ($alumnos as $key => $value)
						{
							$alumno_id = $this->Colegio->desencriptacion($key);
							$alumnos_escogidos[$alumno_id] = $alumno_id;
						}
					}

					if ($data["modo"] == 1 || $data["modo"] == 3)
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
								$alumnos_escogidos[$key] = $key;
							}
						}
					}

					$destinatarios = $this->Comunicados->traerFamilia($alumnos_escogidos);
				}
				else //Es una circular
				{
					$data["modo"] = 1;

					if ($data["nivel"] == "todos")
					{
						//Cuando escoge a toda la escuela
						$alumnos_circular = $this->Comunicados->traerDestNiveles($colegio_id);
					}
					else
					{
						$valido = $this->Colegio->validarIdSelect($data['nivel']);
						if ($valido == 1)
						{
							if ($data["grado"] == "todos")
							{
								//Cuando escoge a todos los grados de cierto nivel
								$nivele_id = $this->Colegio->desencriptacion($data['nivel']);
								$alumnos_circular = $this->Comunicados->traerDestGrados($nivele_id);
							}
							else
							{
								$valido = $this->Colegio->validarIdSelect($data['grado']);
								if ($valido == 1)
								{
									if ($data["salon"] == "todos")
									{
										//Cuando escoge a todos los salones de cierto grado
										$grado_id = $this->Colegio->desencriptacion($data['grado']);
										$alumnos_circular = $this->Comunicados->traerDestSalones($grado_id);
									}
									else
									{
										//Cuando se escoge un salone especifico
										$valido = $this->Colegio->validarIdSelect($data['salon']);
										if ($valido == 1)
										{
											$salone_id = $this->Colegio->desencriptacion($data['salon']);
											$alumnos_circular = $this->Comunicados->destSalonEspecifico($salone_id);
										}
									}
								}
							}
						}
					}

					$toda_familia = $this->Comunicados->traerFamilia($alumnos_circular);

					//Una vez se tienen los destinatarios ahora hay que saber si se manda a toda la familia, solo padres, solo madres, etc.
					$destinatarios = $this->Comunicados->filtrarFamilia($toda_familia, $data);
				}
			}
			else
				$destinatarios = 0;

			@$mandado = $this->Comunicados->agregarBDD($destinatarios, $data);

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

		$reusado;
		if (!empty($mensaje_id))
		{
			$mensaje_id = $this->Colegio->desencriptacion($mensaje_id);

			if (!$this->Comunicado->exists($mensaje_id, "comunicados"))
			{
				$this->Session->write("mensaje_autorizacion", "Ese mensaje no existe.");
				$this->redirect("/no_autorizado");
			}

			$reusado = $this->Comunicado->find('first', array(
				'conditions' => array('id' => $mensaje_id),
				'fields' => array('id', 'asunto', 'mensaje', 'firmado')
			));

			$reusado['Archivo'] = $this->Comunicado->query("
				SELECT id
				FROM kappi.archivos
				WHERE comunicado_id = $mensaje_id
			");

			$reusado['Imagene'] = $this->Comunicado->query("
				SELECT img.id, img.ruta, img.nombre
				FROM kappi.imagenes as img, kappi.imagenes_comunicados as ic
				WHERE ic.comunicado_id = $mensaje_id
					AND ic.imagene_id = img.id
			");

			foreach ($reusado["Archivo"] as $key => $pdf)
			{
				$id_encriptada = $this->Colegio->encriptacion($pdf[0]["id"]);
				$reusado["Archivo"][$key][0]["encriptada"] = $id_encriptada;
			}
			foreach ($reusado["Imagene"] as $key => $imagen)
			{
				$id_encriptada = $this->Colegio->encriptacion($imagen[0]["id"]);
				$reusado["Imagene"][$key][0]["encriptada"] = $id_encriptada;
			}
		}

		//Cuando es profesor, le aparecera su unico salon del ciclo
		$salones = $this->Salone->find('all', array(
			'recursive' => 0,
			'conditions' => array(
				'Salone.ciclo_id' => $ciclo_id,
				'Salone.user_id' => $emisor_id
			),
			'fields' => array('Salone.id', 'Salone.nombre')
		));

		foreach ($salones as $key => $salon)
		{
			$id_encriptada = $this->Colegio->encriptacion($salon["Salone"]["id"]);
			$salones[$key]["Salone"]["encriptada"] = $id_encriptada;
		}

		//Cuando es administrador, le apareceran todos los niveles
		if ($this->Session->read('Auth.User.tipo') == "Administrador")
		{	
			$niveles = $this->Nivele->find('all', array(
				'conditions' => array('Nivele.colegio_id' => $colegio_id),
				'fields' => array('id', 'nombre')
			));

			foreach ($niveles as $key => $nivel)
			{
				$id_encriptada = $this->Colegio->encriptacion($nivel["Nivele"]["id"]);
				$niveles[$key]["Nivele"]["encriptada"] = $id_encriptada;
			}
		}

		
	    $this->set("salones", $salones);
	    @$this->set("niveles", $niveles);
	    @$this->set("reusado", $reusado);
	}

}
