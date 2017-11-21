

<tbody id="filtro_a_cambiar">
	<?php foreach ($familias as $key => $familia): ?>
		<tr onclick="window.location = '/familias/datos/<?php echo $familia[0]["encriptada"] ?>'">
			<td>
				<?php echo $familia[0]["nombre"] ?>
			</td>
			<td>
				<?php echo $familia[0]["identificador"] ?>
			</td>
			<td>
				<?php foreach ($familia["User"] as $key => $miembro): ?>
					<?php if ($key != 0): ?>
						<br>
					<?php endif ?>
					<?php echo $miembro['a_paterno'] ?>
					<?php echo $miembro['a_materno'].", " ?>
					<?php echo $miembro['nombre'] ?>
				<?php endforeach ?>
			</td>
			<td class="center ver_editar_acciones">
				<!-- <a href="/familias/datos/<?php echo $familia[0]["encriptada"] ?>">
					<i class="material-icons">visibility</i>
				</a>
				<br class="hide-on-small-only"> -->
				<a class="hide-on-small-only" href="/familias/editar/<?php echo $familia[0]["encriptada"] ?>">
					<i class="material-icons">edit</i>
				</a>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>