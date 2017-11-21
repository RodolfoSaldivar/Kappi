<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('File', 'Utility');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('RequestHandler', 'Paginator', 'Flash', 'Qimage', 'Session', 'Usuarios');


//-------------------------------------------------------------------------


	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('logout', 'olvide_contrasena', 'resetear_contra');
	}


//-------------------------------------------------------------------------


	public function isAuthorized($user)
	{
		//Acceso para todos
		if (in_array($this->action, array('login', 'logout', 'olvide_contrasena', 'resetear_contra', 'cambiar_contrasena', 'cambiar_foto')))
		{
            return true;
        }

        //Acceso para Admins
        if (in_array($this->action, array('filtrar_usuarios', 'datos', 'agregar', 'editar', 'miembros_de_familia', 'activo_actualizar', 'alumnos', 'padres', 'madres', 'maestros', 'administradores', 'mostrar_todos', 'resultados', 'descargar_excel', 'subir_excel')))
        {
            if (isset($user['tipo']) && in_array($user['tipo'], array("Administrador")))
    		{
    			return true;
    		}
        }

        //Acceso para Madres y Padres
        if (in_array($this->action, array('escoger_hijo')))
        {
            if (isset($user['tipo']) && in_array($user['tipo'], array("Madre", "Padre")))
    		{
    			return true;
    		}
        }

	    return parent::isAuthorized($user);
    }


//-------------------------------------------------------------------------


	public function login() 
	{
		$this->layout = 'login';

	    if ($this->request->is('post'))
	    {
			$data = $this->request->data;

			$result = $this->User->find("first", array(
				'conditions' => array('User.username' => $data['User']['username']),
				'fields' => array(
					'id', 'identificador', 'username', 'password', 'nombre', 'a_paterno', 'a_materno', 'correo', 'tipo', 'celular', 'clave', 'activo', 'imagene_id', 'familia_id', 'colegio_id', 'grado_id' 
				)
			));
			
			$blowF = new BlowfishPasswordHasher();

	        if (@$blowF->check($data['User']['password'], $result['User']['password']))
	        {	
	        	$this->loadModel("Colegio");
	        	$this->loadModel("Imagene");

	        	$colegio = $this->Colegio->find("first", array(
					'conditions' => array('id' => $result['User']['colegio_id']),
					'fields' => array(
						'id', 'nombre_comercial', 'nombre_corto', 'imagene_id', 'activo' 
					)
				));
				$result['User']['Colegio'] = $colegio['Colegio'];

	        	
				if (!empty($result['User']['imagene_id']))
				{
					$imagen = $this->Imagene->find("first", array(
						'conditions' => array('id' => $result['User']['imagene_id']),
						'fields' => array(
							'id', 'ruta', 'nombre' 
						)
					));
					$result['User']['Imagene'] = $imagen['Imagene'];
				}

	        	$this->Auth->login($result['User']);

	        	if ($this->Session->read('Auth.User.activo') == 0)
    			{
        			$this->Session->setFlash('Su cuenta ha sido deshabilitada.');
    				$this->redirect($this->Auth->logout());
				}

				$this->Session->write("numero_hijos", 1);

	        	//Es superadministrador
	        	if ($this->Session->read('Auth.User.tipo') == "Superadministrador")
	        		$this->redirect("/seleccionar_colegio");
	        	else
	        	{
	        		$this->Session->write("mi_colegio", $this->Session->read('Auth.User.colegio_id'));
        			$this->Session->write("colegio_seleccionado", true);

	        		//Colegio desactivado
        			if ($this->Session->read('Auth.User.Colegio.activo') == 0)
        			{
	        			$this->Session->setFlash('Su colegio ha sido deshabilitado.');
        				$this->redirect($this->Auth->logout());
					}
					else
					{
        				//Es madre o padre
						if (in_array($this->Session->read('Auth.User.tipo'), array("Madre", "Padre")))
						{
							//No tiene hijos registrados
							if (empty($this->Session->read('Auth.User.familia_id')))
							{
			        			$this->Session->setFlash('No tiene hijos registrados a su nombre.');
		        				$this->redirect($this->Auth->logout());
							}
							else
							{
								$hijos = $this->User->find('count', array(
									'conditions' => array(
										'User.tipo' => 'Alumno',
										'User.colegio_id' => $this->Session->read('mi_colegio'),
										'User.familia_id' => $this->Session->read('Auth.User.familia_id')
									)
								));

								//Tiene mas de 1 hijo en el sistema
								if ($hijos > 1)
								{
									$this->Session->write("numero_hijos", "muchos");
									$this->redirect("/escoger_hijo");
								}
								else
								{
									$mi_hijo = $this->User->find('first', array(
										'fields' => array('User.id'),
										'conditions' => array(
											'User.tipo' => 'Alumno',
											'User.colegio_id' => $this->Session->read('mi_colegio'),
											'User.familia_id' => $this->Session->read('Auth.User.familia_id')
										)
									));
									$this->Session->write("mi_hijo", $mi_hijo['User']['id']);
									$this->redirect($this->Auth->redirectURL());
								}
							}
						}
						else
							$this->redirect($this->Auth->redirectURL());
					}

	        	}
	        }
	        else
	        	$this->Session->setFlash('Usuario o contraseña incorrecta.');
	    }
	}


