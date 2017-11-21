

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
				<td class="center ver_editar_acciones">
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