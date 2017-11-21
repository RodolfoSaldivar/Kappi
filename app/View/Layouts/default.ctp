

<!DOCTYPE html>
<html>
<head>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>

	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<?php echo $this->Html->css('materialize.min.css'); ?>
	<?php echo $this->Html->css('style.min.css'); ?>

	<?php
		echo $this->Html->meta('icon');

		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

	<div class="row margin_nada" id="hasta_arriba">

		<!-- Este div es la barra de la izquierda que se vera solamente en computadoras -->
		<div id="menu_izquierdo_global" class="col hide-on-med-and-down">

			<table style="height: 68px;">
				<td class="center" width="100" style="padding:0px 0px; padding-top:5px;">
					<img width="64" src="/img/kappi_peque.png">
				</td>
			</table>

			<div class="divider"></div>

			<div id="menu_global_acciones" class="azul_4">
				---Acciones
			</div>

			<?php $tipo_usuario = $this->Session->read('Auth.User.tipo'); ?>

			<!-- Lista de acciones que hay -->
			<ul id="menu_todas_acciones" class="collapsible azul_5" data-collapsible="accordion">

				<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
				<li>
					<div class="collapsible-header" id="accion_de_colegios">
						<a href="/colegios">
							<div>
								<i class="material-icons">domain</i>
								<?php if ($tipo_usuario == "Superadministrador"): ?>
									Colegios
								<?php else: ?>
									Colegio
								<?php endif ?>
							</div>
						</a>
					</div>
				</li>
				<?php endif ?>


				<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
				<li>
					<div class="collapsible-header" id="accion_de_usuarios">
						<i class="material-icons">person</i>
						Usuarios
					</div>
					<div class="collapsible-body">
						<ul class="collection">
							<a href="/alumnos" id="usuarios_accion_alumnos">
								<li class="collection-item">
									Alumnos
								</li>
							</a>
							<a href="/madres" id="usuarios_accion_madres">
								<li class="collection-item">
									Mamás
								</li>
							</a>
							<a href="/padres" id="usuarios_accion_padres">
								<li class="collection-item">
									Papás
								</li>
							</a>
							<a href="/maestros" id="usuarios_accion_maestros">
								<li class="collection-item">
									Maestros
								</li>
							</a>
							<a href="/administradores" id="usuarios_accion_administradores">
								<li class="collection-item">
									Administradores del sistema
								</li>
							</a>
							<?php if (in_array($tipo_usuario, array("Superadministrador"))): ?>
								<a href="/superadministradores" id="usuarios_accion_superadministradores">
									<li class="collection-item">
										Administrador General
									</li>
								</a>
							<?php endif ?>	
						</ul>
					</div>
				</li>
				<?php endif ?>


				<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
				<li>
					<div class="collapsible-header" id="accion_de_familias">
						<a href="/familias">
							<div>
								<i class="material-icons">group</i>
								Familias
							</div>
						</a>
					</div>
				</li>
				<?php endif ?>


				<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
				<li>
					<div class="collapsible-header" id="accion_de_ciclos">
						<a href="/ciclos">
							<div>
								<i class="material-icons">school</i>
								Ciclos Escolares
							</div>
						</a>
					</div>
				</li>
				<?php endif ?>


				<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
				<li>
					<div class="collapsible-header" id="accion_de_extras">
						<a href="/extras">
							<div>
								<i class="material-icons">thumbs_up_down</i>
								Catálogo
							</div>
						</a>
					</div>
				</li>
				<?php endif ?>


				<?php if ($this->Session->read('numero_hijos') != 1): ?>
				<li>
					<div class="collapsible-header">
						<table>
							<tr>
								<td class="nombre_hijo_menu">
									<i class="material-icons">person</i>
								</td>
								<td class="nombre_hijo_menu">
									<?php foreach ($this->Session->read("todos_mis_hijos") as $key => $hijo): ?>
										<?php if ($hijo["User"]["id"] == $this->Session->read("mi_hijo")): ?>
											<?php echo $hijo["User"]["nombre"] ?>
											<?php echo $hijo["User"]["a_paterno"] ?>
											<?php echo $hijo["User"]["a_materno"] ?>
										<?php endif ?>
									<?php endforeach ?>
								</td>
							</tr>
						</table>
					</div>
					<div class="collapsible-body">
						<ul class="collection">
							<?php foreach ($this->Session->read("todos_mis_hijos") as $key => $hijo): ?>
								<form action="/escoger_hijo" id="UserEscogerHijoForm" method="post" accept-charset="utf-8">
									
									<input type="hidden" name="data[User][id]" id="UserId">
									
									<a href="#">
										<li class="collection-item"  id="user_<?php echo $hijo["User"]["id"] ?>">
											<div class="img_select_hijo" >
											<?php if (!empty($hijo['User']['imagene_id'])): ?>
												<img class="responsive-img circle" src="<?php echo $hijo['Imagene']['ruta'] ?><?php echo $hijo['Imagene']['nombre'] ?>">
											<?php else: ?>
												<img class="responsive-img circle" src="/img/default_user.png">
											<?php endif ?>
											</div>
											<?php echo $hijo["User"]["nombre"] ?>
											<?php echo $hijo["User"]["a_paterno"] ?>
											<?php echo $hijo["User"]["a_materno"] ?>
										</li>
									</a>

								</form>
							<?php endforeach ?>								
						</ul>
					</div>
				</li>
				<?php endif ?>


				<li>
					<div class="collapsible-header" id="accion_de_mensajes">
						<i class="material-icons">email</i>
						Mensajes
					</div>
					<div class="collapsible-body">
						<ul class="collection">
							<?php if (!in_array($tipo_usuario, array("Maestro"))): ?>
								<a href="/circulares" id="mensajes_accion_circulares">
									<li class="collection-item">
										Circulares
										<?php if (in_array($tipo_usuario, array("Madre", "Padre", "Alumno"))): ?>
											<span class="circle badge bg_complementario azul_5">
												<?php if($num_circ != 0) echo $num_circ ?>
											</span>
										<?php endif ?>
									</li>
								</a>
							<?php endif ?>
							<a href="/tareas" id="mensajes_accion_tareas">
								<li class="collection-item">
									Tareas
									<?php if (in_array($tipo_usuario, array("Madre", "Padre", "Alumno"))): ?>
										<span class="circle badge bg_complementario azul_5">
											<?php if($num_tar != 0) echo $num_tar ?>
										</span>
									<?php endif ?>
								</li>
							</a>
							<a href="/comunicados" id="mensajes_accion_comunicados">
								<li class="collection-item">
									Comunicados
									<?php if (in_array($tipo_usuario, array("Madre", "Padre", "Alumno"))): ?>
										<span class="circle badge bg_complementario azul_5">
											<?php if($num_coms != 0) echo $num_coms ?>
										</span>
									<?php endif ?>
								</li>
							</a>
							<a href="/distinciones" id="mensajes_accion_distinciones">
								<li class="collection-item">
									Distinciones
								</li>
							</a>
							<a href="/reportes" id="mensajes_accion_reportes">
								<li class="collection-item">
									Reportes
								</li>
							</a>
						</ul>
					</div>
				</li>

			</ul>
		</div>

		<!-- Este div es el que maneja el contenido y que se hara a pantalla completa cuando es tableta o celular, tendra una navegacion accedida por medio de un boton -->
		<div id="contenido_vistas" class="col l10 s12 bg_azul_1" style="padding:0px;">

			<!-- Esta es la barra de hasta arriba de la pantalla -->
			<nav id="nav_menu_global" class="bg_azul_5">
				<div class="nav-wrapper">

					<!-- Menu hamburguesa -->
					<ul id="nav-mobile" class="left hide-on-large-only">
						<a href="#" data-activates="hamburguesa_menu" id="menu_hamburguesa_global">
							<i class="material-icons">menu</i>
						</a>
					</ul>

					<!-- Menu opciones usuario -->
					<ul id="nav-mobile" class="right hide-on-large-only">
						<a href="#" data-activates="usuario_opciones" id="menu_opciones_usuario">
							<i class="material-icons">more_vert</i>
						</a>
					</ul>
					
					<!-- Foto y nombre -->
					<ul id="nav-mobile" class="right" style="line-height:64px;">
						<li id="li_para_foto_usuario" class="valign-wrapper">
							<img id="imagen_usuario_global" class="responsive-img circle valign" src="<?php echo $this->Session->read('Auth.User.Imagene.ruta'); ?><?php echo $this->Session->read('Auth.User.Imagene.nombre'); ?>">
						</li>
						
						<div class="chip hide-on-med-and-down bg_azul_1" id="chip_nombre_usuario">
							<a class="dropdown-button azul_5" href="#!" data-activates="dropdown1">
								<?php echo $this->Session->read('Auth.User.nombre'); ?>
								<?php echo $this->Session->read('Auth.User.a_paterno'); ?>
								<?php echo $this->Session->read('Auth.User.a_materno'); ?>
							</a>
						</div>
					</ul>

				</div>
			</nav>

			<!-- Breadcrumbs y titulo de vista -->
			<nav id="bread_titulo_vista" class="azul_5">
				<div class="nav-wrapper">
					<div class="left">
						<div style="font-size:2.23rem; margin-left:30px">
							<?php echo @$titulo_vista; ?>
						</div>
					</div>
					<div class="right hide-on-small-only" style="margin-right:30px">
						<div class="right">
							<?php echo @$breadcrumbs; ?>
						</div>
					</div>
				</div>
			</nav>

			<!-- Estas son las opciones que hay al seleccionar el nombre de usuario -->
			<ul id="dropdown1" class="dropdown-content">
				<li><a href="/cambiar_foto">Foto</a></li>
				<li><a href="/cambiar_contrasena">Contraseña</a></li>
				<li class="divider"></li>
				<li><a href="/users/logout">Cerrar Sesión</a></li>
			</ul>
			
			<!-- Esta es la barra lateral izquierda en movil que se activa con el boton de hamburguesa menu -->
			<ul id="hamburguesa_menu" class="side-nav">
				<table style="height: 68px;">
					<td class="center" width="100" style="padding:0px 0px; padding-top:5px;">
						<img width="64" src="/img/kappi_peque.png">
					</td>
				</table>

				<div class="divider"></div>

				<div id="menu_global_acciones" class="azul_4">
					---Acciones
				</div>

				<!-- Lista de acciones que hay -->
				<ul id="menu_todas_acciones" class="mta_movil collapsible azul_5" data-collapsible="accordion">

					<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
					<li>
						<div class="collapsible-header">
							<a href="/colegios">
								<div>
									<i class="material-icons">domain</i>
									<?php if ($tipo_usuario == "Superadministrador"): ?>
										Colegios
									<?php else: ?>
										Colegio
									<?php endif ?>
								</div>
							</a>
						</div>
					</li>
					<?php endif ?>
					

					<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
					<li>
						<div class="collapsible-header">
							<i class="material-icons">person</i>
							Usuarios
						</div>
						<div class="collapsible-body">
							<ul class="collection">
								<a href="/alumnos">
									<li class="collection-item">
										Alumnos
									</li>
								</a>
								<a href="/madres">
									<li class="collection-item">
										Mamás
									</li>
								</a>
								<a href="/padres">
									<li class="collection-item">
										Papás
									</li>
								</a>
								<a href="/maestros">
									<li class="collection-item">
										Maestros
									</li>
								</a>
								<a href="/administradores">
									<li class="collection-item">
										Administradores del sistema
									</li>
								</a>
								<?php if (in_array($tipo_usuario, array("Superadministrador"))): ?>
									<a href="/superadministradores">
										<li class="collection-item">
											Administrador General
										</li>
									</a>
								<?php endif ?>	
							</ul>
						</div>
					</li>
					<?php endif ?>


					<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
					<li>
						<div class="collapsible-header">
							<a href="/familias">
								<div>
									<i class="material-icons">group</i>
									Familias
								</div>
							</a>
						</div>
					</li>
					<?php endif ?>


					<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
					<li>
						<div class="collapsible-header">
							<a href="/ciclos">
								<div>
									<i class="material-icons">school</i>
									Ciclos Escolares
								</div>
							</a>
						</div>
					</li>
					<?php endif ?>


					<?php if (in_array($tipo_usuario, array("Superadministrador", "Administrador"))): ?>
					<li>
						<div class="collapsible-header">
							<a href="/extras">
								<div>
									<i class="material-icons">thumbs_up_down</i>
									Catálogo
								</div>
							</a>
						</div>
					</li>
					<?php endif ?>


					<?php if ($this->Session->read('numero_hijos') != 1): ?>
					<li>
						<div class="collapsible-header">
							<table>
								<tr>
									<td class="nombre_hijo_menu" style="width:42px;">
										<i class="material-icons">person</i>
									</td>
									<td class="nombre_hijo_menu">
										<?php foreach ($this->Session->read("todos_mis_hijos") as $key => $hijo): ?>
											<?php if ($hijo["User"]["id"] == $this->Session->read("mi_hijo")): ?>
												<?php echo $hijo["User"]["nombre"] ?>
												<?php echo $hijo["User"]["a_paterno"] ?>
												<?php echo $hijo["User"]["a_materno"] ?>
											<?php endif ?>
										<?php endforeach ?>
									</td>
								</tr>
							</table>
						</div>
						<div class="collapsible-body">
							<ul class="collection">
								<?php foreach ($this->Session->read("todos_mis_hijos") as $key => $hijo): ?>
									<form action="/escoger_hijo" id="UserEscogerHijoForm" method="post" accept-charset="utf-8">
										
										<input type="hidden" name="data[User][id]" id="UserId">
										
										<a href="#">
											<li class="collection-item"  id="user_<?php echo $hijo["User"]["id"] ?>">
												<div class="img_select_hijo" >
												<?php if (!empty($hijo['User']['imagene_id'])): ?>
													<img class="responsive-img circle" src="<?php echo $hijo['Imagene']['ruta'] ?><?php echo $hijo['Imagene']['nombre'] ?>">
												<?php else: ?>
													<img class="responsive-img circle" src="/img/default_user.png">
												<?php endif ?>
												</div>
												<?php echo $hijo["User"]["nombre"] ?>
												<?php echo $hijo["User"]["a_paterno"] ?>
												<?php echo $hijo["User"]["a_materno"] ?>
											</li>
										</a>

									</form>
								<?php endforeach ?>								
							</ul>
						</div>
					</li>
					<?php endif ?>


					<li>
						<div class="collapsible-header">
							<i class="material-icons">email</i>
							Mensajes
						</div>
						<div class="collapsible-body">
							<ul class="collection">
								<?php if (!in_array($tipo_usuario, array("Maestro"))): ?>
									<a href="/circulares">
										<li class="collection-item">
											Circulares
											<?php if (in_array($tipo_usuario, array("Madre", "Padre", "Alumno"))): ?>
												<span class="circle badge bg_complementario azul_5">
													<?php if($num_circ != 0) echo $num_circ ?>
												</span>
											<?php endif ?>
										</li>
									</a>
								<?php endif ?>	
								<a href="/tareas">
									<li class="collection-item">
										Tareas
										<?php if (in_array($tipo_usuario, array("Madre", "Padre", "Alumno"))): ?>
											<span class="circle badge bg_complementario azul_5">
												<?php if($num_tar != 0) echo $num_tar ?>
											</span>
										<?php endif ?>
									</li>
								</a>
								<a href="/comunicados">
									<li class="collection-item">
										Comunicados
										<?php if (in_array($tipo_usuario, array("Madre", "Padre", "Alumno"))): ?>
											<span class="circle badge bg_complementario azul_5">
												<?php if($num_coms != 0) echo $num_coms ?>
											</span>
										<?php endif ?>
									</li>
								</a>
								<a href="/distinciones">
									<li class="collection-item">
										Distinciones
									</li>
								</a>
								<a href="/reportes">
									<li class="collection-item">
										Reportes
									</li>
								</a>
							</ul>
						</div>
					</li>

				</ul>
			</ul>
			
			<!-- Esta es la barra lateral derecha en movil que se activa con el boton de opciones del usuario -->
			<ul id="usuario_opciones" class="side-nav">
				<table>
					<td class="valign-wrapper" style="padding:6px 11px;">
						<img id="imagen_usuario_global" class="responsive-img circle valign" src="<?php echo $this->Session->read('Auth.User.Imagene.ruta'); ?><?php echo $this->Session->read('Auth.User.Imagene.nombre'); ?>">
					</td>
					<td style="padding:0;">
						<a class="azul_5" id="nombre_lateral_movil">
							<?php echo $this->Session->read('Auth.User.nombre'); ?>
							<?php echo $this->Session->read('Auth.User.a_paterno'); ?>
							<?php echo $this->Session->read('Auth.User.a_materno'); ?>
						</a>
					</td>
				</table>
				<ul class="collection">
					<a href="/cambiar_foto">
						<li class="collection-item">
							Foto
						</li>
					</a>
					<a href="/cambiar_contrasena">
						<li class="collection-item">
							Contraseña
						</li>
					</a>
					<li class="divider"></li>
					<a href="/users/logout">
						<li class="collection-item">
							Cerrar Sesión
						</li>
					</a>
				</ul>
			</ul>

			<!-- Aqui se muestra todo el contenido de las vistas -->
			
			<div id="contenido_vistas_padding">
				<div>
					<?php echo $this->fetch('content'); ?>	
				</div>
			</div>

			<a href="#hasta_arriba" class="hasta_arriba">Hasta Arriba</a>

		</div>
	</div>

	

	<?php echo $this->Html->script('jquery-2.1.1.min.js'); ?>
	<?php echo $this->Html->script('materialize.min.js'); ?>
	<?php echo $this->Html->script('jquery.validate.min.js'); ?>
	<?php echo $this->Html->script('alphanumeric.js'); ?>
	<?php echo $this->Html->script('resize_sensor.js'); ?>
	<?php echo $this->fetch('script'); ?>

	<script type="text/javascript">

		// Se inicializan los componentes de materialize
		$(document).ready(function()
		{
			$("#menu_hamburguesa_global").sideNav({edge: 'left'});
			$("#menu_opciones_usuario").sideNav({edge: 'right'});
			$('.collapsible').collapsible();
			$(".dropdown-button").dropdown({
				belowOrigin: true,		//Que este debajo del drop
				constrain_width: true	//Que sea del mismo ancho que drop
			});
			$('.tooltipped').tooltip({delay: 50, opacity: 0.7});
		});

		//Funcion que hace las 2 columnas del mismo largo
		function mismoLargo()
		{
			$('#menu_izquierdo_global').height("auto");
			$('#contenido_vistas').height("auto");

			var masGrande = 0;
			var menu = $('#menu_izquierdo_global').height();
			var contenido = $('#contenido_vistas').height();
			var ventana = $(window).height();

			if (menu > masGrande)
				masGrande = menu;

			if (contenido > masGrande)
				masGrande = contenido;

			if (ventana > masGrande)
				masGrande = ventana;

			$('#menu_izquierdo_global').height(masGrande);
			$('#contenido_vistas').height(masGrande);
		}

		$(document).ready(function() { mismoLargo(); });
		$(document).resize(function() { mismoLargo(); });

		new ResizeSensor(jQuery('#contenido_vistas_padding'), function(){ 
		    mismoLargo();
		});

		<?php //Para los padres que tengan mas de 1 hijo
			if ($this->Session->read('numero_hijos') != 1)
				foreach ($this->Session->read("todos_mis_hijos") as $key => $hijo): ?>
			
				$(document).on("click", "#user_<?php echo $hijo["User"]["id"] ?>", function()
				{
					$("#UserId").val("<?php echo $hijo["User"]["id"] ?>")
					$("#UserEscogerHijoForm").submit();
				});

		<?php endforeach ?>

		//Boton de hasta arriba
		$(window).scroll(function() {
			if ( $(window).scrollTop() > $(window).height() ) {
				$('a.hasta_arriba').fadeIn('slow');
			} else {
				$('a.hasta_arriba').fadeOut('slow');
			}
		});

		$('a.hasta_arriba').click(function() {
			$('html, body').animate({
				scrollTop: 0
			}, 700);
			return false;
		});

		//Mensaje Toast
		<?php echo $this->Session->flash('flash', array(
		    'element' => 'mensaje_toast'
		)); ?>

	</script>
</body>
</html>