//-------------------------------------------------------------------------


	public function logout()
	{
		$this->Session->destroy();
	    return $this->redirect($this->Auth->logout());
	}


//-------------------------------------------------------------------------


	public function seleccionar_colegio()
	{
		if ($this->Session->read('colegio_seleccionado') && $this->Session->read('Auth.User.tipo') != "Superadministrador")
			$this->redirect($this->Auth->redirectURL());

	    $this->layout = 'login';

	    $this->loadModel("Colegio");
	    $this->loadModel("Imagene");

	    //Cuando escoge el colegio
	    if (!empty($this->request->data))
		{
			$this->Session->write("mi_colegio", $this->request->data("Colegio.id"));
			$this->Session->write("colegio_seleccionado", true);
			$this->redirect($this->Auth->redirectUrl());
		}

		$colegios = $this->Colegio->find('all', array(
	    	'order' => array(
	    		'activo' => 'desc',
	    		'nombre_comercial' => 'asc'
	    	),
	    	'conditions' => array('activo' => 1),
			'fields' => array(
				'id', 'nombre_comercial', 'nombre_corto', 'imagene_id', 'activo' 
			)
	    ));

	    foreach ($colegios as $key => $colegio)
	    {
	    	$imagen = $this->Imagene->find('first', array(
	    		'conditions' => array('id' => $colegio['Colegio']['imagene_id']),
	    		'fields' => array('nombre', 'ruta')
	    	));
	    	$colegios[$key]['Imagene'] = $imagen['Imagene'];
	    }

	    $this->set('colegios', $colegios);
	}


//-------------------------------------------------------------------------


	public function escoger_hijo()
	{
		if ($this->Session->read('numero_hijos') == 1)
			$this->redirect($this->Auth->redirectURL());

	    $this->layout = 'login';

	    $this->loadModel("Colegio");
	    $this->loadModel("Imagene");

	    //Cuando escoge el hijo
	    if (!empty($this->request->data))
		{
			$this->Session->write("mi_hijo", $this->request->data("User.id"));
			$this->redirect($this->Auth->redirectUrl());
		}

		$hijos = $this->User->find('all', array(
			'conditions' => array(
				'User.tipo' => 'Alumno',
				'User.colegio_id' => $this->Session->read('mi_colegio'),
				'User.familia_id' => $this->Session->read('Auth.User.familia_id'),
				'User.activo' => 1
			),
			'fields' => array('id', 'nombre', 'a_paterno', 'a_materno', 'activo', 'imagene_id')
		));

	    foreach ($hijos as $key => $hijo)
	    {
	    	$imagen = $this->Imagene->find('first', array(
	    		'conditions' => array('id' => $hijo['User']['imagene_id']),
	    		'fields' => array('nombre', 'ruta')
	    	));
	    	@$hijos[$key]['Imagene'] = $imagen['Imagene'];
	    }

		$this->Session->write("todos_mis_hijos", $hijos);

	    $this->set('hijos', $hijos);
	}


//-------------------------------------------------------------------------


	public function olvide_contrasena()
	{
		$this->layout = 'login';

		if ($this->request->is('post'))
		{
			$usu_username = $this->request->data['User']['username'];

			$usuario = $this->User->find('first', array(
		    	'conditions' => array('User.username' => $usu_username),
		    	'fields' => array(
		    		'correo', 'clave', 'nombre', 'a_paterno', 'a_materno'
		    	)
		    ));

		    if (empty($usuario)) {
		    	$this->Session->setFlash('Usuario no existente.');
		    }
		    else
		    {
			    $correo_a_mandar = $usuario['User']['correo'];
			    $clave_usuario = $usuario['User']['clave'];
			    $nombre = $usuario['User']['nombre'];
			    $a_paterno = $usuario['User']['a_paterno'];
			    $a_materno = $usuario['User']['a_materno'];

				//Obtiene el metodo directamente del modelo de User
				$nueva_contra = $this->User->randomString();

				$url = "http://www.kappi.com.mx/resetear_contra/$clave_usuario/$nueva_contra";

				$Email = new CakeEmail();
				$Email->template('recuperar_contra', 'recuperar_contra');
				$Email->emailFormat('html');
				$Email->config('smtp');
				$Email->to($correo_a_mandar);
				$Email->subject('Cambio de Contraseña');
				$Email->viewVars(array(
					'usu_username' => $usu_username,
					'nombre' => $nombre,
					'a_paterno' => $a_paterno,
					'a_materno' => $a_materno,
					'nueva_contra' => $nueva_contra
				));
				$Email->send($url);

		    	$this->Session->setFlash('Se le mandó un correo electrónico, siga las instrucciones.');
		    }
		}
	}


