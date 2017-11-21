<tbody id="filtro_a_cambiar_enviados">
	<?php foreach ($enviados as $key => $enviado): ?>

		<tr id="submit<?php echo $enviado['DestinoExtra']['id_encriptada'] ?>">
			<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
				<td>
					<?php echo $enviado["Profe"]["a_paterno"] ?>
					<?php echo $enviado["Profe"]["a_materno"].", " ?>
					<?php echo $enviado["Profe"]["nombre"] ?>
				</td>
				
			<?php endif ?>
			<td>
				<?php echo $enviado["DestinoExtra"]["fecha"] ?>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>