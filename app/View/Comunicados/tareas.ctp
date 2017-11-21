

<?php $this->assign('title', 'Tareas - Kappi');  ?>

<?php $this->set('titulo_vista', "Tareas"); ?>
<?php $this->set("breadcrumbs",
	'<a class="breadcrumb">Mensajes</a>
	<a class="breadcrumb">Tareas</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_mensajes").addClass("active");
		$("#mensajes_accion_tareas").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<div class="row">

	<div class="col s12">
		<ul class="tabs">
			<?php if (!in_array($this->Session->read('Auth.User.tipo'), array('Superadministrador', 'Administrador', 'Maestro'))): ?>
				<li class="tab col s3">
					<a id="recibidos_ponme" href="#todos_recibidos">Recibidos</a>
				</li>
			<?php endif ?>	
				
			<?php if (!in_array($this->Session->read('Auth.User.tipo'), array('Alumno', 'Madre', 'Padre'))): ?>
				<li class="tab col s3">
					<a id="enviados_ponme" href="#todos_enviados">Enviados</a>
				</li>
				<li class="tab col s3">
					<a id="guardados_ponme" href="#todos_guardados">Guardados</a>
				</li>
			<?php endif ?>
		</ul>
	</div>


	<?php if (!in_array($this->Session->read('Auth.User.tipo'), array('Superadministrador', 'Administrador', 'Maestro'))): ?>
		<div id="todos_recibidos" class="col s12">
			<br>
			<?php include 'index_recibidos.ctp';?>
		</div>
	<?php endif ?>

	<?php if (!in_array($this->Session->read('Auth.User.tipo'), array('Alumno', 'Madre', 'Padre'))): ?>

		<div id="todos_enviados" class="col s12">
			<br>
			<?php include 'index_enviados.ctp';?>
		</div>
			
		<div id="todos_guardados" class="col s12">
			<br>
			<?php include 'index_guardados.ctp';?>
		</div>

	<?php endif ?>	
</div>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	<?php if ($user_tipo == "Alumno" || $user_tipo == "Padre" || $user_tipo == "Madre"): ?>
		$("#recibidos_ponme").addClass("active");
	<?php else: ?>
		$("#enviados_ponme").addClass("active");
	<?php endif ?>



	<?php if (!empty($guardados)) foreach ($guardados as $key => $guardado): ?>
			
		function toast_eliminar_<?php echo $guardado["Comunicado"]["encriptada"] ?>()
		{
			Materialize.toast(
				'<div>Se descartará el mensaje guardado.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><a href="/descartar_guardado/<?php echo $guardado["Comunicado"]["encriptada"] ?>" class="waves-effect waves-light btn"><i class="material-icons blanco">done</i></a>',
				6000,
				"bg_azul_5"
			);
		}

	<?php endforeach ?>	

<?php $this->Html->scriptEnd(); ?>