

<tbody id="filtro_a_cambiar">
	<?php foreach ($salones as $key => $salon): ?>
		<tr onclick="window.location = '/salones/datos/<?php echo $salon["Salone"]["encriptada"] ?>'">
			<td><?php echo $salon["Nivele"]["nombre"] ?></td>
			<td><?php echo $salon["Grado"]["nombre"] ?></td>
			<td>
				<a href="/salones/datos/<?php echo $salon["Salone"]["encriptada"] ?>">
					<?php echo $salon["Salone"]["nombre"] ?></td>
				</a>
			<td>
				<?php echo $salon["User"]["a_paterno"] ?>
				<?php echo $salon["User"]["a_materno"].", " ?>
				<?php echo $salon["User"]["nombre"] ?>
			</td>
			<td class="center ver_editar_acciones">
				<!-- <a href="/salones/datos/<?php echo $salon["Salone"]["encriptada"] ?>">
					<i class="material-icons">visibility</i>
				</a>
				<br class="hide-on-med-and-down"> -->
				<a class="hide-on-small-only" href="/salones/editar/<?php echo $salon["Salone"]["encriptada"] ?>">
					<i class="material-icons">edit</i>
				</a>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>