


<div class="row azul_4">

	<div class="input-field col m3 l2">
		<div id="eliminar_boton_imagenes">
			<a class="right bg_complementario btn-floating waves-effect waves-light" id="agregar_imagen">
				<i class="material-icons">add</i>
			</a>
		</div>
	</div>

	<div class="file-field input-field col m6">
		<div class="btn">
			<span>Imagen</span>
			<input name="data[Comunicado][Imagenes][<?php echo $this->request->data["cont_imagenes"] ?>]" type="file">
		</div>
		<div class="file-path-wrapper">
			<input class="file-path" type="text">
			<label id="imageneNum<?php echo $this->request->data["cont_imagenes"] ?>" class="error validation_label validation_imagene" for="data[Comunicado][Imagenes][<?php echo $this->request->data["cont_imagenes"] ?>]">* Requerido</label>
		</div>
	</div>
	
</div>