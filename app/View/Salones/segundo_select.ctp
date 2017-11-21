

<div id="segundoSelect">	
	<select id="nivelYgrado" class="col l2 m3">
		<option value="nada" disabled selected><?php echo $grado["Nivele"]["nombre"] ?></option>

		<?php if (!empty($grado["Antes"])): ?>
			<option value="<?php echo $grado["Antes"]["encriptada"] ?>"><?php echo $grado["Antes"]["nombre"] ?></option>
		<?php endif ?>

		<option value="<?php echo $grado["Grado"]["encriptada"] ?>"><?php echo $grado["Grado"]["nombre"] ?></option>

		<option value="nuevos">Nuevo ingreso</option>

	</select>
</div>