//-------------------------------------------------------------------------


	public function resetear_contra($clave = null, $nueva_contra = null)
	{
		$usuario = $this->User->find('first', array(
	    	'conditions' => array('User.clave' => $clave),
	    	'fields' => array('User.id'),
	    	'recursive' => 0
	    ));

	    //Obtiene el metodo directamente del modelo de User
	    $nueva_clave = $this->User->randomString();
	    $id = $usuario['User']['id'];

		if (@$this->User->exists($id, "users"))
		{
			$blowF = new BlowfishPasswordHasher();
			$contra_encr = $blowF->hash($nueva_contra);

			$this->User->query("
				UPDATE kappi.users
				SET clave = '$nueva_clave',
					password = '$contra_encr'
				WHERE id = $id
			");

			$this->redirect('cambiar_contrasena');
		}
	}


//-------------------------------------------------------------------------


	public function cambiar_contrasena()
	{
		$this->layout = "login";

		if (!empty($this->request->data))
		{
			$user_id = $this->Session->read('Auth.User.id');

			$usuario = $this->User->find('first', array(
		    	'conditions' => array('User.id' => $user_id),
		    	'fields' => array('User.id', 'User.password')
		    ));

		    $contra_bdd = $usuario["User"]["password"];
		    $contra_vieja = $this->request->data("User.vieja");
		    $contra_nueva = $this->request->data("User.nueva");

		    //Verifica que la contraseña dada sea la misma que la de la base de datos
		    $blowF = new BlowfishPasswordHasher();
			if ($blowF->check($contra_vieja, $contra_bdd))
	        {
	        	//Checa que la nueva contraseña sea alfanumerica
	        	if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $contra_nueva))
					$this->Session->setFlash('Contraseña nueva solo letras y números.');
				else
				{
		        	//Checa que tenga más de 8 caracteres
		        	if (strlen($contra_nueva) < 8)
	   					$this->Session->setFlash('Mínimo 8 caracteres.');
	   				else
	   				{
	   					//Checa que tenga menos de 20 caracteres
			        	if (strlen($contra_nueva) > 20)
		   					$this->Session->setFlash('Máximo 20 caracteres.');
		   				else
		   				{
		   					//Esta validado, ahora si se hace el cambio
		   					$contra_encr = $blowF->hash($contra_nueva);
							$this->User->query("
								UPDATE kappi.users
								SET password = '$contra_encr'
								WHERE id = ".$usuario["User"]["id"]."
							");

							$this->redirect('/');
		   				}
	   				}
				}
	        }
	        else
	        {
	        	$this->Session->setFlash('Contraseña actual incorrecta.');
	        }
		}
	}
	

