

<div class="bg_blanco">	

	<!-- Barra de busqueda y boton de agregar -->
	<div class="contenedor">	
		<div class="row margin_nada" style="padding-top:10px;">
			<div class="col s10 offset-s1 m10">
				<?php include 'barra_busqueda_enviados.ctp';?>
			</div>

			<?php if ($this->Session->read('Auth.User.tipo') == "Maestro" || $this->Session->read("tipo") == "Circular" && $this->Session->read('Auth.User.tipo') != "Superadministrador"): ?>
					
				<div class="m2 hide-on-small-only">
					<a href="/<?php echo $tipo ?>/escribir" class="bg_complementario right btn-floating btn-large waves-effect waves-light tooltipped" data-position="left" data-delay="50" data-tooltip="Escribir <?php echo $this->Session->read("tipo"); ?>" style="margin-right:10px;">
						<i class="material-icons">add</i>
					</a>
				</div>

			<?php endif ?>	
		</div>
	</div>


	<!-- Donde viene la tabla de informacion -->
	<div class="contenedor">
		<table class="responsive-table bordered highlight">
			<thead>
				<tr>
					<th>Ciclo</th>
					<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
						<th>Emisor</th>
					<?php endif ?>	
					<th>Asunto</th>
					<th>Fecha</th>
					<!-- <th class="center">Acciones</th> -->
				</tr>
			</thead>

			<tbody id="filtro_a_cambiar_enviados">
				<?php foreach ($enviados as $key => $enviado): ?>
					<tr onclick="window.location = 'mensaje_enviado/<?php echo $tipo ?>/<?php echo $enviado["Comunicado"]["encriptada"] ?>'">
						<td><?php echo $enviado["Ciclo"]["nombre"] ?></td>
						<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
							<td>
								<?php echo $enviado["User"]["a_paterno"] ?>
								<?php echo $enviado["User"]["a_materno"].", " ?>
								<?php echo $enviado["User"]["nombre"] ?>
							</td>
						<?php endif ?>
						<td>
							<a href="mensaje_enviado/<?php echo $tipo ?>/<?php echo $enviado["Comunicado"]["encriptada"] ?>">
								<?php echo $enviado["Comunicado"]["asunto"] ?>
							</a>
						</td>
						<td><?php echo $enviado["Comunicado"]["fecha"] ?></td>
						<!-- <td class="center ver_editar_acciones">
							<a href="mensaje_enviado/<?php echo $tipo ?>/<?php echo $enviado["Comunicado"]["encriptada"] ?>">
								<i class="material-icons">visibility</i>
							</a>
						</td> -->
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>