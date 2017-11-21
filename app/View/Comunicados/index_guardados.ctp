

<div class="bg_blanco">	

	<!-- Barra de busqueda y boton de agregar -->
	<div class="contenedor">	
		<div class="row margin_nada" style="padding-top:10px;">
			<div class="col s10 offset-s1 m10">
				<?php include 'barra_busqueda_guardados.ctp';?>
			</div>
		</div>
	</div>


	<!-- Donde viene la tabla de informacion -->
	<div class="contenedor">
		<table class="responsive-table bordered">
			<thead>
				<tr>
					<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
						<th>Emisor</th>
					<?php endif ?>
					<th>Asunto</th>
					<th>Mensaje</th>
					<th class="center hide-on-small-only">Acciones</th>
				</tr>
			</thead> 

			<tbody id="filtro_a_cambiar_guardados">
				<?php foreach ($guardados as $key => $guardado): ?>
					<tr>
						<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
							<td>
								<?php echo $guardado["User"]["a_paterno"] ?>
								<?php echo $guardado["User"]["a_materno"].", " ?>
								<?php echo $guardado["User"]["nombre"] ?>
							</td>
						<?php endif ?>
						<td>
							<?php if ($user_tipo == "Maestro"): ?>
								<a href="/<?php echo $tipo ?>/escribir/<?php echo $guardado["Comunicado"]["encriptada"] ?>">
									<?php echo $guardado["Comunicado"]["asunto"] ?>
								</a>
							<?php else: ?>
								<?php echo $guardado["Comunicado"]["asunto"] ?>
							<?php endif ?>
						</td>
						<td><?php echo $guardado["Comunicado"]["mensaje"] ?></td>
						<?php if ($guardado['User']['id'] == $this->Session->read('Auth.User.id')): ?>
							<td class="center ver_editar_acciones hide-on-small-only">
								<a class="tooltipped" data-position="left" data-delay="50" data-tooltip="Reusar <?php echo $this->Session->read("tipo"); ?>" href="/<?php echo $tipo ?>/escribir/<?php echo $guardado["Comunicado"]["encriptada"] ?>" style="display:inline-block;">
									<i class="material-icons">add_circle</i>
								</a>
								<br>
								<a onclick="toast_eliminar_<?php echo $guardado["Comunicado"]["encriptada"] ?>()" style="cursor:pointer;">
									<i class="material-icons">delete</i>
								</a>
							</td>
						<?php endif ?>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>