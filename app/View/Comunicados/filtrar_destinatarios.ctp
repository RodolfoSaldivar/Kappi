

<tbody id="filtro_a_cambiar">
	<?php foreach ($destinatarios as $key => $destinatario): ?>
		<tr>
			<td>
				<?php echo $destinatario[0]["a_paterno"] ?>
				<?php echo $destinatario[0]["a_materno"].", " ?>
				<?php echo $destinatario[0]["nombre"] ?>
			</td>
			<td><?php echo $destinatario[0]["tipo"] ?></td>
			<td>
				<?php if ($destinatario[0]["visto"] == 0): ?>
					No
				<?php else: ?>
					<?php echo $destinatario[0]["fecha_visto"]." / ".$destinatario[0]["visto"] ?>
				<?php endif ?>
			</td>
			<td>
				<?php if ($destinatario[0]["firmado"] == 0): ?>
					No
				<?php endif ?>
				<?php if ($destinatario[0]["firmado"] == 1): ?>
					<?php echo $destinatario[0]["fecha_firmado"] ?>
				<?php endif ?>
				<?php if ($destinatario[0]["firmado"] == 2): ?>
					---
				<?php endif ?>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>