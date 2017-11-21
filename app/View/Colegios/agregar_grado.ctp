

<div class="row margin_nada">
	<div class="col s1 offset-s1 input-field">
		<i class="small flecha_volteada material-icons">subdirectory_arrow_left</i>
	</div>

	<div class="col s4 input-field grado_existente">
		<i class="material-icons prefix">bookmark_border</i>
		<label for="ColegioNivel<?php echo $nivel ?>Grado<?php echo $grado ?>Nombre">
			Grado&nbsp;
			<label id="ColegioNivel<?php echo $nivel ?>Grado<?php echo $grado ?>Nombre-error" class="error validation_label" for="ColegioNivel<?php echo $nivel ?>Grado<?php echo $grado ?>Nombre"></label>
		</label>
		<input name="data[Colegio][nivel][<?php echo $nivel ?>][grado][<?php echo $grado ?>][nombre]" type="text" id="ColegioNivel<?php echo $nivel ?>Grado<?php echo $grado ?>Nombre" aria-required="true" class="error" aria-invalid="true">
	</div>

	<div class="col s4 input-field">
		<i class="material-icons prefix">vpn_key</i>
		<label for="ColegioNivel<?php echo $nivel ?>Grado<?php echo $grado ?>Identificador">
			Identificador&nbsp;
			<label id="ColegioNivel<?php echo $nivel ?>Grado<?php echo $grado ?>Identificador-error" class="error validation_label" for="ColegioNivel<?php echo $nivel ?>Grado<?php echo $grado ?>Identificador"></label>
		</label>
		<input name="data[Colegio][nivel][<?php echo $nivel ?>][grado][<?php echo $grado ?>][identificador]" type="text" id="ColegioNivel<?php echo $nivel ?>Grado<?php echo $grado ?>Identificador" aria-required="true" class="error" aria-invalid="true">
	</div>

	<div class="col s1">
		<a class="input-field bg_complementario_claro btn-floating waves-effect waves-light" id="nivel_<?php echo $nivel ?>_eliminar_grado_<?php echo $grado ?>">
			<i class="material-icons">remove</i>
		</a>
	</div>
</div>