//-------------------------------------------------------------------------


	public function cambiar_foto()
	{
		$this->loadModel("Imagene");
		$this->loadModel("Colegio");

		$id = $this->Session->read('Auth.User.id');

		//Obtiene el usuario
		$usuario = $this->User->find('first', array(
			'conditions' => array('User.id' => $id),
			'fields' => array(
				'Imagene.id',
				'Imagene.ruta',
				'Imagene.nombre'
			),
			'recursive' => 0
		));

		$this->set('usuario', $usuario);

		$imagenBorrar['id'] = $usuario['Imagene']['id'];
		$imagenBorrar['ruta'] = $usuario['Imagene']['ruta'];
		$imagenBorrar['nombre'] = $usuario['Imagene']['nombre'];

		if (!empty($this->request->data))
		{
			$imgObligatoria = true;
			$imagen_valida = $this->Colegio->logoAgregarValidar($this->request->data['User']['foto'], $imgObligatoria);

			if ($imagen_valida != 1)
	    	{
	    		$this->Session->setFlash($imagen_valida);
	    	}
	    	else
	    	{
	    		if (!empty($this->request->data['User']['foto']['name']))
	        	{
	        		//Ruta en donde sera guardada la imagen
	        		$ruta = '/img/usuarios/';

		        	//Se produce el nombre de la imagen
		        	$nombre = $this->Qimage->traerNombre($ruta, $this->request->data['User']['foto']);

		        	//Se prepara para guardarse en la bdd
		        	$datos_imagen['Imagene']['nombre'] = $nombre;
		        	$datos_imagen['Imagene']['ruta'] = $ruta;

		        	//Crea y guarda
		        	$this->Imagene->query("
		        		INSERT INTO kappi.imagenes (nombre, ruta)
		        		VALUES
		        		(
		        			'".$datos_imagen['Imagene']['nombre']."',
		        			'".$datos_imagen['Imagene']['ruta']."'
		        		)
		        	");

		        	//Se obtiene el id de la imagen recien guardada
		        	$imagen_id = $this->Imagene->query("
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


		        	$user_id = $this->request->data['User']['id'];

		        	//Se guarda el usuario
		        	$this->User->query("
		        		UPDATE kappi.users
		        		SET imagene_id = $imagen_id
		        		WHERE id = $user_id
		        	");

		        	//Se borra la imagen vieja y se quita de la carpeta
		        	if (!empty($imagenBorrar['ruta']))
		        	{
		        		$ruta_borrar = $imagenBorrar['ruta'].$imagenBorrar['nombre'];
		        		@$this->Imagene->query("
		        			DELETE FROM kappi.imagenes WHERE id = ".$imagenBorrar['id']."
		        		");
		        		$file = new File(WWW_ROOT.$ruta_borrar);
						$file->delete();

	        			$this->Session->write('Auth.User.Imagene.nombre', $nombre);
		        	}

		        	$this->redirect('/');
	        	}
	        }
		}
	}


//-------------------------------------------------------------------------
	

	public function filtrar_usuarios()
	{
		$this->loadModel("Colegio");

		$this->layout='ajax';

		$data = $this->request->data;
		
		$todoVacio = false;
		if (empty($data['username']) &&
			empty($data['nombre']) &&
			empty($data['correo']) &&
			empty($data['identificador']))
		{
			$todoVacio = true;
		}
		else
		{
			//Condiciones default
			$condiciones = array(
				'User.colegio_id' => $this->Session->read("mi_colegio"),
				'User.tipo' => $this->Session->read("tipo")
			);

			//Agrega la condicion si su campo no esta vacio
			if (!empty($data["username"]))
				$condiciones["CHARINDEX('".$data["username"]."', User.username) >"] = "0";
			if (!empty($data["correo"]))
				$condiciones["CHARINDEX('".$data["correo"]."', User.correo) >"] = "0";
			if (!empty($data["identificador"]))
				$condiciones["CHARINDEX('".$data["identificador"]."', User.identificador) >"] = "0";
			if (!empty($data["nombre"]))
				$condiciones[
					"CHARINDEX('".$data["nombre"]."',
					CONCAT(User.a_paterno, ' ', User.a_materno, ', ', User.nombre)) >"
				] = "0";

			//Busca
			$encontrados = $this->User->find('all', array(
				'conditions' => $condiciones,
				'order' => array(
					'User.activo' => 'desc',
					'User.a_paterno' => 'asc',
					'User.a_materno' => 'asc',
					'User.nombre' => 'asc'
				),
				'fields' => array(
					'User.nombre', 'User.a_paterno', 'User.a_materno',
					'User.identificador', 'User.username', 'User.correo',
					'User.activo', 'User.imagene_id', 'Imagene.ruta', 'Imagene.nombre'
				),
				'recursive' => 0
			));


			//Mete la encriptacion
		    foreach ($encontrados as $key => $usuario)
		    {
		    	$id_encriptada = $this->Colegio->encriptacion($usuario['User']['id']);
		    	$encontrados[$key]['User']['encriptada'] = $id_encriptada;
		    }
		}

	    //Checa que no este todo vacio
		if ($todoVacio)
			$this->set("usuarios", $this->Session->read("UsuariosCompletos"));
		else
			$this->set("usuarios", $encontrados);

		$this->set("tipo", $data['tipo']);
		
	}


//-------------------------------------------------------------------------


	public function datos($id = null)
	{
		$this->loadModel('Colegio');

		$id = $this->Colegio->desencriptacion($id);

		if (!$this->User->exists($id, "users"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese usuario no está registrado.");
			$this->redirect("/no_autorizado");
		}

		//Obtiene el usuario
		$usuario = $this->User->find('first', array(
			'conditions' => array('User.id' => $id),
			'recursive' => 0,
			'fields' => array(
				'User.nombre', 'User.a_paterno', 'User.a_materno',
				'User.identificador', 'User.username', 'User.correo',
				'User.activo', 'User.imagene_id', 'Imagene.ruta', 'Imagene.nombre',
				'Familia.id', 'Familia.nombre', 'Familia.identificador'
			)
		));

		$miembros = null;
		if (!empty($usuario['Familia']['id']))
		{
			$miembros = $this->User->find('all', array(
				'recursive' => 0,
				'conditions' => array('User.familia_id' => $usuario['Familia']['id']),
				'order' => array(
					'User.a_paterno' => 'asc',
					'User.a_materno' => 'asc',
					'User.nombre' => 'asc'
				),
				'fields' => array(
					'User.nombre', 'User.a_paterno', 'User.a_materno',
					'User.identificador', 'User.username', 'User.correo',
					'User.activo', 'User.imagene_id', 'Imagene.ruta', 'Imagene.nombre'
				)
			));
		}

		$this->set('usuario', $usuario);
		$this->set('miembros', $miembros);
	}
	

//-------------------------------------------------------------------------


	public function agregar()
	{
		$this->loadModel("Familia");
		$this->loadModel("Imagene");
		$this->loadModel("Colegio");
		$this->loadModel("Nivele");
		$this->loadModel("Grado");

		if (!empty($this->request->data))
		{
			if ($this->Session->read("tipo") == "Alumno")
				$correoYcelularObligatorio = false;
			else
				$correoYcelularObligatorio = true;

			$imagenObligatoria = false;
			$tipo = $this->Session->read("tipo");
			$editado = false;
			$agregado = $this->Usuarios->agregarBDD($this->request->data, $imagenObligatoria, $correoYcelularObligatorio, $tipo, $editado);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla de colegios
	        	$this->redirect($this->Session->read("una_pag_antes"));
	        }
		}

		if (empty($this->request->data))
			$this->Session->setFlash('El nombre de la cuenta se creará una vez llene los datos.');
		
		$colegio_id = $this->Session->read("mi_colegio");

		//Solo si es un alumno
		$nivelesYgrados;
		if ($this->Session->read("tipo") == "Alumno") 
		{
			$nivelesYgrados = $this->Nivele->find('all', array(
				'conditions' => array('Nivele.colegio_id' => $colegio_id),
				'fields' => array('id', 'nombre')
			));

			foreach ($nivelesYgrados as $key => $nivgra)
			{
				$grados = $this->Grado->find('all', array(
					'conditions' => array('nivele_id' => $nivgra["Nivele"]["id"]),
					'fields' => array('id', 'nombre')
				));

				foreach ($grados as $keyGrado => $grado)
				{
					$nivelesYgrados[$key]['Grado'][$keyGrado] = $grado['Grado'];
					$nivelesYgrados[$key]['Grado'][$keyGrado]["id"] = $this->Colegio->encriptacion($grado['Grado']['id']);
				}
			}
		}

		//Todas las familias
		@$familias = $this->Familia->query(
			"SELECT DISTINCT Familia.*
			FROM kappi.users as usuario, kappi.familias as Familia
			WHERE usuario.colegio_id = $colegio_id
				AND usuario.activo = 1
				AND	usuario.familia_id = Familia.id
			ORDER BY Familia.nombre"
		);

		foreach ($familias as $key => $familia)
		{
			$id = $this->Colegio->encriptacion($familia[0]['id']);
			$familias[$key][0]['id'] = $id;
		}

		@$this->set('familias', $familias);
		@$this->set('nivelesYgrados', $nivelesYgrados);
	}


//-------------------------------------------------------------------------


	public function editar($id = null)
	{
		$this->loadModel("Familia");
		$this->loadModel("Imagene");
		$this->loadModel('Colegio');
		$this->loadModel('Nivele');
		$this->loadModel('Grado');

		$id = $this->Colegio->desencriptacion($id);

		if (!$this->User->exists($id, "users"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese usuario no está registrado.");
			$this->redirect("/no_autorizado");
		}

		if (!empty($this->request->data))
		{
        	$this->request->data['Img']['id'] = $this->Session->read('Img.id');
        	$this->request->data['Img']['ruta'] = $this->Session->read('Img.ruta').$this->Session->read('Img.nombre');
        	$this->request->data['Img']['usuario_password'] = $this->Session->read('Img.usuario_password');
        	$this->Session->delete('Img');
        	
			$imagenObligatoria = false;
			$correoYcelularObligatorio = false;
			$tipo = $this->Session->read("tipo");
			$editado = true;
			$agregado = $this->Usuarios->agregarBDD($this->request->data, $imagenObligatoria, $correoYcelularObligatorio, $tipo, $editado);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla de colegios
	        	$this->redirect($this->Session->read("una_pag_antes"));
	        }
		}
		
		$colegio_id = $this->Session->read("mi_colegio");

		//Solo si es un alumno
		$nivelesYgrados;
		if ($this->Session->read("tipo") == "Alumno") 
		{
			$nivelesYgrados = $this->Nivele->find('all', array(
				'conditions' => array('Nivele.colegio_id' => $colegio_id),
				'fields' => array('id', 'nombre')
			));

			foreach ($nivelesYgrados as $key => $nivgra)
			{
				$grados = $this->Grado->find('all', array(
					'conditions' => array('nivele_id' => $nivgra["Nivele"]["id"]),
					'fields' => array('id', 'nombre')
				));

				foreach ($grados as $keyGrado => $grado)
				{
					$nivelesYgrados[$key]['Grado'][$keyGrado] = $grado['Grado'];
					$nivelesYgrados[$key]['Grado'][$keyGrado]["encriptada"] = $this->Colegio->encriptacion($grado['Grado']['id']);
				}
			}
		}

		//Todas las familias
		$familias = $this->Familia->query(
			'SELECT DISTINCT Familia.*
			FROM kappi.users as usuario, kappi.familias as Familia
			WHERE usuario.colegio_id = '.$colegio_id.'
				AND usuario.activo = 1
				AND	usuario.familia_id = Familia.id
			ORDER BY Familia.nombre'
		);

		foreach ($familias as $key => $familia)
		{
			$familia_id = $this->Colegio->encriptacion($familia[0]['id']);
			$familias[$key][0]['encriptada'] = $familia_id;
		}

		//Obtiene el usuario
		$usuario = $this->User->find('first', array(
			'conditions' => array('User.id' => $id),
			'recursive' => 0,
			'fields' => array(
				'User.id', 'User.identificador','User.password', 'User.nombre', 'User.a_paterno', 'User.a_materno', 'User.correo', 'User.tipo', 'User.celular', 'User.activo', 'User.imagene_id', 'User.familia_id', 'User.grado_id','Imagene.id', 'Imagene.nombre', 'Imagene.ruta'
			)
		));
		$usuario['User']['id'] = $this->Colegio->encriptacion($usuario['User']['id']);

		//Recupera las fotos de los usuarios
		if (!empty($usuario["User"]["familia_id"]))
		{
			$familia = $this->User->find('all', array(
				'conditions' => array('User.familia_id' => $usuario["User"]["familia_id"]),
				'fields' => array(
					'User.nombre', 'User.a_paterno', 'User.a_materno', 'User.imagene_id', 'Imagene.nombre', 'Imagene.ruta'
				),
				'recursive' => 0
			));
		}
		else
			$familia = null;

		$this->set('familias', $familias);
		$this->set('usuario', $usuario);
		$this->set('familia', $familia);
		@$this->set('nivelesYgrados', $nivelesYgrados);

		$this->Session->write('Img.id', $usuario['Imagene']['id']);
		$this->Session->write('Img.ruta', $usuario['Imagene']['ruta']);
		$this->Session->write('Img.nombre', $usuario['Imagene']['nombre']);
		$this->Session->write('Img.usuario_password', $usuario['User']['password']);
	}
	

//-------------------------------------------------------------------------


	public function miembros_de_familia()
	{
		$this->layout='ajax';

		$this->loadModel("Imagene");
		$this->loadModel("Colegio");

		$id = $this->Colegio->desencriptacion($this->request->data['familia']);

		//Recupera las fotos de los usuarios
		$familia = $this->User->find('all', array(
			'conditions' => array('User.familia_id' => $id),
			'fields' => array(
				'User.nombre', 'User.a_paterno', 'User.a_materno', 'User.imagene_id', 'Imagene.nombre', 'Imagene.ruta'
			),
			'recursive' => 0
		));

		$this->set('familia', $familia);
	}
	

//-------------------------------------------------------------------------
	

	public function activo_actualizar()
	{
		$this->layout='ajax';

		$this->loadModel("Colegio");

		$id = $this->Colegio->desencriptacion($this->request->data['id']);
		$this->request->data['User']['id'] = $id;

		$this->request->data['User']['encriptada'] = $this->request->data['id'];

		if ($this->request->data['activo'] == 1)
			$this->request->data['User']['activo'] = 0;
		else
			$this->request->data['User']['activo'] = 1;

		$this->User->query("
			UPDATE kappi.users
			SET activo = ".$this->request->data['User']['activo']."
			WHERE id = ".$this->request->data['User']['id']."
		");

		$this->set("alumno", $this->request->data);
	}


//-------------------------------------------------------------------------


	public function alumnos()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");

		//Obtiene el metodo directamente del modelo de User
		$alumnos = $this->User->traerUsuarios($colegio_id, "Alumno");

		$this->Session->write("UsuariosCompletos", $alumnos);
		$this->Session->write("tipo", "Alumno");
		$this->Session->write("tipo_bread", "Alumnos");
		$this->Session->write("una_pag_antes", "/alumnos");

	    $this->set("usuarios", $alumnos);
	    $this->set("tipo", "alumnos");
	}


//-------------------------------------------------------------------------


	public function madres()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");

		//Obtiene el metodo directamente del modelo de User
		$madres = $this->User->traerUsuarios($colegio_id, "Madre");

		$this->Session->write("UsuariosCompletos", $madres);
		$this->Session->write("tipo", "Madre");
		$this->Session->write("tipo_bread", "Madres");
		$this->Session->write("una_pag_antes", "/madres");

	    $this->set("usuarios", $madres);
	    $this->set("tipo", "madres");
	}


//-------------------------------------------------------------------------


	public function padres()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");

		//Obtiene el metodo directamente del modelo de User
		$padres = $this->User->traerUsuarios($colegio_id, "Padre");

		$this->Session->write("UsuariosCompletos", $padres);
		$this->Session->write("tipo", "Padre");
		$this->Session->write("tipo_bread", "Padres");
		$this->Session->write("una_pag_antes", "/padres");

	    $this->set("usuarios", $padres);
	    $this->set("tipo", "padres");
	}


//-------------------------------------------------------------------------


	public function maestros()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");

		//Obtiene el metodo directamente del modelo de User
		$maestros = $this->User->traerUsuarios($colegio_id, "Maestro");

		$this->Session->write("UsuariosCompletos", $maestros);
		$this->Session->write("tipo", "Maestro");
		$this->Session->write("tipo_bread", "Maestros");
		$this->Session->write("una_pag_antes", "/maestros");

	    $this->set("usuarios", $maestros);
	    $this->set("tipo", "maestros");
	}


