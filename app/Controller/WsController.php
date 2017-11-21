<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('File', 'Utility');

class WsController extends AppController {
    public $uses = array('User');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler', 'Qimage');


//-------------------------------------------------------------------------


    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }


//-------------------------------------------------------------------------


    public function isAuthorized($user)
    {
        return true;
    }


//-------------------------------------------------------------------------


    public function login()
    {
        if (!empty($this->request->data))
        {
            $data = $this->request->data;

            $usuario = $this->User->find('first', array(
                'recursive' => 0,
                'conditions' => array(
                    'User.username' => $data['usuario'],
                    'User.activo' => 1
                ),
                'fields' => array(
                    'User.id', 'User.nombre', 'User.a_paterno', 'User.a_materno', 'User.password', 'User.tipo', 'User.clave', 'User.colegio_id', 'User.familia_id', 'Imagene.id', 'Imagene.nombre', 'Imagene.ruta'
                )
            ));

            //Si no existe el usuario
            if (empty($usuario['User']['password']))
            {
                $ws["usuario_id"] = "00";
                $ws["mensaje"] = "Usuario invalido";
            }
            else
            {
                $contra_encriptada = $usuario['User']['password'];

                //Si es la contraseña correcta
                $blowF = new BlowfishPasswordHasher();
                if ($blowF->check($data['contra'], $contra_encriptada))
                {
                    //Si es madre, padre o alumno
                    if (in_array($usuario['User']['tipo'], array("Madre", "Padre", "Alumno")))
                    {
                        $this->loadModel("Colegio");

                        $colegio = $this->Colegio->find("first", array(
                            'recursive' => 0,
                            'conditions' => array('Colegio.id' => $usuario['User']['colegio_id']),
                            'fields' => array('Imagene.id', 'Imagene.nombre', 'Imagene.ruta')
                        ));

                        $ws["usuario_tipo"] = $usuario['User']['tipo'];
                        $ws["nombre"] = $usuario['User']['nombre'];
                        $ws["a_paterno"] = $usuario['User']['a_paterno'];
                        $ws["a_materno"] = $usuario['User']['a_materno'];
                        $ws["col_img_nombre"] = $colegio['Imagene']['nombre'];
                        $ws["col_img_ruta"] = $colegio['Imagene']['ruta'];
                        
                        //Si es madre o padre
                        if (in_array($usuario['User']['tipo'], array("Madre", "Padre")))
                        {
                            $hijos = $this->User->find('count', array(
                                'conditions' => array(
                                    'User.tipo' => 'Alumno',
                                    'User.colegio_id' => $usuario['User']['colegio_id'],
                                    'User.familia_id' => $usuario['User']['familia_id']
                                )
                            ));

                            //No tiene hijos registrados
                            if ($hijos == 0)
                            {
                                $ws["usuario_id"] = '00';
                                $ws["mensaje"] = "Sin hijos";
                            }
                            else
                            {
                                //Tiene mas de 1 hijo en el sistema
                                if ($hijos > 1)
                                {
                                    $hijos = $this->User->find('all', array(
                                        'conditions' => array(
                                            'User.tipo' => 'Alumno',
                                            'User.colegio_id' => $usuario['User']['colegio_id'],
                                            'User.familia_id' => $usuario['User']['familia_id'],
                                            'User.activo' => 1
                                        ),
                                        'fields' => array(
                                            'User.id', 'User.nombre', 'User.a_paterno', 'User.a_materno', 'Imagene.ruta', 'Imagene.nombre'
                                        ),
                                        'recursive' => 0
                                    ));

                                    $ws["hijo_id"] = $hijos;
                                    $ws["mensaje"] = "Muchos hijos";
                                }
                                else
                                {
                                    $mi_hijo = $this->User->find('first', array(
                                        'recursive' => 0,
                                        'fields' => array(
                                            'User.id', 'User.nombre', 'User.a_paterno', 'User.a_materno', 'Imagene.ruta', 'Imagene.nombre'
                                        ),
                                        'conditions' => array(
                                            'User.tipo' => 'Alumno',
                                            'User.colegio_id' => $usuario['User']['colegio_id'],
                                            'User.familia_id' => $usuario['User']['familia_id']
                                        )
                                    ));
                                    $ws["hijo_id"] = $mi_hijo;
                                    $ws["mensaje"] = "1 hijo";
                                }

                                $ws["usuario_id"] = $usuario['User']['id'];
                                $ws["token"] = $usuario['User']['clave'];
                                $ws["imagen_ruta"] = $usuario['Imagene']['ruta'];
                                $ws["imagen_nombre"] = $usuario['Imagene']['nombre'];
                            }
                        }
                        else
                        {//Si es alumno
                            $ws["usuario_id"] = $usuario['User']['id'];
                            $ws["token"] = $usuario['User']['clave'];
                            $ws["imagen_ruta"] = $usuario['Imagene']['ruta'];
                            $ws["imagen_nombre"] = $usuario['Imagene']['nombre'];
                        }
                    }
                    else
                    {//Si NO es madre, padre o alumno
                        $ws["usuario_id"] = '00';
                        $ws["mensaje"] = "Solo para padres y alumnos";
                    }
                }
                else
                {
                    $ws["usuario_id"] = '00';
                    $ws["mensaje"] = "Contra invalida";
                }
            }
                            
            $this->set(array(
                'ws' => $ws,
                '_serialize' => array('ws')
            ));
        }
    }


