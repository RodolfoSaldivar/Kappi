

<div class="col s6" id="filtro_a_cambiar">
	<table>
		<tbody>

			<?php foreach ($familia as $key => $usuario): ?>
				
				<tr>
					<td width="100" class="hide-on-med-and-down" style="padding-right:30px;">
						<?php if (!empty($usuario["User"]['imagene_id'])): ?>
							<img class="responsive-img materialboxed circle" src="<?php echo $usuario['Imagene']['ruta'] ?><?php echo $usuario['Imagene']['nombre'] ?>">
						<?php else: ?>
							<img class="responsive-img materialboxed circle" src="/img/default_user.png">
						<?php endif ?>
					</td>
					<td>
						<?php echo $usuario["User"]['nombre'] ?>
						<?php echo $usuario["User"]['a_paterno'] ?>
						<?php echo $usuario["User"]['a_materno'] ?>
					</td>
				</tr>

			<?php endforeach ?>
			
		</tbody>
	</table>
</div>