//-------------------------------------------------------------------------


	public function administradores()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");

		//Obtiene el metodo directamente del modelo de User
		$administradores = $this->User->traerUsuarios($colegio_id, "Administrador");

		$this->Session->write("UsuariosCompletos", $administradores);
		$this->Session->write("tipo", "Administrador");
		$this->Session->write("tipo_bread", "Administradores");
		$this->Session->write("una_pag_antes", "/administradores");

	    $this->set("usuarios", $administradores);
	    $this->set("tipo", "administradores");
	}


//-------------------------------------------------------------------------


	public function superadministradores()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");

		$superadministradores = $this->User->find('all', array(
			'conditions' => array(
				'User.tipo' => "Superadministrador"
			),
			'order' => array(
				'User.activo' => 'desc',
				'User.a_paterno' => 'asc',
				'User.a_materno' => 'asc',
				'User.nombre' => 'asc'
			),
			'recursive' => 0,
			'fields' => array(
				'User.id', 'User.identificador', 'User.nombre', 'User.a_paterno', 'User.a_materno', 'User.username', 'User.correo', 'User.activo', 'User.imagene_id', 'Imagene.nombre', 'Imagene.ruta'
			)
		));

	    foreach ($superadministradores as $key => $usuario)
	    {
	    	$id_encriptada = $this->Colegio->encriptacion($usuario['User']['id']);
	    	$superadministradores[$key]['User']['encriptada'] = $id_encriptada;
	    }

		$this->Session->write("UsuariosCompletos", $superadministradores);
		$this->Session->write("tipo", "Superadministrador");
		$this->Session->write("tipo_bread", "Superadministradores");
		$this->Session->write("una_pag_antes", "/superadministradores");

	    $this->set("usuarios", $superadministradores);
	    $this->set("tipo", "superadministradores");
	}


