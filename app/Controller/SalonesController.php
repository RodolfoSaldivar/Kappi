<?php
App::uses('AppController', 'Controller');
/**
 * Salones Controller
 *
 * @property Salone $Salone
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class SalonesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'Salones');


//-------------------------------------------------------------------------


    public function isAuthorized($user)
    {
        if (isset($user['tipo']) && in_array($user['tipo'], array("Administrador")))
		{
			return true;
		}

	    return parent::isAuthorized($user);
    }


//-------------------------------------------------------------------------


	public function index($ciclo_id = null)
	{
		$this->loadModel("Colegio");
		$this->loadModel("Ciclo");
		$this->loadModel("Nivele");

		$ciclo_id = $this->Colegio->desencriptacion($ciclo_id);

		if (!$this->Ciclo->exists($ciclo_id, "ciclos"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese ciclo no está registrado.");
			$this->redirect("/no_autorizado");
		}

		//Obtiene el ciclo
		$ciclo = $this->Ciclo->find('first', array(
			'conditions' => array('Ciclo.id' => $ciclo_id),
			'fields' => array('id', 'nombre', 'fecha_inicio', 'fecha_fin')
		));

		$id_encriptada = $this->Colegio->encriptacion($ciclo['Ciclo']['id']);
    	$ciclo['Ciclo']['encriptada'] = $id_encriptada;

		//Obtiene los salones del ciclo
		$salones = $this->Salone->find('all', array(
			'conditions' => array('Salone.ciclo_id' => $ciclo_id),
			'order' => array('Salone.created' => 'asc'),
			'recursive' => 0,
			'fields' => array(
				'Grado.nombre',
				'Grado.nivele_id',
				'User.nombre',
				'User.a_paterno',
				'User.a_materno',
				'Salone.id',
				'Salone.nombre'
			)

		));

		foreach ($salones as $key => $salon)
		{
	    	$nivel = $this->Nivele->find('first', array(
				'conditions' => array('Nivele.id' => $salon["Grado"]["nivele_id"]),
				'fields' => array('nombre')
			));

			$id_encriptada = $this->Colegio->encriptacion($salon['Salone']['id']);
	    	$salones[$key]["Salone"]['encriptada'] = $id_encriptada;
	    	$salones[$key]["Nivele"] = $nivel["Nivele"];
	    	unset($salones[$key]["Ciclo"]);
		}

		$this->Session->write("SalonesCompletos", $salones);

		$this->set('ciclo', $ciclo);
		$this->set('salones', $salones);
	}
	

//-------------------------------------------------------------------------
	

	public function filtrar_salones()
	{
		$this->layout='ajax';

		$data = $this->request->data;
		$data['salones'] = unserialize(base64_decode($data['salones']));
		
		$todoVacio = false;
		if (empty($data['nivel']) &&
			empty($data['grado']) &&
			empty($data['salon']) &&
			empty($data['maestro']))
		{
			$todoVacio = true;
		}


		if (!empty($data['nivel']))
		{
			foreach ($data['salones'] as $key => $salon)
			{
				if (stripos($salon['Nivele']['nombre'], $data['nivel']) === false)
				{
					unset($data['salones'][$key]);
				}
			}	
		}

		if (!empty($data['grado']))
		{
			foreach ($data['salones'] as $key => $salon)
			{
				if (stripos($salon['Grado']['nombre'], $data['grado']) === false)
				{
					unset($data['salones'][$key]);
				}
			}	
		}

		if (!empty($data['salon']))
		{
			foreach ($data['salones'] as $key => $salon)
			{
				if (stripos($salon['Salone']['nombre'], $data['salon']) === false)
				{
					unset($data['salones'][$key]);
				}
			}	
		}

		if (!empty($data['maestro']))
		{
			foreach ($data['salones'] as $key => $salon)
			{
				$nombre_completo = $salon['User']['a_paterno']." ".$salon['User']['a_materno'].", ".$salon['User']['nombre'];

				if (stripos($nombre_completo, $data['maestro']) === false)
				{
					unset($data['salones'][$key]);
				}
			}	
		}

		if ($todoVacio)
			$this->set("salones", $this->Session->read("SalonesCompletos"));
		else
			$this->set("salones", $data['salones']);
	}
	

//-------------------------------------------------------------------------


	public function segundo_select()
	{
		$this->layout = 'ajax';

		$this->loadModel("Colegio");
		$this->loadModel("Grado");

		$data = $this->request->data;

		$id = $this->Colegio->desencriptacion($data["grado"]);

		$grado = $this->Grado->find('first', array(
			'conditions' => array('Grado.id' => $id),
			'recursive' => 0,
			'fields' => array(
				'Grado.id',
				'Grado.nombre',
				'Grado.grado_id',
				'Nivele.nombre'
			)
		));
		$grado["Grado"]["encriptada"] = $this->Colegio->encriptacion($grado["Grado"]["id"]);

		if (!empty($grado["Grado"]["grado_id"]))
		{
			$antes = $this->Grado->find('first', array(
				'conditions' => array('Grado.id' => $grado["Grado"]["grado_id"]),
				'fields' => array('id', 'nombre')
			));
			$antes["Grado"]["encriptada"] = $this->Colegio->encriptacion($antes["Grado"]["id"]);

			$grado["Antes"] = $antes["Grado"];
		}
		
		$this->set('grado', $grado);
	}
	

//-------------------------------------------------------------------------


	public function mostrar_alumnos()
	{
		$this->layout = 'ajax';

		$this->loadModel("Colegio");
		$this->loadModel("User");
		$this->loadModel("Ciclo");
		$this->loadModel("Imagene");
		$this->loadModel("Grado");

		$data = $this->request->data;
		$colegio_id = $this->Session->read("mi_colegio");

		$alumnos_temporal;
		$alumnos_mandar;
		$alumnos_ya_inscritos;
		$salones;

		if ($data["grado"] == "nuevos")
		{
			$grado_id = $this->Colegio->desencriptacion($data["padre"]);
			$ciclo_id = $this->Colegio->desencriptacion($data["ciclo"]);

			$nivele_id = $this->Grado->find('first', array(
				'conditions' => array('Grado.id' => $grado_id),
				'fields' => array('Grado.nivele_id')
			));
			$nivele_id = $nivele_id["Grado"]["nivele_id"];

			$alumnos_temporal = $this->User->query(
				"SELECT DISTINCT(usuario.id), usuario.nombre, usuario.a_paterno, usuario.a_materno, usuario.imagene_id
				FROM kappi.users as usuario, kappi.grados as Grado
				WHERE usuario.tipo = N'Alumno'
					AND usuario.activo = 1
					AND usuario.colegio_id = $colegio_id
					AND usuario.grado_id = $grado_id
					AND usuario.id NOT IN
					(	SELECT usu.id
						FROM kappi.users as usu, kappi.alumnos_inscritos as ai, kappi.grados as gra, kappi.salones as sal
						WHERE usu.tipo = N'Alumno'
							AND usu.activo = 1
							AND usu.colegio_id = $colegio_id
							AND usu.id = ai.user_id
							AND ai.salone_id = sal.id
							AND sal.grado_id = gra.id
							AND gra.nivele_id = $nivele_id
					)
				ORDER BY a_paterno, a_materno, nombre"
			);

			if (!$alumnos_temporal)
			{
				$alumnos_temporal = "nuevos";
			}
		}
		else
		{
			$ciclo_id = $this->Colegio->desencriptacion($data["ciclo"]);

			$ciclo_actual = $this->Ciclo->find('first', array(
				'conditions' => array('Ciclo.id' => $ciclo_id),
				'fields' => array('ciclo_id')
			));

			$ciclo_pasado = $ciclo_actual["Ciclo"]["ciclo_id"];
			
			//Cuando NO es el primer ciclo y SI hay ciclos pasados registrados
			if (!empty($ciclo_pasado))
			{
				$grado_id = $this->Colegio->desencriptacion($data["grado"]);

				//Viene de la vista de "A"gregar
				if ($data["accion"] == "a")
				{
					$alumnos_temporal = $this->User->query(
						"SELECT Salone.id as sal_id, Salone.nombre as sal_nombre, usuario.id, usuario.nombre, usuario.a_paterno, usuario.a_materno, usuario.imagene_id
						FROM kappi.users as usuario, kappi.alumnos_inscritos as ai, kappi.salones as Salone
						WHERE usuario.tipo = N'Alumno'
							AND usuario.activo = 1
						    AND usuario.colegio_id = $colegio_id
						    AND usuario.id = ai.user_id
						    AND ai.salone_id = Salone.id
						    AND Salone.grado_id = $grado_id
						    AND Salone.ciclo_id = $ciclo_pasado
						    AND usuario.id NOT IN
						    (	SELECT ai2.user_id
								FROM kappi.alumnos_inscritos as ai2, kappi.salones as sal
								WHERE ai2.salone_id = sal.id
									AND sal.ciclo_id = $ciclo_id
						    )
					    ORDER BY usuario.a_paterno, usuario.a_materno, usuario.nombre"
					);
				}

				//Viene de la vista de "E"ditar
				if ($data["accion"] == "e")
				{
					$salone_id = $this->Colegio->desencriptacion($data["salon"]);

					$alumnos_temporal = $this->User->query(
						"SELECT Salone.id as sal_id, Salone.nombre as sal_nombre, usuario.id, usuario.nombre, usuario.a_paterno, usuario.a_materno, usuario.imagene_id
						FROM kappi.users as usuario, kappi.alumnos_inscritos as ai, kappi.salones as Salone
						WHERE usuario.tipo = N'Alumno'
							AND usuario.activo = 1
						    AND usuario.colegio_id = $colegio_id
						    AND usuario.id = ai.user_id
						    AND ai.salone_id = Salone.id
						    AND Salone.grado_id = $grado_id
						    AND Salone.ciclo_id = $ciclo_pasado
						    AND usuario.id NOT IN
						    (	SELECT ai2.user_id
								FROM kappi.alumnos_inscritos as ai2, kappi.salones as sal
								WHERE ai2.salone_id = sal.id
									AND sal.ciclo_id = $ciclo_id
									AND ai2.user_id NOT IN
									(	SELECT ai3.user_id
										FROM kappi.alumnos_inscritos as ai3
										WHERE ai3.salone_id = $salone_id
									)
						    )
					    ORDER BY usuario.a_paterno, usuario.a_materno, usuario.nombre"
					);
				}

				if (!$alumnos_temporal)
				{
					$alumnos_temporal = "ciclos";
				}

				if ($data["modo"] == 1)
				{
					$salones = $this->Salone->find('all', array(
						'conditions' => array(
							'Salone.ciclo_id' => $ciclo_pasado,
							'Salone.grado_id' => $grado_id
						),
						'fields' => array('Salone.id', 'Salone.nombre')
					));

					foreach ($salones as $key => $salon)
					{
						$id_encriptada = $this->Colegio->encriptacion($salon["Salone"]["id"]);
						$salones[$key]["Salone"]["id"] = $id_encriptada;
					}
					$this->set('salones', $salones);
				}
			}
			else
				$alumnos_temporal = "ciclos";
		}

		if ($alumnos_temporal != "nuevos" && $alumnos_temporal != "ciclos")
		{
			foreach ($alumnos_temporal as $key => $alumno)
			{
				$id_encriptada = $this->Colegio->encriptacion($alumno[0]["id"]);
				$alumnos_temporal[$key][0]["id"] = $id_encriptada;

				if (!empty($alumno[0]["sal_id"]))
				{
					$sid = $this->Colegio->encriptacion($alumno[0]["sal_id"]);
					$alumnos_temporal[$key][0]["sal_id"] = $sid;
				}

				if (!empty($alumno[0]["imagene_id"]))
				{
					$imagen = $this->Imagene->find('first', array(
						'conditions' => array('Imagene.id' => $alumno[0]["imagene_id"]),
						'fields' => array('nombre', 'ruta')
					));
					@$alumnos_temporal[$key]["Imagene"] = $imagen["Imagene"];
				}

				$alumnos_mandar[$id_encriptada] = $alumnos_temporal[$key];
			}

			if ($data["modo"] == 2)
			{
				$this->Session->write("AlumnosParaInscribir", $alumnos_mandar);
			}
		}
		else
			$alumnos_mandar = $alumnos_temporal;
			
		@$this->set('alumnos', $alumnos_mandar);
	}
	

//-------------------------------------------------------------------------


	public function buscar_por_nombre()
	{
		$this->layout='ajax';

		$data = $this->request->data;
		$data['alumnos'] = $this->Session->read("AlumnosParaInscribir");

		if (!empty($data['nombre']))
		{
			foreach ($data['alumnos'] as $key => $alumno)
			{
				$nombre_completo = $alumno['User']['a_paterno']." ".$alumno['User']['a_materno'].", ".$alumno['User']['nombre'];

				if (stripos($nombre_completo, $data['nombre']) === false)
				{
					unset($data['alumnos'][$key]);
				}
			}	
		}

		if (empty($data['nombre']))
			$this->set("alumnos", $this->Session->read("AlumnosParaInscribir"));
		else
			$this->set("alumnos", $data['alumnos']);
	}
	

//-------------------------------------------------------------------------


	public function inscribir_alumnos()
	{
		$this->layout = 'ajax';

		$this->loadModel("Colegio");
		$this->loadModel("User");

		$data = $this->request->data;

		$alumnos;

		//Si lo inscribio es que esta "D"entro
		if ($data["accion"] == "d")
		{
			$user_id = $this->Colegio->desencriptacion($data["alumno"]);

			//Obtiene el usuario
			$usuario = $this->User->find('first', array(
				'conditions' => array('User.id' => $user_id),
				'recursive' => 0,
				'fields' => array(
					'User.nombre',
					'User.a_paterno',
					'User.a_materno',
					'User.imagene_id',
					'Imagene.ruta',
					'Imagene.nombre'
				)
			));

			//Id encriptada
			$usuario["User"]["id"] = $data["alumno"];

			//Checa si existe la variable
			$lleno = $this->Session->check("AlumnosInscritos");
			if ($lleno)
			{
				$alumnos = $this->Session->read("AlumnosInscritos");
			}

			$alumnos[$data["alumno"]] = $usuario;
			$this->Session->write("AlumnosInscritos", $alumnos);
		}

		//Si lo quita es que esta "F"uera
		if ($data["accion"] == "f")
		{
			$alumnos = $this->Session->read("AlumnosInscritos");
			unset($alumnos[$data["alumno"]]);
			$this->Session->write("AlumnosInscritos", $alumnos);
		}
			
		
		$this->set('inscritos', $this->Session->read("AlumnosInscritos"));
	}
	

//-------------------------------------------------------------------------


	public function agregar($ciclo_id = null)
	{
		$this->loadModel("Colegio");
		$this->loadModel("Ciclo");
		$this->loadModel("Grado");
		$this->loadModel("Nivele");
		$this->loadModel("User");

		if (!empty($this->request->data))
		{
			$this->request->data["Inscritos"] = $this->Session->read("AlumnosInscritos");

			$agregado = $this->Salones->agregarBDD($this->request->data, "agregar");

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla del ciclo
	        	$this->redirect("/salones_index/".$ciclo_id);
	        }
		}

		//Borra la informacion de alumnos inscritos en otra session
		$this->Session->delete('AlumnosInscritos');		

		$this->request->data["Salone"]["ciclo_id"] = $ciclo_id;

		$ciclo_id = $this->Colegio->desencriptacion($ciclo_id);
		$colegio_id = $this->Session->read("mi_colegio");

		//Obtiene el ciclo
		$ciclo = $this->Ciclo->find('first', array(
			'conditions' => array('Ciclo.id' => $ciclo_id),
			'fields' => array('id', 'nombre', 'activo')
		));

		if (!$this->Ciclo->exists($ciclo_id, "ciclos"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese ciclo no está registrado.");
			$this->redirect("/no_autorizado");
		}
		if ($ciclo["Ciclo"]["activo"] == 0)
		{
			$this->Session->write("mensaje_autorizacion", "Ese ciclo no está activo.");
			$this->redirect("/no_autorizado");
		}

		//Obtiene los grados
		$nivelesYgrados = $this->Nivele->find('all', array(
			'conditions' => array('Nivele.colegio_id' => $colegio_id),
			'fields' => array(
				'Nivele.id',
				'Nivele.nombre'
			)
		));

		foreach ($nivelesYgrados as $keyN => $nivgra)
		{
			$grados = $this->Grado->find('all', array(
				'conditions' => array('nivele_id' => $nivgra['Nivele']['id']),
				'fields' => array('id', 'nombre')
			));

			foreach ($grados as $keyG => $grado)
			{
				$grados[$keyG]['Grado']['id'] = $this->Colegio->encriptacion($grado['Grado']['id']);

				$nivelesYgrados[$keyN]['Grado'][$keyG] = $grados[$keyG]['Grado'];
			}
		}

		//Obtiene los maestros
		$maestros = $this->User->query(
			"SELECT usua.id, usua.nombre, usua.a_paterno, usua.a_materno
			FROM kappi.users as usua
			WHERE usua.colegio_id = $colegio_id
				AND usua.activo = 1
			    AND usua.tipo = N'Maestro'
			    AND usua.id NOT IN 
			    (	SELECT us.id
					FROM kappi.users as us, kappi.salones as sal
			        WHERE us.colegio_id = $colegio_id
			            AND us.activo = 1
						AND us.tipo = N'Maestro'
			            AND sal.user_id = us.id
			            AND sal.ciclo_id = $ciclo_id
			    )
			ORDER BY usua.a_paterno, usua.a_materno, usua.nombre"
		);

		foreach ($maestros as $key => $maestro)
		{
			$id = $this->Colegio->encriptacion($maestro[0]['id']);
			$maestros[$key][0]['encriptada'] = $id;
		}

		$this->set('ciclo', $ciclo);
		$this->set('nivelesYgrados', $nivelesYgrados);
		$this->set('maestros', $maestros);
	}
	

//-------------------------------------------------------------------------


	public function editar($id = null)
	{
		$this->loadModel("Colegio");
		$this->loadModel("Ciclo");
		$this->loadModel("Grado");
		$this->loadModel("User");
		$this->loadModel("AlumnosInscrito");

		$id = $this->Colegio->desencriptacion($id);

		$salon = $this->Salone->find('first', array(
			'conditions' => array('Salone.id' => $id),
			'recursive' => 0,
			'fields' => array(
				'Salone.id',
				'Salone.nombre',
				'Salone.ciclo_id',
				'Salone.grado_id',
				'Salone.user_id',
				'Ciclo.nombre',
				'Grado.nombre',
				'User.id'
			)
		));
		$ciclo_id = $salon["Salone"]["ciclo_id"];

		//Obtiene el ciclo
		$ciclo = $this->Ciclo->find('first', array(
			'conditions' => array('Ciclo.id' => $ciclo_id),
			'fields' => array('id', 'nombre', 'activo')
		));

		if (!$this->Salone->exists($id, "salones"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese salón no está registrado.");
			$this->redirect("/no_autorizado");
		}
		if ($ciclo["Ciclo"]["activo"] == 0)
		{
			$this->Session->write("mensaje_autorizacion", "Ese ciclo no está activo.");
			$this->redirect("/no_autorizado");
		}

		//Obtiene los inscritos
		$inscritos = $this->AlumnosInscrito->find('all', array(
			'conditions' => array('AlumnosInscrito.salone_id' => $id),
			'order' => array(
				'User.a_paterno' => 'asc',
				'User.a_materno' => 'asc',
				'User.nombre' => 'asc'
			),
			'fields' => array(
				'AlumnosInscrito.id', 'User.id', 'User.nombre', 'User.a_paterno', 'User.a_materno', 'User.imagene_id'
			),
			'recursive' => 0
		));

		foreach ($inscritos as $key => $inscrito)
		{
			$id = $this->Colegio->encriptacion($inscrito['User']['id']);
			$inscritos[$key]['User']['encriptada'] = $id;
		}

		if (!empty($this->request->data))
		{
			$this->request->data["Inscritos"] = $this->Session->read("AlumnosInscritos");

			$agregado = $this->Salones->agregarBDD($this->request->data, $inscritos);

			if ($agregado != 1)
	        {
	        	$this->Session->setFlash($agregado);
	        }
	        else
	        {
	        	//Regresa a la pantalla del ciclo
	        	$this->redirect("/salones_index/".$this->Colegio->encriptacion($salon["Salone"]["ciclo_id"]));
	        }
		}

		//Obtiene el grado
		$grado = $this->Grado->find('first', array(
			'conditions' => array('Grado.id' => $salon["Salone"]["grado_id"]),
			'recursive' => 0,
			'fields' => array('Grado.nombre', 'Nivele.nombre')
		));
		
		$salon["Salone"]["grado_id"] = $this->Colegio->encriptacion($salon["Salone"]["grado_id"]);
		$salon["Salone"]["ciclo_id"] = $this->Colegio->encriptacion($salon["Salone"]["ciclo_id"]);
		$salon["Salone"]["id"] = $this->Colegio->encriptacion($salon["Salone"]["id"]);

		//Borra la informacion de alumnos inscritos en otra session
		$this->Session->delete('AlumnosInscritos');

		$colegio_id = $this->Session->read("mi_colegio");

		//Obtiene los maestros
		$maestros = $this->User->query(
			"SELECT DISTINCT(usuario.id), usuario.nombre, usuario.a_paterno, usuario.a_materno
			FROM kappi.users as usuario
			WHERE usuario.colegio_id = $colegio_id
				AND usuario.activo = 1
			    AND usuario.tipo = N'Maestro'
			    AND usuario.id NOT IN 
			    (	SELECT us.id
					FROM kappi.users as us, kappi.salones as sal
			        WHERE us.colegio_id = $colegio_id
			            AND us.activo = 1
						AND us.tipo = N'Maestro'
			            AND sal.user_id = us.id
			            AND sal.ciclo_id = $ciclo_id
			            AND us.id <> ".$salon["User"]["id"]."
			    )
			ORDER BY usuario.a_paterno, usuario.a_materno, usuario.nombre"
		);

		foreach ($maestros as $key => $maestro)
		{
			$id = $this->Colegio->encriptacion($maestro[0]['id']);
			$maestros[$key][0]['encriptada'] = $id;
		}

		$this->set('ciclo', $ciclo);
		$this->set('grado', $grado);
		$this->set('maestros', $maestros);
		$this->set('inscritos', $inscritos);
		$this->set('salon', $salon);
	}
	

//-------------------------------------------------------------------------


	public function datos($id = null)
	{
		$this->loadModel("Colegio");
		$this->loadModel("Ciclo");
		$this->loadModel("Grado");
		$this->loadModel("User");
		$this->loadModel("AlumnosInscrito");

		$id = $this->Colegio->desencriptacion($id);

		$salon = $this->Salone->find('first', array(
			'conditions' => array('Salone.id' => $id),
			'recursive' => 0,
			'fields' => array(
				'Salone.id',
				'Salone.nombre',
				'Salone.ciclo_id',
				'Salone.grado_id',
				'Salone.user_id',
				'Ciclo.nombre',
				'Grado.nombre',
				'User.id'
			)
		));
		$ciclo_id = $salon["Salone"]["ciclo_id"];
		$salon["Salone"]["ciclo_id"] = $this->Colegio->encriptacion($salon["Salone"]["ciclo_id"]);

		//Obtiene el ciclo
		$ciclo = $this->Ciclo->find('first', array(
			'conditions' => array('Ciclo.id' => $ciclo_id),
			'fields' => array('nombre')
		));

		if (!$this->Salone->exists($id, "salones"))
		{
			$this->Session->write("mensaje_autorizacion", "Ese salón no está registrado.");
			$this->redirect("/no_autorizado");
		}

		//Obtiene los inscritos
		$inscritos = $this->AlumnosInscrito->find('all', array(
			'conditions' => array('AlumnosInscrito.salone_id' => $id),
			'order' => array(
				'User.a_paterno' => 'asc',
				'User.a_materno' => 'asc',
				'User.nombre' => 'asc'
			),
			'fields' => array(
				'AlumnosInscrito.id', 'AlumnosInscrito.user_id'
			),
			'recursive' => 0
		));

		foreach ($inscritos as $key => $inscrito)
		{
			$alumno = $this->User->find("first", array(
				'conditions' => array(
					'User.id' => $inscrito["AlumnosInscrito"]["user_id"]
				),
				'fields' => array(
					'User.nombre', 'User.a_paterno', 'User.a_materno', 'User.imagene_id', 'Imagene.ruta', 'Imagene.nombre'
				),
				'recursive' => 0
			));

			$inscritos[$key]['User'] = $alumno["User"];
			$inscritos[$key]['Imagene'] = $alumno["Imagene"];
		}

		//Obtiene el grado
		$grado = $this->Grado->find('first', array(
			'conditions' => array('Grado.id' => $salon["Salone"]["grado_id"]),
			'recursive' => 0,
			'fields' => array('Nivele.nombre')
		));

		//Obtiene el profesor
		$profesor = $this->User->find('first', array(
			'conditions' => array('User.id' => $salon["User"]["id"]),
			'recursive' => 0,
			'fields' => array(
				'User.nombre', 'User.a_paterno', 'User.a_materno', 'User.imagene_id', 'Imagene.ruta', 'Imagene.nombre'
			)
		));

		$this->set('inscritos', $inscritos);
		$this->set('salon', $salon);
		$this->set('ciclo', $ciclo);
		$this->set('grado', $grado);
		$this->set('profesor', $profesor);
	}

}