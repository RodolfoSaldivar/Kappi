


<div class="row azul_4">

	<div class="input-field col m3 l2">
		<div id="eliminar_boton_pdf">
			<a class="right bg_complementario btn-floating waves-effect waves-light" id="agregar_pdf">
				<i class="material-icons">add</i>
			</a>
		</div>
	</div>

	<div class="file-field input-field col m6">
		<div class="btn">
			<span>PDF</span>
			<input name="data[Comunicado][PDF][<?php echo $this->request->data["cont_pdf"] ?>]" type="file">
		</div>
		<div class="file-path-wrapper">
			<input class="file-path" type="text">
			<label id="comunicadoNum<?php echo $this->request->data["cont_pdf"] ?>" class="error validation_label validation_archivo" for="data[Comunicado][PDF][<?php echo $this->request->data["cont_pdf"] ?>]">* Requerido</label>
		</div>
	</div>
	
</div>