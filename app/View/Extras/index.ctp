

<?php $this->assign('title', 'Catálogo - Kappi'); ?>

<?php $this->set('titulo_vista', "Catálogo"); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_extras").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<div class="row">

	<!-- Todas las distinciones -->
	<div class="col s12 m6" style="margin-bottom:40px;">
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row margin_nada">
				<div class="extra_tipo left">
					Distinciones
					<i class="material-icons">thumb_up</i>
				</div>
				<div class="hide-on-small-only extra_agregar">
					<a href="/extras/agregar/distinciones" class="bg_complementario right btn-floating btn-small waves-effect waves-light tooltipped" data-position="left" data-delay="50"
						data-tooltip="Agregar Distinción">
						<i class="material-icons">add</i>
					</a>
				</div>
			</div>

			<table class="responsive-table bordered highlight">
				<thead>
					<tr>
						<th>Imagen</th>
						<th>Descripcion</th>
						<th class="center hide-on-small-only">Acciones</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($extras as $key => $extra): ?>
					<?php if ($extra['Extra']['tipo'] == "distinciones"): ?>
						
						<tr class="cursor_normal">
							<td width="100">
								<img class="responsive-img materialboxed" src="<?php echo $extra['Imagene']['ruta'] ?><?php echo $extra['Imagene']['nombre'] ?>">
							</td>
							<td>
								<?php echo $extra['Extra']['descripcion'] ?>
							</td>
							<td class="center ver_editar_acciones">
								<a class="hide-on-small-only" href="extras/editar/distinciones/<?php echo $extra["Extra"]["encriptada"] ?>">
									<i class="material-icons">edit</i>
								</a>
							</td>
						</tr>

					<?php endif ?>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
	</div>

	<!-- Todas los reportes -->
	<div class="col s12 m6">
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row margin_nada">	
				<div class="extra_tipo left">
					Reportes
					<i class="material-icons">thumb_down</i>
				</div>
				<div class="hide-on-small-only extra_agregar">
					<a href="/extras/agregar/reportes" class="bg_complementario right btn-floating btn-small waves-effect waves-light tooltipped" data-position="left" data-delay="50"
						data-tooltip="Agregar Reporte">
						<i class="material-icons">add</i>
					</a>
				</div>
			</div>

			<table class="responsive-table bordered highlight">
				<thead>
					<tr>
						<th>Imagen</th>
						<th>Descripcion</th>
						<th class="center hide-on-small-only">Acciones</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($extras as $key => $extra): ?>
					<?php if ($extra['Extra']['tipo'] == "reportes"): ?>
						
						<tr class="cursor_normal">
							<td width="100">
								<img class="responsive-img materialboxed" src="<?php echo $extra['Imagene']['ruta'] ?><?php echo $extra['Imagene']['nombre'] ?>">
							</td>
							<td>
								<?php echo $extra['Extra']['descripcion'] ?>
							</td>
							<td class="center ver_editar_acciones">
								<a class="hide-on-small-only" href="extras/editar/reportes/<?php echo $extra["Extra"]["encriptada"] ?>">
									<i class="material-icons">edit</i>
								</a>
							</td>
						</tr>

					<?php endif ?>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
	</div>

</div>