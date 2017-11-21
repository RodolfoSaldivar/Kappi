


<tbody id="filtro_a_cambiar">
	<?php foreach ($ciclos as $key => $ciclo): ?>
		<tr>
			<td onclick="window.location = 'salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>'">
				<a href="salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>">
					<?php echo $ciclo["Ciclo"]["nombre"] ?>
				</a>
			</td>
			<td onclick="window.location = 'salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>'"><?php echo $ciclo["Ciclo"]["fecha_inicio"] ?></td>
			<td onclick="window.location = 'salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>'"><?php echo $ciclo["Ciclo"]["fecha_fin"] ?></td>
			<td class="center" id="poner_switch_<?php echo $ciclo["Ciclo"]["encriptada"] ?>">
				<div class="switch">
					<label>
						No
						<input <?php if ($ciclo["Ciclo"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Activo][<?php echo $ciclo["Ciclo"]["encriptada"] ?>]' id='Activo<?php echo $ciclo["Ciclo"]["encriptada"] ?>' value='<?php echo $ciclo["Ciclo"]["activo"] ?>'>
						<span class="lever"></span>
						Si
					</label>
				</div>
			</td>
			<td class="center ver_editar_acciones">
				<!-- <a href="salones_index/<?php echo $ciclo["Ciclo"]["encriptada"] ?>">
					<i class="material-icons">visibility</i>
				</a>
				<br class="hide-on-med-and-down"> -->
				<a class="hide-on-small-only" href="ciclos/editar/<?php echo $ciclo["Ciclo"]["encriptada"] ?>">
					<i class="material-icons">edit</i>
				</a>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>