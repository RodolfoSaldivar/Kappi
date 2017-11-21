<?php
App::uses('AppController', 'Controller');
/**
 * Familias Controller
 *
 * @property Familia $Familia
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class FamiliasController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'Familias');


//-------------------------------------------------------------------------


	public function isAuthorized($user)
	{
        //Acceso para Admins solamente
        if (in_array($this->action, array('index', 'agregar', 'editar', 'mostrar_todos', 'filtrar_familias', 'agregar_miembro', 'datos', 'resultados', 'subir_excel')))
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
		$this->loadModel("User");

		$colegio_id = $this->Session->read("mi_colegio");

		@$familias = $this->Familia->query(
			"SELECT DISTINCT TOP 75 Familia.*
			FROM kappi.users as usuario, kappi.familias as Familia
			WHERE usuario.colegio_id = $colegio_id
				AND usuario.activo = 1
				AND	usuario.familia_id = Familia.id
			ORDER BY Familia.nombre"
		);

		foreach ($familias as $keyFam => $familia)
		{
			$usuarios = $this->User->find('all', array(
				'conditions' => array('User.familia_id' => $familia[0]['id']),
				'order' => array(
					'User.a_paterno' => 'asc',
					'User.a_materno' => 'asc',
					'User.nombre' => 'asc'
				),
				'fields' => array(
					'User.a_paterno',
					'User.a_materno',
					'User.nombre'
				)
			));

			foreach ($usuarios as $keyUsu => $usuario)
			{
				$familias[$keyFam]['User'][$keyUsu] = $usuario['User'];
			}

	    	$id_encriptada = $this->Colegio->encriptacion($familia[0]['id']);
	    	$familias[$keyFam][0]['encriptada'] = $id_encriptada;
		}

		$this->Session->write("FamiliasCompletas", $familias);

	    $this->set("familias", $familias);
	}


//-------------------------------------------------------------------------


	public function mostrar_todos()
	{
		$this->loadModel("Colegio");
		$this->loadModel("User");

		$colegio_id = $this->Session->read("mi_colegio");

		@$familias = $this->Familia->query("
			SELECT DISTINCT Familia.*
			FROM kappi.users as usuario, kappi.familias as Familia
			WHERE usuario.colegio_id = $colegio_id
				AND usuario.activo = 1
				AND	usuario.familia_id = Familia.id
			ORDER BY Familia.nombre
		");

		foreach ($familias as $keyFam => $familia)
		{
			$usuarios = $this->User->find('all', array(
				'recursive' => 0,
				'conditions' => array('User.familia_id' => $familia[0]['id']),
				'order' => array(
					'User.a_paterno' => 'asc',
					'User.a_materno' => 'asc',
					'User.nombre' => 'asc'
				),
				'fields' => array(
					'User.a_paterno',
					'User.a_materno',
					'User.nombre'
				)
			));

			foreach ($usuarios as $keyUsu => $usuario)
			{
				$familias[$keyFam]['User'][$keyUsu] = $usuario['User'];
			}

	    	$id_encriptada = $this->Colegio->encriptacion($familia[0]['id']);
	    	$familias[$keyFam][0]['encriptada'] = $id_encriptada;
		}

		$this->Session->write("FamiliasCompletas", $familias);

	    $this->set("familias", $familias);
	}
	

//-------------------------------------------------------------------------
	

	public function filtrar_familias()
	{
		$this->loadModel("Colegio");
		$this->loadModel("User");

		$this->layout='ajax';

		$data = $this->request->data;
		
		$todoVacio = false;
		if (empty($data['miembro']) &&
			empty($data['nombre']) &&
			empty($data['identificador']))
		{
			$todoVacio = true;
		}
		else
		{
			$query = " AND usuario.familia_id = Familia.id ";

			if (!empty($data['nombre'])) 
				$query = $query." AND CHARINDEX('".$data["nombre"]."', Familia.nombre) > 0";
			if (!empty($data["identificador"])) 
				$query = $query." AND CHARINDEX('".$data["identificador"]."', Familia.identificador) > 0";
			if (!empty($data['miembro']))
				$query = $query." AND CHARINDEX('".$data["miembro"]."', CONCAT(usuario.a_paterno, ' ', usuario.a_materno, ', ', usuario.nombre)) > 0";

			$familias = $this->Familia->query("
				SELECT DISTINCT TOP 75 Familia.*
				FROM kappi.users as usuario, kappi.familias as Familia
				WHERE usuario.colegio_id = ".$this->Session->read("mi_colegio")."
					AND usuario.activo = 1 ".$query."
				ORDER BY Familia.nombre
			");

			foreach ($familias as $keyFam => $familia)
			{
				$usuarios = $this->User->find('all', array(
					'recursive' => 0,
					'conditions' => array('User.familia_id' => $familia[0]['id']),
					'order' => array(
						'User.a_paterno' => 'asc',
						'User.a_materno' => 'asc',
						'User.nombre' => 'asc'
					),
					'fields' => array(
						'User.a_paterno',
						'User.a_materno',
						'User.nombre'
					)
				));

				foreach ($usuarios as $keyUsu => $usuario)
				{
					$familias[$keyFam]['User'][$keyUsu] = $usuario['User'];
				}

		    	$id_encriptada = $this->Colegio->encriptacion($familia[0]['id']);
		    	$familias[$keyFam][0]['encriptada'] = $id_encriptada;
			}
		}

		if ($todoVacio)
			$this->set("familias", $this->Session->read("FamiliasCompletas"));
		else
			$this->set("familias", $familias);
	}
	

//-------------------------------------------------------------------------
	

	public function agregar()
	{
		$this->loadModel("User");
		$this->loadModel("Colegio");

		$colegio_id = $this->Session->read("mi_colegio");

		if (!empty($this->request->data))
		{
			$agregado = $this->Familias->agregarBDD($this->request->data);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	if ($this->request->data['Miembros'][0] == "")
	        	{
	        		$this->Session->setFlash("Escoga miembros.");
	        	}
	        	else
	        	{
		        	//Regresa a la pantalla de colegios
		        	$this->redirect("/familias");
		        }
	        }
		}

		$usuarios = $this->User->query("
			SELECT usuario.id, CONCAT(usuario.a_paterno, ' ', usuario.a_materno, ', ', usuario.nombre) AS nombre_completo
			FROM kappi.users as usuario, kappi.colegios as Colegio
			WHERE usuario.colegio_id = $colegio_id
				AND usuario.activo = 1
				AND Colegio.activo = 1
				AND usuario.familia_id is NULL
				AND usuario.tipo IN (N'Alumno', N'Madre', N'Padre')
			ORDER BY usuario.a_paterno, usuario.a_materno, usuario.nombre
		");

	    $usuariosMandar = array();
	    foreach ($usuarios as $key => $usuario)
	    {
	    	$id = $this->Colegio->encriptacion($usuario[0]['id']);
	    	$usuariosMandar[$id] = $usuario[0]['nombre_completo'];
	    }

	    $this->Session->write("UsuariosCompletos", $usuariosMandar);
	    $this->set("usuarios", $usuariosMandar);
	    $this->set("contador", 0);
	}
	

//-------------------------------------------------------------------------
	

	public function agregar_miembro()
	{
		$this->layout='ajax';

		$data = $this->request->data;

		$usuariosCompletos = $this->Session->read("UsuariosCompletos");
		@$id = $data['usuario'];

		unset($usuariosCompletos[$id]);

		$this->Session->write("UsuariosCompletos", $usuariosCompletos);
		$this->set("usuarios", $usuariosCompletos);
	    $this->set("contador", $data['contador']);
	}
	

//-------------------------------------------------------------------------
	

	public function editar($id = null)
	{
		$this->loadModel("User");
		$this->loadModel("Colegio");

		$colegio_id = $this->Session->read("mi_colegio");

		$id = $this->Colegio->desencriptacion($id);

		if (!$this->Familia->exists($id, "familias"))
		{
			$this->Session->write("mensaje_autorizacion", "Esa familia no está registrada.");
			$this->redirect("/no_autorizado");
		}

		//Familia a editar
		$familia = $this->Familia->find('first', array(
	        'conditions' => array('Familia.id' => $id),
	        'fields' => array(
	        	'id', 'nombre', 'identificador'
	        )
	    ));
	    $familia['Familia']['id'] = $this->Colegio->encriptacion($familia['Familia']['id']);

	    $miembros = $this->User->find("all", array(
	    	'conditions' => array('familia_id' => $id),
	    	'fields' => array(
	    		'id', 'nombre', 'a_paterno', 'a_materno', 'tipo', 'activo' ,'imagene_id'
	    	)
	    ));

	    foreach ($miembros as $key => $miembro)
	    {
	    	$familia['User'][$key] = $miembro['User'];
	    }

		if (!empty($this->request->data))
		{
			if (!empty($this->request->data['Miembros']))
        	{
        		//Se borra la familia a la que pertenece
			    foreach ($familia['User'] as $key => $usuario)
			    {
	        		$datos_usuario['User']['id'] = $usuario['id'];
	        		$datos_usuario['User']['familia_id'] = "";
	        		$this->User->query("
	        			UPDATE kappi.users
	        			SET familia_id = NULL
	        			WHERE id = ".$usuario['id']."
	        		");
			    }
        	}
        	
			$agregado = $this->Familias->agregarBDD($this->request->data);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	if (empty($this->request->data['Miembros']))
	        	{
	        		$this->Session->setFlash("Escoga miembros.");
	        	}
	        	else
	        	{
	        		//Regresa a la pantalla de colegios
		        	$this->redirect("/familias");
		        }
	        }
		}

		//Cantidad de miembros en la familia
	    $miembros = $this->User->find('count', array(
	        'conditions' => array('User.familia_id' => $id)
	    ));

	    //Todos los usuarios
		$usuarios = $this->User->query(
			"SELECT usuario.id, CONCAT(usuario.a_paterno, ' ', usuario.a_materno, ', ', usuario.nombre) AS nombre_completo
			FROM kappi.users as usuario, kappi.colegios as Colegio
			WHERE usuario.colegio_id = $colegio_id
				AND usuario.activo = 1
				AND Colegio.activo = 1
				AND usuario.familia_id is NULL
				AND usuario.tipo IN (N'Alumno', N'Madre', N'Padre')
			ORDER BY usuario.a_paterno, usuario.a_materno, usuario.nombre"
		);

		//Se encripta el id
	    $usuariosMandar = array();
	    foreach ($usuarios as $key => $usuario)
	    {
	    	$id = $this->Colegio->encriptacion($usuario[0]['id']);
	    	$usuariosMandar[$id] = $usuario[0]['nombre_completo'];
	    }

	    foreach ($familia['User'] as $key => $usuario)
	    {
	    	$id_encriptada = $this->Colegio->encriptacion($usuario['id']);
	    	$familia['User'][$key]['id'] = $id_encriptada;
	    	unset($usuariosMandar[$id_encriptada]);
	    }

	    $this->Session->write("UsuariosCompletos", $usuariosMandar);
	    $this->set("familia", $familia);
	    $this->set("usuarios", $usuariosMandar);
	    $this->set("contador", $miembros);
	}
	

//-------------------------------------------------------------------------
	

	public function datos($id = null)
	{
		$this->loadModel("Colegio");
		$this->loadModel("Imagene");
		$this->loadModel("User");

		$id = $this->Colegio->desencriptacion($id);

		if (!$this->Familia->exists($id, "familias"))
		{
			$this->Session->write("mensaje_autorizacion", "Esa familia no está registrada.");
			$this->redirect("/no_autorizado");
		}

		//Familia a ver
		$familia = $this->Familia->find('first', array(
	        'conditions' => array('Familia.id' => $id),
	        'fields' => array(
	        	'nombre', 'identificador'
	        )
	    ));

	    $miembros = $this->User->find("all", array(
	    	'conditions' => array('familia_id' => $id),
	    	'fields' => array(
	    		'nombre', 'a_paterno', 'a_materno', 'tipo', 'activo' ,'imagene_id'
	    	)
	    ));

	    foreach ($miembros as $key => $miembro)
	    {
	    	$familia['User'][$key] = $miembro['User'];
	    }

		foreach ($familia['User'] as $key => $usuario)
		{
			$imagen = $this->Imagene->find('first', array(
		        'conditions' => array('Imagene.id' => $usuario['imagene_id']),
		        'fields' => array('nombre', 'ruta')
		    ));

		    @$familia['User'][$key]['Imagene'] = $imagen['Imagene'];
		}
		
	    $this->set("familia", $familia);
	}


//-------------------------------------------------------------------------


	public function resultados($fila = null, $agregadas = null, $actualizadas = null)
    {
	    $this->set("fila", $fila);
	    $this->set("agregadas", $agregadas);
	    $this->set("actualizadas", $actualizadas);
    }


//-------------------------------------------------------------------------


	public function subir_excel()
    {
		if (!empty($this->request->data))
		{
			$this->loadModel("User");

			$data = $this->request->data['archivo'];

        	$name = explode('.',$data['name']);

        	if ($name[1] != "xls") 
        	{
        		$this->Session->setFlash('Solo archivos "xls".');
        		$this->redirect("/familias/subir_excel");
        	}

        	if (substr($name[0], 0, 17) != "plantillaFamilias") 
        	{
        		$this->Session->setFlash('Elija el mismo archivo descargado.');
        		$this->redirect("/familias/subir_excel");
        	}

			require_once 'php/reader.php';
			$arch_excel = new Spreadsheet_Excel_Reader();
			$arch_excel->setOutputEncoding('iso-8859-1');
			$arch_excel->read($data['tmp_name']);
			error_reporting(E_ALL ^ E_NOTICE);

			$familiasAgregadas = 0;
			$familiasActualizadas = 0;

			//Por la cantidad de filas que tenga el archivo
			//La fila 1 son los títulos, por lo que la info empieza en la 2
			for ($fila = 2; $fila <= $arch_excel->sheets[0]['numRows']; $fila++)
			{
				@$celdas = array_map("trim", $arch_excel->sheets[0]['cells'][$fila]);

				//Siempre debe estar el identificador
				if (!empty($celdas[1]))
				{
					$familia = $this->User->find('first', array(
						'conditions' => array(
							'Familia.identificador' => $celdas[1],
							'User.colegio_id' => $this->Session->read("mi_colegio")
						),
						'fields' => array(
							'Familia.id',
							'Familia.nombre'
						),
						'recursive' => 0
					));

					//Significa que no existe y se dara de alta
					if (empty($familia['Familia']['id']))
					{
						//Si tiene el nombre lleno y almenos un miembro
						if (!empty($celdas[2]) &&
							!empty($celdas[3]))
						{
							$agregado = true;
							$agregarFila = $this->agregarFila($celdas, "nueva", $agregado);
							if ($agregarFila == 1)
								$familiasAgregadas++;
						}
					}
					else
					{	//Significa que ya existe y se actualizara
						$agregado = false;
						$agregarFila = $this->agregarFila($celdas, $familia['Familia'], $agregado);
						if ($agregarFila == 1)
							$familiasActualizadas++;
					}
				}
			}

			$fila = $fila - 2;
			$this->redirect("/familias/resultados/$fila/$familiasAgregadas/$familiasActualizadas");
		}
    }


	function agregarFila($celdas, $familia, $agregado)
    {
		$this->loadModel('User');
		$this->loadModel('Colegio');

    	$valido = 1;

		if ($familia != "nueva")
		{
			$datos_familia['Familia'] = $familia;
			$datos_familia['Familia']['id'] = $this->Colegio->encriptacion($familia['id']);
		}

		if (!empty($celdas[1]))
			$datos_familia['Familia']['identificador'] = $celdas[1];
		if (!empty($celdas[2]))
			$datos_familia['Familia']['nombre'] = $celdas[2];

		//De la celda 3 a la 8
		for ($miembro = 3; $miembro <= 8; $miembro++)
		{
			if (!empty($celdas[$miembro]))
			{
				$usuario = $this->User->find('first', array(
					'conditions' => array(
						'User.colegio_id' => $this->Session->read("mi_colegio"),
						'User.identificador' => $celdas[$miembro]
					),
					'fields' => array('User.id', 'User.familia_id')
				));

				//Si existe el usuario
				if (!empty($usuario['User']['id']))
				{
					//Si no esta en otra familia
					if (empty($usuario['User']['familia_id']))
						$datos_familia['Miembros'][$miembro] = $this->Colegio->encriptacion($usuario['User']['id']);
				}

				//Cuando el miembro requerido no es valido
				if ($agregado && $miembro == 3 && empty($usuario['User']['id']))
					$valido = false;
				//Cuando el miembro requerido ya esta en otra familia
				if ($agregado && $miembro == 3 && !empty($usuario['User']['familia_id']))
					$valido = false;
			}
		}

		if ($valido)
		{
			$agregado = $this->Familias->agregarBDD($datos_familia);

			return $agregado;
		}
		else
			return 0;
    }


//-------------------------------------------------------------------------


}