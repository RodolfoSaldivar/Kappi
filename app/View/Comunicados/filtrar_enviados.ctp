

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