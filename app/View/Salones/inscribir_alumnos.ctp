

<div id="todosAlumnosInscritos">

	<table>
		<tbody>

			<?php foreach ($inscritos as $key => $inscrito): ?>
				
				<tr id="inscritos_<?php echo $inscrito["User"]["id"] ?>">
					<input type="hidden" value="<?php echo $inscrito["User"]["id"] ?>">
					<td width="130" class="hide-on-med-and-down" style="padding-right:30px;">
						<?php if (empty($inscrito['User']['imagene_id'])): ?>
							<img class="circle left responsive-img materialboxed" src="/img/default_user.png">
						<?php else: ?>
							<img class="circle left responsive-img materialboxed" src="<?php echo $inscrito['Imagene']['ruta'] ?><?php echo $inscrito['Imagene']['nombre'] ?>">
						<?php endif ?>
					</td>
					<td>
						<?php echo $inscrito["User"]['a_paterno'] ?>
						<?php echo $inscrito["User"]['a_materno'].", " ?>
						<?php echo $inscrito["User"]['nombre'] ?>
					</td>
					<td>
						<a class="quitar_inscribir waves-effect waves-light btn">
							Quitar
						</a>
					</td>
				</tr>

			<?php endforeach ?>
			
		</tbody>
	</table>

</div>