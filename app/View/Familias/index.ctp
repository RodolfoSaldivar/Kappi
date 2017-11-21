

<?php $this->assign('title', 'Familias - Kappi'); ?>

<?php $this->set('titulo_vista', "Familias"); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_familias").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<!-- Barra de busqueda y boton de agregar -->
<div class="row margin_nada">
	<div class="col s10 offset-s1 m8">
		<?php include 'barra_busqueda_familias.ctp';?>
	</div>
	<div class="col m2 hide-on-small-only padding_top">
		<a href="/familias/subir_excel" class="bg_complementario btn tooltipped" data-position="left" data-delay="50" data-tooltip="Carga Masiva">
			Importar
		</a>
	</div>
	<div class="col m2 hide-on-small-only">
		<a href="/familias/agregar" class="bg_complementario right btn-floating btn-large waves-effect waves-light tooltipped" data-position="left" data-delay="50"
			data-tooltip="Agregar Familia">
			<i class="material-icons">add</i>
		</a>
	</div>
</div>


<!-- Donde viene la tabla de informacion -->
<div class="bg_blanco">	
	<div class="contenedor">
		<table class="bordered highlight">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Identificador</th>
					<th>Miembros</th>
					<th class="center hide-on-small-only">Acciones</th>
				</tr>
			</thead>

			<tbody id="filtro_a_cambiar">
				<?php foreach ($familias as $key => $familia): ?>
					<tr onclick="window.location = '/familias/datos/<?php echo $familia[0]["encriptada"] ?>'">
						<td>
							<?php echo $familia[0]["nombre"] ?>
						</td>
						<td>
							<?php echo $familia[0]["identificador"] ?>
						</td>
						<td>
							<?php foreach ($familia["User"] as $key => $miembro): ?>
								<?php if ($key != 0): ?>
									<br>
								<?php endif ?>
								<?php echo $miembro['a_paterno'] ?>
								<?php echo $miembro['a_materno'].", " ?>
								<?php echo $miembro['nombre'] ?>
							<?php endforeach ?>
						</td>
						<td class="center ver_editar_acciones">
							<!-- <a href="/familias/datos/<?php echo $familia[0]["encriptada"] ?>">
								<i class="material-icons">visibility</i>
							</a>
							<br class="hide-on-small-only"> -->
							<a class="hide-on-small-only" href="familias/editar/<?php echo $familia[0]["encriptada"] ?>">
								<i class="material-icons">edit</i>
							</a>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>


<?php 
	//Ya se estan mostrando todos
	$url = substr($_SERVER['REQUEST_URI'], -13);
	if ($url != "mostrar_todos")
	{
?>

	<div class="center hide-on-med-and-down hide" id="mostrar_todos">	
		<a href="/familias/mostrar_todos" class="waves-effect waves-light btn">
			--------------------------  Mostrar todas las familias  --------------------------
		</a>
	</div>

	<?php $this->Html->scriptStart(array('inline' => false)); ?>

		var cont = 0;
		$("#filtro_a_cambiar > tr").each(function()
		{
			cont++;
		});

		if (cont == 75)
		{
			$("#mostrar_todos").removeClass("hide");
		}

	<?php $this->Html->scriptEnd(); ?>

<?php } ?>