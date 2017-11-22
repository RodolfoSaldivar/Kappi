<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

	Router::mapResources('users');
	Router::parseExtensions();
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'users', 'action' => 'login'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));


//-------------------------------------------------------------------------


	Router::connect('/no_autorizado', array('controller' => 'pages', 'action' => 'display', 'no_autorizado'));
	Router::connect('/iniciar_sesion', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/seleccionar_colegio', array('controller' => 'users', 'action' => 'seleccionar_colegio'));
	Router::connect('/escoger_hijo', array('controller' => 'users', 'action' => 'escoger_hijo'));
	Router::connect('/olvide_contrasena', array('controller' => 'users', 'action' => 'olvide_contrasena'));
	Router::connect('/cambiar_contrasena', array('controller' => 'users', 'action' => 'cambiar_contrasena'));
	Router::connect('/cambiar_foto', array('controller' => 'users', 'action' => 'cambiar_foto'));
	Router::connect('/resetear_contra/*', array('controller' => 'users', 'action' => 'resetear_contra'));


//-------------------------------------------------------------------------


	Router::connect('/alumnos', array('controller' => 'users', 'action' => 'alumnos'));
	Router::connect('/alumnos/agregar', array('controller' => 'users', 'action' => 'agregar'));
	Router::connect('/alumnos/editar/*', array('controller' => 'users', 'action' => 'editar'));
	Router::connect('/alumnos/datos/*', array('controller' => 'users', 'action' => 'datos'));


	Router::connect('/madres', array('controller' => 'users', 'action' => 'madres'));
	Router::connect('/madres/agregar', array('controller' => 'users', 'action' => 'agregar'));
	Router::connect('/madres/editar/*', array('controller' => 'users', 'action' => 'editar'));
	Router::connect('/madres/datos/*', array('controller' => 'users', 'action' => 'datos'));


	Router::connect('/padres', array('controller' => 'users', 'action' => 'padres'));
	Router::connect('/padres/agregar', array('controller' => 'users', 'action' => 'agregar'));
	Router::connect('/padres/editar/*', array('controller' => 'users', 'action' => 'editar'));
	Router::connect('/padres/datos/*', array('controller' => 'users', 'action' => 'datos'));


	Router::connect('/maestros', array('controller' => 'users', 'action' => 'maestros'));
	Router::connect('/maestros/agregar', array('controller' => 'users', 'action' => 'agregar'));
	Router::connect('/maestros/editar/*', array('controller' => 'users', 'action' => 'editar'));
	Router::connect('/maestros/datos/*', array('controller' => 'users', 'action' => 'datos'));


	Router::connect('/administradores', array('controller' => 'users', 'action' => 'administradores'));
	Router::connect('/administradores/agregar', array('controller' => 'users', 'action' => 'agregar'));
	Router::connect('/administradores/editar/*', array('controller' => 'users', 'action' => 'editar'));
	Router::connect('/administradores/datos/*', array('controller' => 'users', 'action' => 'datos'));


	Router::connect('/superadministradores', array('controller' => 'users', 'action' => 'superadministradores'));
	Router::connect('/superadministradores/agregar', array('controller' => 'users', 'action' => 'agregar'));
	Router::connect('/superadministradores/editar/*', array('controller' => 'users', 'action' => 'editar'));
	Router::connect('/superadministradores/datos/*', array('controller' => 'users', 'action' => 'datos'));


//-------------------------------------------------------------------------


	Router::connect('/circulares', array('controller' => 'comunicados', 'action' => 'circulares'));
	Router::connect('/circulares/escribir/*', array('controller' => 'comunicados', 'action' => 'escribir'));
	Router::connect('/circulares/datos/*', array('controller' => 'comunicados', 'action' => 'datos'));


	Router::connect('/tareas', array('controller' => 'comunicados', 'action' => 'tareas'));
	Router::connect('/tareas/escribir/*', array('controller' => 'comunicados', 'action' => 'escribir'));
	Router::connect('/tareas/datos/*', array('controller' => 'comunicados', 'action' => 'datos'));


	Router::connect('/comunicados', array('controller' => 'comunicados', 'action' => 'comunicados'));
	Router::connect('/comunicados/escribir/*', array('controller' => 'comunicados', 'action' => 'escribir'));
	Router::connect('/comunicados/datos/*', array('controller' => 'comunicados', 'action' => 'datos'));


	Router::connect('/distinciones', array('controller' => 'extras', 'action' => 'distinciones'));
	Router::connect('/distinciones/mandar', array('controller' => 'extras', 'action' => 'mandar_extra'));
	Router::connect('/distinciones/datos/*', array('controller' => 'extras', 'action' => 'datos'));


	Router::connect('/reportes', array('controller' => 'extras', 'action' => 'reportes'));
	Router::connect('/reportes/mandar', array('controller' => 'extras', 'action' => 'mandar_extra'));
	Router::connect('/reportes/datos/*', array('controller' => 'extras', 'action' => 'datos'));


//-------------------------------------------------------------------------


	Router::connect('/salones_index/*', array('controller' => 'salones', 'action' => 'index'));


//-------------------------------------------------------------------------


	Router::connect('/descargar_imagenes/*', array('controller' => 'comunicados', 'action' => 'descargar_imagenes'));
	Router::connect('/descargar_pdf/*', array('controller' => 'comunicados', 'action' => 'descargar_pdf'));
	Router::connect('/mensaje_abierto/*', array('controller' => 'comunicados', 'action' => 'mensaje_abierto'));
	Router::connect('/mensaje_enviado/*', array('controller' => 'comunicados', 'action' => 'mensaje_enviado'));
	Router::connect('/firmar_mensaje/*', array('controller' => 'comunicados', 'action' => 'firmar_mensaje'));
	Router::connect('/descartar_guardado/*', array('controller' => 'comunicados', 'action' => 'descartar_guardado'));
	Router::connect('/guardar_mensaje/*', array('controller' => 'comunicados', 'action' => 'guardar_mensaje'));


//-------------------------------------------------------------------------


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