//-------------------------------------------------------------------------


	public function mostrar_todos()
	{
		$this->loadModel('Colegio');

		$colegio_id = $this->Session->read("mi_colegio");
		$tipo = $this->Session->read("tipo");


		$usuarios = $this->User->find('all', array(
			'conditions' => array(
				'User.tipo' => $tipo,
				'User.colegio_id' => $colegio_id
			),
			'order' => array(
				'User.activo' => 'desc',
				'User.a_paterno' => 'asc',
				'User.a_materno' => 'asc',
				'User.nombre' => 'asc'
			),
			'fields' => array(
				'User.nombre', 'User.a_paterno', 'User.a_materno',
				'User.identificador', 'User.username', 'User.correo',
				'User.activo', 'User.imagene_id', 'Imagene.ruta', 'Imagene.nombre'
			),
			'recursive' => 0
		));

	    foreach ($usuarios as $key => $usuario)
	    {
	    	$id_encriptada = $this->Colegio->encriptacion($usuario['User']['id']);
	    	$usuarios[$key]['User']['encriptada'] = $id_encriptada;
	    }

	    $this->set("usuarios", $usuarios);
	    $this->set("tipo", substr($this->Session->read("una_pag_antes"), 1));
	}


//-------------------------------------------------------------------------


	public function resultados($fila = null, $agregados = null, $actualizados = null)
    {
	    $this->set("fila", $fila);
	    $this->set("agregados", $agregados);
	    $this->set("actualizados", $actualizados);
    }


