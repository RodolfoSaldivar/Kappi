

<?php $this->assign('title', 'Ciclo '.$ciclo["Ciclo"]["nombre"].'- Kappi');  ?>

<?php $this->set('titulo_vista', "Ciclo ".$ciclo["Ciclo"]["nombre"]); ?>
<?php $this->set("breadcrumbs",
	'<a href="/ciclos" class="breadcrumb">Ciclos</a>
	<a class="breadcrumb">'.$ciclo["Ciclo"]["nombre"].'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_ciclos").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<!-- Fechas de inicio y fin -->
<div class="bg_blanco">
	<div class="contenedor">
		<div class="row padding_top">
			<div class="input-field col s12 m6">
				<i class="material-icons prefix">today</i>
				<input disabled value="<?php echo $ciclo["Ciclo"]["fecha_inicio"] ?>" id="CicloFechaInicio" type="text">
				<label for="CicloFechaInicio">Fecha Inicio</label>
			</div>
			<div class="input-field col s12 m6">
				<i class="material-icons prefix">event</i>
				<input disabled value="<?php echo $ciclo["Ciclo"]["fecha_fin"] ?>" id="CicloFechaFin" type="text">
				<label for="CicloFechaFin">Fecha Fin</label>
			</div>
		</div>
	</div>
</div>


<!-- Modulo de salones -->
<div class="bg_blanco">
	<div class="contenedor">

		<div class="row azul_4">
			<div style="padding:10px 0 0 20px; font-size:30px;">
				Salones
			</div>
		</div>

		<!-- Barra de busqueda y boton de agregar -->
		<div class="row margin_nada">
			<div class="col s10 offset-s1 m10">
				<?php include 'barra_busqueda_salones.ctp';?>
			</div>
			<div class="m4 l6 hide-on-small-only">
				<a href="/salones/agregar/<?php echo $ciclo["Ciclo"]["encriptada"] ?>" class="bg_complementario right btn-floating btn-large waves-effect waves-light tooltipped" data-position="left" data-delay="50"
					data-tooltip="Agregar Salón">
					<i class="material-icons">add</i>
				</a>
			</div>
		</div>


		<table class="responsive-table bordered highlight">
			<thead>
				<tr>
					<th>Nivel</th>
					<th>Grado</th>
					<th>Salón</th>
					<th>Maestro</th>
					<th class="center hide-on-small-only">Acciones</th>
				</tr>
			</thead>

			<tbody id="filtro_a_cambiar">
				<?php foreach ($salones as $key => $salon): ?>
					<tr onclick="window.location = '/salones/datos/<?php echo $salon["Salone"]["encriptada"] ?>'">
						<td><?php echo $salon["Nivele"]["nombre"] ?></td>
						<td><?php echo $salon["Grado"]["nombre"] ?></td>
						<td>
							<a href="/salones/datos/<?php echo $salon["Salone"]["encriptada"] ?>">
								<?php echo $salon["Salone"]["nombre"] ?></td>
							</a>
						<td>
							<?php echo $salon["User"]["a_paterno"] ?>
							<?php echo $salon["User"]["a_materno"].", " ?>
							<?php echo $salon["User"]["nombre"] ?>
						</td>
						<td class="center ver_editar_acciones">
							<!-- <a href="/salones/datos/<?php echo $salon["Salone"]["encriptada"] ?>">
								<i class="material-icons">visibility</i>
							</a>
							<br class="hide-on-med-and-down"> -->
							<a class="hide-on-small-only" href="/salones/editar/<?php echo $salon["Salone"]["encriptada"] ?>">
								<i class="material-icons">edit</i>
							</a>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>

	</div>
</div>