//-------------------------------------------------------------------------


    public function mensajes()
    {
        if (!empty($this->request->data))
        {
            $this->loadModel("Destinatario");
            $this->loadModel("User");
            
            $usuario_id = $this->request->data['usuario_id'];
            $usuario_tipo = $this->request->data['usuario_tipo'];
            $hijo_id = $this->request->data['hijo_id'];
            $mensaje_tipo = $this->request->data['mensaje_tipo'];

            $colegio_id = $this->User->find("first", array(
                'conditions' => array(
                    'User.id' => $usuario_id
                ),
                'fields' => array('User.id', 'User.colegio_id')
            ));
            $colegio_id = $colegio_id['User']['colegio_id'];

            if (in_array($mensaje_tipo, array("Circular", "Tarea", "Comunicado")))
            {
                if (in_array($usuario_tipo, array("Madre", "Padre")))
                    $dest_query =   " AND Destinatario.hijo = ".$hijo_id."
                                    AND Destinatario.comunicado_id = Comunicado.id";
                else
                    $dest_query = " AND Destinatario.comunicado_id = Comunicado.id";

                $recibidos = $this->Destinatario->query("
                    SELECT Destinatario.*, usuario.a_paterno, usuario.a_materno, usuario.nombre, usuario.colegio_id, Comunicado.id as com_id, Comunicado.asunto, Comunicado.fecha
                    FROM kappi.destinatarios as Destinatario, kappi.users as usuario, kappi.comunicados as Comunicado, kappi.ciclos as Ciclo
                    WHERE Destinatario.user_id = $usuario_id $dest_query
                        AND Comunicado.tipo = N'$mensaje_tipo'
                        AND Comunicado.user_id = usuario.id
                        AND Comunicado.ciclo_id = Ciclo.id
                        AND Ciclo.activo = 1
                    ORDER BY Comunicado.created DESC
                ");

                foreach ($recibidos as $key => $datos)
                {
                    $arreglo["Destinatario"]["id"] = $datos[0]["id"];
                    $arreglo["Destinatario"]["user_id"] = $datos[0]["user_id"];
                    $arreglo["Destinatario"]["comunicado_id"] = $datos[0]["comunicado_id"];
                    $arreglo["Destinatario"]["visto"] = $datos[0]["visto"];
                    $arreglo["Destinatario"]["fecha_visto"] = $datos[0]["fecha_visto"];
                    $arreglo["Destinatario"]["firmado"] = $datos[0]["firmado"];
                    $arreglo["Destinatario"]["fecha_firmado"] = $datos[0]["fecha_firmado"];
                    $arreglo["Destinatario"]["hijo"] = $datos[0]["hijo"];
                    $arreglo["Destinatario"]["created"] = $datos[0]["created"];
                    $arreglo["Destinatario"]["modified"] = $datos[0]["modified"];

                    $arreglo["User"]["a_paterno"] = $datos[0]["a_paterno"];
                    $arreglo["User"]["a_materno"] = $datos[0]["a_materno"];
                    $arreglo["User"]["nombre"] = $datos[0]["nombre"];

                    $arreglo["Comunicado"]["id"] = $datos[0]["com_id"];
                    $arreglo["Comunicado"]["asunto"] = $datos[0]["asunto"];
                    $arreglo["Comunicado"]["fecha"] = $datos[0]["fecha"];

                    $recibidos[$key] = $arreglo;
                }
            }

            if (in_array($mensaje_tipo, array("distinciones", "reportes")))
            {
                $this->loadModel("Imagene");
                $this->loadModel("DestinoExtra");

                $condiciones = array(
                    'DestinoExtra.user_id' => $usuario_id,
                    'Extra.tipo' => $mensaje_tipo
                );

                if (in_array($usuario_tipo, array("Madre", "Padre")))
                    $condiciones['DestinoExtra.hijo'] = $hijo_id;

                $fechas = $this->DestinoExtra->find('all', array(
                    'conditions' => $condiciones,
                    'group' => array('DestinoExtra.fecha'),
                    'fields' => array('DestinoExtra.fecha'),
                    'order' => array('MIN(DestinoExtra.created)' => 'desc'),
                    'limit' => 50,
                    'recursive' => 0
                ));

                $recibidos;
                foreach ($fechas as $keyFe => $fecha)
                {
                    $condiciones = array(
                        'DestinoExtra.user_id' => $usuario_id,
                        'DestinoExtra.fecha' => $fecha['DestinoExtra']['fecha'],
                        'Extra.tipo' => $mensaje_tipo
                    );

                    if (in_array($usuario_tipo, array("Madre", "Padre")))
                        $condiciones['DestinoExtra.hijo'] = $hijo_id;
                    
                    $extras = $this->DestinoExtra->find('all', array(
                        'conditions' => $condiciones,
                        'fields' => array('Extra.descripcion', 'Extra.imagene_id'),
                        'order' => array('DestinoExtra.created' => 'desc'),
                        'recursive' => 0
                    ));

                    foreach ($extras as $keyEx => $extra)
                    {
                        $imagen = $this->Imagene->find('first', array(
                            'conditions' => array('Imagene.id' => $extra['Extra']['imagene_id']),
                            'fields' => array('id', 'nombre', 'ruta')
                        ));

                        $extras[$keyEx]['Imagene'] = $imagen['Imagene'];
                    }

                    $recibidos[$keyFe]['Fecha'] = $fecha['DestinoExtra']['fecha'];
                    $recibidos[$keyFe]['Extras'] = $extras;
                }

                if (empty($recibidos))
                    $recibidos = array();
            }

            $sin_leer = $this->sin_leer($usuario_id, $usuario_tipo, $hijo_id, $colegio_id);

            $ws['sin_leer'] = $sin_leer;
            $ws['recibidos'] = $recibidos;
                            
            $this->set(array(
                'ws' => $ws,
                '_serialize' => array('ws')
            ));
        }
    }


//-------------------------------------------------------------------------


    function sin_leer($usuario_id, $usuario_tipo, $hijo_id, $colegio_id)
    {
        if (in_array($usuario_tipo, array("Madre", "Padre", "Alumno")))
        {
            $this->loadModel("Destinatario");
            $this->loadModel("Ciclo");

            $ciclo_id = $this->Ciclo->find("first", array(
                'conditions' => array(
                    'Ciclo.activo' => 1,
                    'Ciclo.colegio_id' => $colegio_id
                ),
                'fields' => array('Ciclo.id')
            ));
            $ciclo_id = $ciclo_id['Ciclo']['id'];

            $condiciones = array(
                'Destinatario.user_id' => $usuario_id,
                'Destinatario.visto' => 0,
                'Comunicado.ciclo_id' => $ciclo_id
            );

            if (in_array($usuario_tipo, array("Madre", "Padre"))) 
                $condiciones['Destinatario.hijo'] = $hijo_id;

            $condiciones['Comunicado.tipo'] = 'Circular';
            $cont_circular = $this->Destinatario->find("count", array(
                'conditions' => $condiciones,
                'recursive' => 0
            ));

            $condiciones['Comunicado.tipo'] = 'Tarea';
            $cont_tarea = $this->Destinatario->find("count", array(
                'conditions' => $condiciones,
                'recursive' => 0
            ));

            $condiciones['Comunicado.tipo'] = 'Comunicado';
            $cont_comunicado = $this->Destinatario->find("count", array(
                'conditions' => $condiciones,
                'recursive' => 0
            ));

            $no_leidos = array(
                'cont_circular' => $cont_circular,
                'cont_tarea' => $cont_tarea,
                'cont_comunicado' => $cont_comunicado
            );
        }
        else
            $no_leidos = 0;

        return $no_leidos;
    }


//-------------------------------------------------------------------------


    public function olvide_contra()
    {
        if ($this->request->is('post'))
        {
            $username = $this->request->data['username'];
            var_dump($username);

            $usuario = $this->User->find('first', array(
                'conditions' => array('User.username' => $username),
                'fields' => array('correo', 'clave', 'nombre', 'a_paterno', 'a_materno')
            ));

            if (empty($usuario)) {
                $ws['mensaje'] = 'Usuario no existente.';
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
                    'usu_username' => $username,
                    'nombre' => $nombre,
                    'a_paterno' => $a_paterno,
                    'a_materno' => $a_materno,
                    'nueva_contra' => $nueva_contra
                ));
                $Email->send($url);

                $ws['mensaje'] = 'Se le mandó un correo electrónico, siga las instrucciones.';
            }

            $this->set(array(
                'ws' => $ws,
                '_serialize' => array('ws')
            ));
        }
    }