//-------------------------------------------------------------------------


	public function descargar_excel($nombre = null)
    {
    	if (!in_array($nombre, array("plantillaUsuarios", "plantillaFamilias")))
    		$this->redirect("/users/subir_excel");

        $filename = $nombre.".xls";
        $name = explode('.',$filename);
        $this->viewClass = 'Media';

        $params = array(
            'id'        => $filename,
            'name'      => $name[0],
            'download'  => 1,
            'extension' => $name[1],
            'path'      => APP . 'plantillas' . DS
        );

        $this->set($params);
    }


//-------------------------------------------------------------------------


    public function subir_excel()
	{
		if (!empty($this->request->data))
		{
			$data = $this->request->data['archivo'];

        	$name = explode('.',$data['name']);

        	if ($name[1] != "xls") 
        	{
        		$this->Session->setFlash('Solo archivos "xls".');
        		$this->redirect("/users/subir_excel");
        	}

        	if (substr($name[0], 0, 17) != "plantillaUsuarios") 
        	{
        		$this->Session->setFlash('Elija el mismo archivo descargado.');
        		$this->redirect("/users/subir_excel");
        	}

			require_once 'php/reader.php';
			$arch_excel = new Spreadsheet_Excel_Reader();
			$arch_excel->setOutputEncoding('iso-8859-1');
			$arch_excel->read($data['tmp_name']);
			error_reporting(E_ALL ^ E_NOTICE);

			$usuariosAgregados = 0;
			$usuariosActualizados = 0;

			//Por la cantidad de filas que tenga el archivo
			//La fila 1 son los títulos, por lo que la info empieza en la 2
			for ($fila = 2; $fila <= $arch_excel->sheets[0]['numRows']; $fila++)
			{
				@$celdas = array_map("trim", $arch_excel->sheets[0]['cells'][$fila]);

				//Siempre debe estar el identificador
				if (!empty($celdas[1]))
				{
					$usuario = $this->User->find('first', array(
						'conditions' => array(
							'User.identificador' => $celdas[1],
							'User.colegio_id' => $this->Session->read("mi_colegio")
						),
						'fields' => array(
							'User.id', 'User.identificador', 'User.nombre',
							'User.a_paterno', 'User.a_materno', 'User.password', 'User.tipo'
						)
					));

					//Significa que no existe y se dara de alta
					if (empty($usuario['User']['id']))
					{
						//Si el "tipo" esta bien escrito y tiene los campos obligatorios llenos
						if (!empty($celdas[2]) &&
							!empty($celdas[3]) &&
							!empty($celdas[4]) &&
							!empty($celdas[5]) &&
							!empty($celdas[6]) &&
							in_array($celdas[6], array("Alumno", "Padre", "Madre", "Maestro")))
						{
							$agregarFila = $this->agregarFila($celdas, "nuevo");
							if ($agregarFila == 1)
								$usuariosAgregados++;
						}
					}
					else
					{	//Significa que ya existe y se actualizara
						$agregarFila = $this->agregarFila($celdas, $usuario['User']);
						if ($agregarFila == 1)
							$usuariosActualizados++;
					}
				}
			}

			$fila = $fila - 2;
			$this->redirect("/users/resultados/$fila/$usuariosAgregados/$usuariosActualizados");
		}
	}


	function agregarFila($celdas, $usuario)
    {
		$this->loadModel('Grado');
		$this->loadModel('Colegio');

		$imagenObligatoria = false;
		$correoYcelularObligatorio = false;
		$editado = false;

		if ($usuario != "nuevo")
		{
			$datos_usuario['User'] = $usuario;
			$datos_usuario['User']['id'] = $this->Colegio->encriptacion($usuario['id']);
			$editado = true;
			$tipo = "nada";
			$datos_usuario['Img']['usuario_password'] = $usuario['password'];
		}

		if (!empty($celdas[1]))
			$datos_usuario['User']['identificador'] = $celdas[1];

		if (!empty($celdas[2]))
			$datos_usuario['User']['nombre'] = $celdas[2];

		if (!empty($celdas[3]))
			$datos_usuario['User']['a_paterno'] = $celdas[3];

		if (!empty($celdas[4]))
			$datos_usuario['User']['a_materno'] = $celdas[4];

		if (!empty($celdas[5]))
			$datos_usuario['User']['password'] = $celdas[5];

		if (!empty($celdas[6]))
			$datos_usuario['User']['tipo'] = $tipo = $celdas[6];

		if (!empty($celdas[7]))
			$datos_usuario['User']['correo'] = $celdas[7];

		if (!empty($celdas[8]))
			$datos_usuario['User']['celular'] = $celdas[8];

		if (!empty($celdas[9]))
		{
			$familia = $this->User->find('first', array(
				'conditions' => array(
					'User.colegio_id' => $this->Session->read("mi_colegio"),
					'Familia.identificador' => $celdas[9]
				),
				'fields' => array('Familia.id'),
				'recursive' => 0
			));

			//Si existe la familia
			if (!empty($familia['Familia']['id']))
			{
				$datos_usuario['Familia'] = $this->Colegio->encriptacion($familia['Familia']['id']);
			}
		}

		if ($celdas[6] == "Alumno" || @$usuario["tipo"] == "Alumno")
		{
			if (!empty($celdas[10]))
			{
				$grado = $this->Grado->find('first', array(
					'conditions' => array(
						'Nivele.colegio_id' => $this->Session->read("mi_colegio"),
						'Grado.identificador' => $celdas[10]
					),
					'fields' => array('Grado.id'),
					'recursive' => 0
				));

				//Si existe el grado
				if (!empty($grado['Grado']['id']))
				{
					$datos_usuario['User']['grado_id'] = $this->Colegio->encriptacion($grado['Grado']['id']);
				}
			}
		}

		$agregado = $this->Usuarios->agregarBDD($datos_usuario, $imagenObligatoria, $correoYcelularObligatorio, $tipo, $editado);		
		
		return $agregado;
    }


//-------------------------------------------------------------------------

}
