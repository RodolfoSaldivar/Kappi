<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array(
        //'Security',
        'Session',
        'Flash',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'comunicados',
                'action' => 'tareas'
            ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            ),
            'authorize' => array('Controller')
        )
    );

    public function isAuthorized($user) {
        // Admin can access every action
        if (isset($user['tipo']) && $user['tipo'] === 'Superadministrador'){
            return true;
        }

        // Default deny
        return false;
    }

    public function beforeFilter()
    {
        if(in_array($this->params['controller'],array('users'))){
            // For RESTful web service requests, we check the name of our contoller
            $this->Auth->allow('');
            // this line should always be there to ensure that all rest calls are secure
            //$this->Security->requireSecure();
            //$this->Security->unlockedActions = array('login', 'seleccionar_colegio');
             
        }else{
            // setup out Auth
            $this->Auth->allow('');         
        }

        //Busca los mensajes que no se han leido si es madre, padre o alumno
        if (in_array($this->Session->read('Auth.User.tipo'), array("Madre", "Padre", "Alumno")))
        {
            $this->loadModel("Destinatario");
            $this->loadModel("Ciclo");

            $ciclo_id = $this->Ciclo->find("first", array(
                'conditions' => array(
                    'Ciclo.activo' => 1,
                    'Ciclo.colegio_id' => $this->Session->read("mi_colegio")
                ),
                'fields' => array('Ciclo.id')
            ));
            $ciclo_id = $ciclo_id['Ciclo']['id'];
            
            $comunicado_tipo = 0;
            $user_id = $this->Session->read('Auth.User.id');
            $consulta = "
                SELECT COUNT(des.id)
                FROM kappi.destinatarios as des, kappi.comunicados as com
                WHERE des.user_id = $user_id
                    AND des.visto = 0
                    AND des.comunicado_id = com.id
                    AND com.ciclo_id = $ciclo_id
            ";

            if (!empty($this->Session->read("mi_hijo")))
                $consulta = $consulta." AND des.hijo = ".$this->Session->read("mi_hijo");

            $num_circ = $this->Destinatario->query($consulta." AND com.tipo = N'Circular'");

            $num_tar = $this->Destinatario->query($consulta." AND com.tipo = N'Tarea'");

            $num_coms = $this->Destinatario->query($consulta." AND com.tipo = N'Comunicado'");

            $this->set("num_circ", $num_circ[0][0][""]);
            $this->set("num_tar", $num_tar[0][0][""]);
            $this->set("num_coms", $num_coms[0][0][""]);
        }
    }

}