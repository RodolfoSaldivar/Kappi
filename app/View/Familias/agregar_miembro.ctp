

<div class="row margin_nada" id="quitar_miembro_<?php echo $contador ?>">
	<div class="miembro_existente input-field col s10">

		<input name="data[Miembros][<?php echo $contador ?>]" id="real_miembro_<?php echo $contador ?>" type="hidden" />
		<i class="material-icons prefix">person</i>
		<select id="miembro<?php echo $contador ?>" name="data[Miembros][<?php echo $contador ?>]">

			<option value="nada" disabled selected>Miembro</option>

			<?php foreach ($usuarios as $id => $nombre): ?>
				<option value="<?php echo $id ?>"><?php echo $nombre ?></option>
			<?php endforeach ?>

		</select>
		<label id="Miembro<?php echo $contador ?>-error" class="validation_label" for="Miembro<?php echo $contador ?>" style="position:absolute!important;">*Requerido</label>

	</div>
	<div class="col s2">
		<div class="col s1">
			<a class="input-field bg_complementario btn-floating waves-effect waves-light" id="miembro_eliminar_<?php echo $contador ?>">
				<i class="material-icons">remove</i>
			</a>
		</div>
	</div>
</div>