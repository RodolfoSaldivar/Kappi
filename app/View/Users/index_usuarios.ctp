

<!-- Barra de busqueda y boton de agregar -->
<div class="row margin_nada">
	<div class="col s10 offset-s1 m8">
		<?php include 'barra_busqueda_usuarios.ctp';?>
	</div>
	<div class="col m2 hide-on-small-only padding_top">
		<a href="/users/subir_excel" class="bg_complementario btn tooltipped" data-position="left" data-delay="50" data-tooltip="Carga Masiva">
			Importar
		</a>
	</div>
	<div class="col m2 hide-on-small-only">
		<a href="/<?php echo $tipo ?>/agregar" class="bg_complementario right btn-floating btn-large waves-effect waves-light tooltipped" data-position="left" data-delay="50"
			data-tooltip="Agregar <?php echo $this->Session->read("tipo"); ?>">
			<i class="material-icons">add</i>
		</a>
	</div>
</div>


<!-- Donde viene la tabla de informacion -->
<div class="bg_blanco">	
	<div class="contenedor">
		<table class="responsive-table bordered highlight">
			<thead>
				<tr>
					<th class="hide-on-med-and-down">Foto</th>
					<th>Nombre</th>
					<th>Identificador</th>
					<th>Usuario</th>
					<th>Correo</th>
					<th class="center">Activo</th>
					<th class="center hide-on-small-only">Acciones</th>
				</tr>
			</thead>

			<tbody id="filtro_a_cambiar">
				<?php foreach ($usuarios as $key => $usuario): ?>
					<tr>
						<td width="100" class="hide-on-med-and-down" style="padding-right:30px;">
							<?php if (!empty($usuario['User']['imagene_id'])): ?>
								<img class="responsive-img materialboxed circle" src="<?php echo $usuario['Imagene']['ruta'] ?><?php echo $usuario['Imagene']['nombre'] ?>">
							<?php else: ?>
								<img class="responsive-img materialboxed circle" src="/img/default_user.png">
							<?php endif ?>							
						</td>
						<td onclick="window.location = '<?php echo $tipo ?>/datos/<?php echo $usuario["User"]["encriptada"] ?>'">
							<?php echo $usuario["User"]["a_paterno"] ?>
							<?php echo $usuario["User"]["a_materno"].", " ?>
							<?php echo $usuario["User"]["nombre"] ?>
						</td>
						<td onclick="window.location = '<?php echo $tipo ?>/datos/<?php echo $usuario["User"]["encriptada"] ?>'"><?php echo $usuario["User"]["identificador"] ?></td>
						<td onclick="window.location = '<?php echo $tipo ?>/datos/<?php echo $usuario["User"]["encriptada"] ?>'"><?php echo $usuario["User"]["username"] ?></td>
						<td onclick="window.location = '<?php echo $tipo ?>/datos/<?php echo $usuario["User"]["encriptada"] ?>'"><?php echo $usuario["User"]["correo"] ?></td>
						<td class="center" id="poner_switch_<?php echo $usuario["User"]["encriptada"] ?>">
							<div class="switch">
								<label>
									No
									<input <?php if ($usuario["User"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Activo][<?php echo $usuario["User"]["encriptada"] ?>]' id='Activo<?php echo $usuario["User"]["encriptada"] ?>' value='<?php echo $usuario["User"]["activo"] ?>'>
									<span class="lever"></span>
									Si
								</label>
							</div>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).on("change", "#Activo<?php echo $usuario["User"]["encriptada"] ?>", function()
	{
		ajax<?php echo $usuario["User"]["encriptada"] ?>();
	});

	function ajax<?php echo $usuario["User"]["encriptada"] ?>()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'users', 'action' => 'activo_actualizar']); ?>',
	        success: function(response)
	        {
	            $('#poner_switch_<?php echo $usuario["User"]["encriptada"] ?>').children().replaceWith(response);
	        },
	        data: {
	        	id: '<?php echo $usuario["User"]["encriptada"] ?>',
	        	activo: $('#Activo<?php echo $usuario["User"]["encriptada"] ?>').val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>


						</td>
						<td class="center ver_editar_acciones">
							<!-- <a href="<?php echo $tipo ?>/datos/<?php echo $usuario["User"]["encriptada"] ?>">
								<i class="material-icons">visibility</i>
							</a>
							<br class="hide-on-med-and-down"> -->
							<a class="hide-on-small-only" href="<?php echo $tipo ?>/editar/<?php echo $usuario["User"]["encriptada"] ?>">
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
		<a href="/users/mostrar_todos" class="waves-effect waves-light btn">
			--------------------------  Mostrar todos los usuarios  --------------------------
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