

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