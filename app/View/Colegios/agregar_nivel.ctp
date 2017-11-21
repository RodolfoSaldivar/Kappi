

<div class="nivel_existente">
	<div class="divider_grueso"></div>

	<div class="row margin_nada mayor_margin">

		<div class="input-field col s7">
			<i class="material-icons prefix">book</i>
			<label for="ColegioNivel<?php echo $nivel ?>Nombre">
				Nivel&nbsp;
				<label id="ColegioNivel<?php echo $nivel ?>Nombre-error" class="error validation_label" for="ColegioNivel<?php echo $nivel ?>Nombre"></label>
			</label>
			<input name="data[Colegio][nivel][<?php echo $nivel ?>][nombre]" type="text" id="ColegioNivel<?php echo $nivel ?>Nombre" aria-required="true" class="error" aria-invalid="true">
		</div>

		<div class="col s1 quitar_nivel">
			<a class="input-field bg_complementario btn-floating waves-effect waves-light" id="eliminar_nivel_<?php echo $nivel ?>">
				<i class="material-icons">remove</i>
			</a>
		</div>

	</div>

	<div id="donde_agregar_grados_<?php echo $nivel ?>" class="row margin_nada">

		<div class="row margin_nada">
			<div class="col s1 offset-s1 input-field">
				<i class="small flecha_volteada material-icons">subdirectory_arrow_left</i>
			</div>

			<div class="col s4 input-field grado_existente">
				<i class="material-icons prefix">bookmark_border</i>
				<label for="ColegioNivel<?php echo $nivel ?>Grado1Nombre">
					Grado&nbsp;
					<label id="ColegioNivel<?php echo $nivel ?>Grado1Nombre-error" class="error validation_label" for="ColegioNivel<?php echo $nivel ?>Grado1Nombre"></label>
				</label>
				<input name="data[Colegio][nivel][<?php echo $nivel ?>][grado][1][nombre]" type="text" id="ColegioNivel<?php echo $nivel ?>Grado1Nombre" aria-required="true" class="error" aria-invalid="true">
			</div>

			<div class="col s4 input-field">
				<i class="material-icons prefix">vpn_key</i>
				<label for="ColegioNivel<?php echo $nivel ?>Grado1Identificador">
					Identificador&nbsp;
					<label id="ColegioNivel<?php echo $nivel ?>Grado1Identificador-error" class="error validation_label" for="ColegioNivel<?php echo $nivel ?>Grado1Identificador"></label>
				</label>
				<input name="data[Colegio][nivel][<?php echo $nivel ?>][grado][1][identificador]" type="text" id="ColegioNivel<?php echo $nivel ?>Grado1Identificador" aria-required="true" class="error" aria-invalid="true">
			</div>

		</div>
	</div>

	<div class="row">
		<div class="col s11">
			<a class="right bg_complementario_claro btn-floating waves-effect waves-light tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar Grado" id="agregar_grado_<?php echo $nivel ?>">
				<i class="material-icons">add</i>
			</a>
		</div>
	</div>
</div>