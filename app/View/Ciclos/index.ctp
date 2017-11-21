

<?php $this->assign('title', 'Ciclos - Kappi'); ?>

<?php $this->set('titulo_vista', "Ciclos Escolares"); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_ciclos").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>



<!-- Barra de busqueda y boton de agregar -->
<div class="row margin_nada">
	<div class="col s10 offset-s1 m10">
		<?php include 'barra_busqueda_ciclos.ctp';?>
	</div>
	<div class="m4 l6 hide-on-small-only">
		<a href="/ciclos/agregar" class="bg_complementario right btn-floating btn-large waves-effect waves-light tooltipped" data-position="left" data-delay="50"
			data-tooltip="Agregar Ciclo">
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
					<th>Nombre</th>
					<th>Inicio</th>
					<th>Fin</th>
					<th class="center">Activo</th>
					<th class="center hide-on-small-only">Acciones</th>
				</tr>
			</thead>

			<tbody id="filtro_a_cambiar">
				<?php foreach ($ciclos as $key => $ciclo): ?>
					<tr>
						<td onclick="window.location = 'salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>'">
							<a href="salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>">
								<?php echo $ciclo["Ciclo"]["nombre"] ?>
							</a>
						</td>
						<td onclick="window.location = 'salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>'"><?php echo $ciclo["Ciclo"]["fecha_inicio"] ?></td>
						<td onclick="window.location = 'salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>'"><?php echo $ciclo["Ciclo"]["fecha_fin"] ?></td>
						<td class="center" id="poner_switch_<?php echo $ciclo["Ciclo"]["encriptada"] ?>">
							<div class="switch">
								<label>
									No
									<input <?php if ($ciclo["Ciclo"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Activo][<?php echo $ciclo["Ciclo"]["encriptada"] ?>]' id='Activo<?php echo $ciclo["Ciclo"]["encriptada"] ?>' value='<?php echo $ciclo["Ciclo"]["activo"] ?>'>
									<span class="lever"></span>
									Si
								</label>
							</div>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).on("change", "#Activo<?php echo $ciclo["Ciclo"]["encriptada"] ?>", function()
	{
		ajax<?php echo $ciclo["Ciclo"]["encriptada"] ?>();
	});

	function ajax<?php echo $ciclo["Ciclo"]["encriptada"] ?>()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Ciclos', 'action' => 'activo_actualizar']); ?>',
	        success: function(response)
	        {
	            $('#poner_switch_<?php echo $ciclo["Ciclo"]["encriptada"] ?>').children().replaceWith(response);

	            Materialize.toast(
	            	$('#poner_switch_<?php echo $ciclo["Ciclo"]["encriptada"] ?> .ciclo_mensaje').text(),
	            	5000,
	            	"bg_azul_5 con_sombra"
	            );
	        },
	        data: {
	        	id: '<?php echo $ciclo["Ciclo"]["encriptada"] ?>',
	        	activo: $('#Activo<?php echo $ciclo["Ciclo"]["encriptada"] ?>').val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>


						</td>
						<td class="center ver_editar_acciones">
							<!-- <a href="salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>">
								<i class="material-icons">visibility</i>
							</a>
							<br class="hide-on-med-and-down"> -->
							<a class="hide-on-small-only" href="ciclos/editar/<?php echo $ciclo["Ciclo"]["encriptada"] ?>">
								<i class="material-icons">edit</i>
							</a>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>