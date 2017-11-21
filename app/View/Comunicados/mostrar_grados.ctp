

<div id="grados_poner">
	<select id="grado_escogido" name="grado">
		<option value="todos" selected="">Todos los grados</option>

		<?php foreach ($grados as $key => $grado): ?>

			<option value="<?php echo $grado["Grado"]['encriptada'] ?>"><?php echo $grado["Grado"]['nombre'] ?></option>

		<?php endforeach ?>
	</select>
</div>