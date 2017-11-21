

<div id="salones_poner">
	<select id="salon_escogido" name="salon">

		<?php if (!empty($salones[0])): ?>
			
			<option value="todos" selected="">Todos los salones</option>

			<?php foreach ($salones as $key => $salon): ?>

				<option value="<?php echo $salon["Salone"]['encriptada'] ?>"><?php echo $salon["Salone"]['nombre'] ?></option>

			<?php endforeach ?>

		<?php else: ?>

			<option value="nada" disabled="">No hay salones</option>

		<?php endif ?>
			
	</select>
</div>