//-------------------------------------------------------------------------


    public function cambiar_contra()
    {
        if (!empty($this->request->data))
        {
            $usuario_id = $this->request->data['usuario_id'];

            $usuario = $this->User->find('first', array(
                'conditions' => array('User.id' => $usuario_id),
                'fields' => array('User.id', 'User.password')
            ));

            $contra_bdd = $usuario["User"]["password"];
            $contra_vieja = $this->request->data['contra_vieja'];
            $contra_nueva = $this->request->data['contra_nueva'];

            //Verifica que la contraseña dada sea la misma que la de la base de datos
            $blowF = new BlowfishPasswordHasher();
            if ($blowF->check($contra_vieja, $contra_bdd))
            {
                //Checa que la nueva contraseña sea alfanumerica
                if(preg_match('/[^a-z_\ñáéíóú\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>]/i', $contra_nueva))
                    $ws['mensaje'] = 'Contraseña nueva solo letras y números.';
                else
                {
                    //Checa que tenga más de 8 caracteres
                    if (strlen($contra_nueva) < 8)
                        $ws['mensaje'] = 'Mínimo 8 caracteres.';
                    else
                    {
                        //Checa que tenga menos de 20 caracteres
                        if (strlen($contra_nueva) > 20)
                            $ws['mensaje'] = 'Máximo 20 caracteres.';
                        else
                        {
                            //Esta validado, ahora si se hace el cambio
                            $usuario['User']['password'] = $contra_nueva;
                            $this->User->query("
                                UPDATE kappi.users
                                SET password = '$contra_nueva'
                                WHERE id = ".$usuario["User"]["id"]."
                            ");

                            $ws['mensaje'] = "Contraseña cambiada exitosamente.";
                        }
                    }
                }
            }
            else
            {
                $ws['mensaje'] = 'Contraseña actual incorrecta.';
            }

            $this->set(array(
                'ws' => $ws,
                '_serialize' => array('ws')
            ));
        }
    }
    

//-------------------------------------------------------------------------


    public function cambiar_foto()
    {
        if (!empty($this->request->data))
        {
            $this->loadModel("Imagene");

            $usuario_id = $this->request->data['usuario_id'];

            //Obtiene el usuario
            $usuario = $this->User->find('first', array(
                'conditions' => array('User.id' => $usuario_id),
                'fields' => array(
                    'Imagene.id',
                    'Imagene.ruta',
                    'Imagene.nombre'
                ),
                'recursive' => 0
            ));

            $imagenBorrar['id'] = $usuario['Imagene']['id'];
            $imagenBorrar['ruta'] = $usuario['Imagene']['ruta'];
            $imagenBorrar['nombre'] = $usuario['Imagene']['nombre'];

            //Para que se pueda guardar la imagen
            $raw = str_replace(' ', '+', $this->request->data["img"]);

            $decodificada = base64_decode($raw);
            $name = uniqid() . date('dmYHis') . '.png';

            if (file_put_contents(WWW_ROOT.'/img/usuarios_movil/'.$name, $decodificada))
            {
                //Ruta en donde sera guardada la imagen
                $ruta = '/img/usuarios_movil/';

                //Se produce el nombre de la imagen
                $nombre = $name;

                //Crea y guarda
                $this->Imagene->query("
                    INSERT INTO kappi.imagenes (nombre, ruta)
                    VALUES ('$nombre', '$ruta')
                ");

                //Se obtiene el id de la imagen recien guardada
                $imagen_id = $this->Imagene->query("
                    SELECT id
                    FROM kappi.imagenes
                    WHERE nombre = '$nombre'
                        AND created =
                            (   SELECT MAX(created)
                                FROM kappi.imagenes
                                WHERE nombre = '$nombre'
                            )
                ");
                $imagen_id = $imagen_id[0][0]['id'];

                //Crea y guarda
                $this->Imagene->query("
                    UPDATE kappi.users
                    SET imagene_id = $imagen_id
                    WHERE id = $usuario_id
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
                }


                $ws["nombre"] = $nombre;
                $ws["ruta"] = $ruta;

            }
            else
                $ws["mensaje"] = "No se guardo";


            $this->set(array(
                'ws' => $ws,
                '_serialize' => array('ws')
            ));
        }
    }


//-------------------------------------------------------------------------


    public function ver_mensaje()
    {
        if (!empty($this->request->data))
        {
            $this->loadModel("Colegio");
            $this->loadModel("Destinatario");
            $this->loadModel("Archivo");
            $this->loadModel("ImagenesComunicado");
            $this->loadModel("Comunicado");

            $usuario_id = $this->request->data['usuario_id'];
            $usuario_tipo = $this->request->data['usuario_tipo'];
            $mensaje_id = $this->request->data['mensaje_id'];
            $hijo_id = $this->request->data['hijo_id'];


            $condiciones = array(
                'Destinatario.user_id' => $usuario_id,
                'Destinatario.comunicado_id' => $mensaje_id
            );

            if (in_array($usuario_tipo, array("Madre", "Padre"))) 
                $condiciones['Destinatario.hijo'] = $hijo_id;

            $destinatario = $this->Destinatario->find('first', array(
                'conditions' => $condiciones,
                'fields' => array(
                    'Destinatario.id',
                    'Destinatario.visto',
                    'Destinatario.fecha_visto',
                    'Destinatario.firmado'
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



            $mensaje = $this->Comunicado->find('first', array(
                'conditions' => array('Comunicado.id' => $mensaje_id),
                'fields' => array('Comunicado.mensaje', 'Comunicado.tipo', 'Comunicado.asunto', 'Comunicado.fecha', 'User.nombre', 'User.a_paterno', 'User.a_materno'),
                'recursive' => 0
            ));

            $imagenes = $this->ImagenesComunicado->find('all', array(
                'conditions' => array('ImagenesComunicado.comunicado_id' => $mensaje_id),
                'fields' => array('Imagene.ruta', 'Imagene.nombre'),
                'recursive' => 0
            ));

            $todos_pdf = $this->Archivo->find('all', array(
                'conditions' => array('Archivo.comunicado_id' => $mensaje_id),
                'fields' => array('Archivo.nombre')
            ));



            $ws['mensaje'] = $mensaje['Comunicado'];
            $ws['emisor'] = $mensaje['User'];
            $ws['destinatario'] = $destinatario['Destinatario'];
            $ws['imagenes'] = $imagenes;
            $ws['pdf'] = $todos_pdf;

            $this->set(array(
                'ws' => $ws,
                '_serialize' => array('ws')
            ));
        }
    }


//-------------------------------------------------------------------------


    public function firmar()
    {
        if (!empty($this->request->data))
        {
            $this->loadModel('Destinatario');

            $usuario_id = $this->request->data['usuario_id'];
            $usuario_tipo = $this->request->data['usuario_tipo'];
            $mensaje_id = $this->request->data['mensaje_id'];
            $hijo_id = $this->request->data['hijo_id'];

            $condiciones = array(
                'Destinatario.user_id' => $usuario_id,
                'Destinatario.comunicado_id' => $mensaje_id
            );

            if (in_array($usuario_tipo, array("Madre", "Padre")))
                $condiciones['Destinatario.hijo'] = $hijo_id;

            $destinatario = $this->Destinatario->find('first', array(
                'conditions' => $condiciones,
                'fields' => array(
                    'Destinatario.id',
                    'Destinatario.fecha_firmado',
                    'Destinatario.firmado',
                    'Destinatario.visto'
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

            if ($destinatario['Destinatario']['firmado'] == 0)
            {
                $destinatario["Destinatario"]["firmado"] = 1;
                $ws['mensaje'] = "Exitoso";

                $this->Destinatario->query("
                    UPDATE kappi.destinatarios
                    SET firmado = ".$destinatario["Destinatario"]["firmado"].",
                        fecha_firmado = '".$destinatario["Destinatario"]["fecha_firmado"]."'
                    WHERE id = ".$destinatario["Destinatario"]["id"]."
                ");
            }
            else
                $ws['mensaje'] = "Ya estaba firmado.";            


            $this->set(array(
                'ws' => $ws,
                '_serialize' => array('ws')
            ));
        }
    }


//-------------------------------------------------------------------------


    
}