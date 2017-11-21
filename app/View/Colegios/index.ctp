

<?php if ($this->Session->read('Auth.User.tipo') == "Superadministrador"): ?>
		
	<?php $this->assign('title', 'Colegios - Kappi'); ?>

	<?php $this->set('titulo_vista', "Colegios"); ?>

<?php else: ?>

	<?php $this->assign('title', 'Colegio - Kappi'); ?>

	<?php $this->set('titulo_vista', "Colegio"); ?>

<?php endif ?>	

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_colegios").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>

	
<?php
	//Solo si es superadministrador
	if ($this->Session->read('Auth.User.tipo') == "Superadministrador")
	{ ?>
		<div class="row margin_nada">
			<table class="card-panel bg_blanco col s12">
				<tr>
					<td class="center">
						<i class="material-icons" style="color:red;">error</i>
					</td>
					<td class="center">
						<h5>
							Como Superadministrador puede ver todos los colegios.<br>
							El colegio actual se muestra  en negritas.
						</h5>
					</td>
				</tr>
			</table>			
		</div>

		<div class="row">
			<div class="col s12">
				<a class="btn waves-effect waves-light" href="/seleccionar_colegio">
					Cambiar de Colegio
					<i class="material-icons right white-text">repeat</i>
				</a>
			</div>
		</div>

		<!-- Barra de busqueda y boton de agregar -->
		<div class="row margin_nada">
			<div class="col s10 offset-s1 m8 l6">
				<?php include 'barra_busqueda_colegios.ctp';?>
			</div>
			<div class="m4 l6 hide-on-small-only">
				<a href="/colegios/agregar" class="bg_complementario right btn-floating btn-large waves-effect waves-light tooltipped" data-position="left" data-delay="50"
					data-tooltip="Agregar Colegio">
					<i class="material-icons">add</i>
				</a>
			</div>
		</div>
<?php } ?>



<!-- Donde viene la tabla de informacion -->
<div class="bg_blanco">	
	<div class="contenedor">
		<table class="responsive-table bordered highlight">
			<thead>
				<tr>
					<th class="hide-on-med-and-down">Logo</th>
					<th>Nombre</th>
					<th>Corto</th>
					<th>Teléfono</th>
					<?php if ($this->Session->read("Auth.User.tipo") == "Superadministrador"): ?>
						<th class="center">Activo</th>	
					<?php endif ?>
					<th class="hide-on-small-only center">Acciones</th>
				</tr>
			</thead>

			<tbody id="filtro_a_cambiar">
				<?php foreach ($colegios as $key => $colegio): ?>
				<?php if ($this->Session->read('mi_colegio') == $colegio['Colegio']['id'] || $this->Session->read('Auth.User.tipo') == "Superadministrador"): ?>
						
					<tr <?php if ($this->Session->read('mi_colegio') == $colegio['Colegio']['id']) echo 'class="no_leido"'; ?>>
						<td width="130" class="hide-on-med-and-down" style="padding-right:30px;">
							<img class="responsive-img materialboxed" src="<?php echo $colegio['Imagene']['ruta'] ?><?php echo $colegio['Imagene']['nombre'] ?>">
						</td>
						<td onclick="window.location = 'colegios/datos/<?php echo $colegio["Colegio"]["encriptada"] ?>'">
							<?php echo $colegio["Colegio"]["nombre_comercial"] ?>
						</td>
						<td onclick="window.location = 'colegios/datos/<?php echo $colegio["Colegio"]["encriptada"] ?>'"><?php echo $colegio["Colegio"]["nombre_corto"] ?></td>
						<td onclick="window.location = 'colegios/datos/<?php echo $colegio["Colegio"]["encriptada"] ?>'"><?php echo $colegio["Colegio"]["telefono"] ?></td>
						<?php if ($this->Session->read("Auth.User.tipo") == "Superadministrador"): ?>
								
							<td class="center" id="poner_switch_<?php echo $colegio["Colegio"]["encriptada"] ?>">
								<div class="switch">
									<label>
										No
										<input <?php if ($colegio["Colegio"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Activo][<?php echo $colegio["Colegio"]["encriptada"] ?>]' id='Activo<?php echo $colegio["Colegio"]["encriptada"] ?>' value='<?php echo $colegio["Colegio"]["activo"] ?>'>
										<span class="lever"></span>
										Si
									</label>
								</div>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).on("change", "#Activo<?php echo $colegio["Colegio"]["encriptada"] ?>", function()
	{
		ajax<?php echo $colegio["Colegio"]["encriptada"] ?>();
	});

	function ajax<?php echo $colegio["Colegio"]["encriptada"] ?>()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Colegios', 'action' => 'activo_actualizar']); ?>',
	        success: function(response)
	        {
	            $('#poner_switch_<?php echo $colegio["Colegio"]["encriptada"] ?>').children().replaceWith(response);
	        },
	        data: {
	        	id: '<?php echo $colegio["Colegio"]["encriptada"] ?>',
	        	activo: $('#Activo<?php echo $colegio["Colegio"]["encriptada"] ?>').val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>


							</td>

						<?php endif ?>	
						<td class="center ver_editar_acciones">
							<!-- <a href="colegios/datos/<?php echo $colegio["Colegio"]["encriptada"] ?>">
								<i class="material-icons">visibility</i>
							</a>
							<br class="hide-on-med-and-down"> -->
							<a class="hide-on-small-only" href="colegios/editar/<?php echo $colegio["Colegio"]["encriptada"] ?>">
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