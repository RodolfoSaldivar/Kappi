

<div class="bg_blanco">	

	<!-- Barra de busqueda y boton de agregar -->
	<div class="contenedor">	
		<div class="row margin_nada" style="padding-top:10px;">
			<div class="col s10 offset-s1 m10">
				<?php include 'barra_busqueda_recibidos.ctp';?>
			</div>
		</div>
	</div>


	<!-- Donde viene la tabla de informacion -->
	<div class="contenedor">
		<table class="responsive-table bordered highlight">
			<thead>
				<tr>
					<th>Emisor</th>
					<th>Asunto</th>
					<th>Fecha</th>
					<!-- <th>Status</th>
					<th class="center">Acciones</th> -->
				</tr>
			</thead>

			<tbody id="filtro_a_cambiar_recibidos">
				<?php foreach ($recibidos as $key => $recibido): ?>
					<?php if ($recibido["Destinatario"]["visto"] > 0): ?>
						<tr onclick="window.location = 'mensaje_abierto/<?php echo $tipo ?>/<?php echo $recibido["Comunicado"]["encriptada"] ?>'">
					<?php else: ?>
						<tr class="no_leido" onclick="window.location = 'mensaje_abierto/<?php echo $tipo ?>/<?php echo $recibido["Comunicado"]["encriptada"] ?>'">
					<?php endif ?>

						<td>
							<?php echo $recibido["User"]["a_paterno"] ?>
							<?php echo $recibido["User"]["a_materno"].", " ?>
							<?php echo $recibido["User"]["nombre"] ?>
						</td>
						<td>
							<?php echo $recibido["Comunicado"]["asunto"] ?>
						</td>
						<td>
							<?php echo $recibido["Comunicado"]["fecha"] ?>
						</td>
						<!-- <td class="center">
							<?php if ($recibido["Destinatario"]["visto"] > 0): ?>
								<i class="material-icons">mail_outline</i>
							<?php else: ?>
								<i class="material-icons">email</i>
							<?php endif ?>
						</td>
						<td class="center ver_editar_acciones">
							<a href="mensaje_abierto/<?php echo $tipo ?>/<?php echo $recibido["Comunicado"]["encriptada"] ?>">
								<i class="material-icons">visibility</i>
							</a>
						</td> -->
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>