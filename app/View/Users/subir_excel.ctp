

<?php $this->assign('title', 'Agregar Usuarios - Kappi');  ?>

<?php $this->set('titulo_vista', 'Agregar Usuarios'); ?>
<?php $this->set("breadcrumbs",
	'<a href="'.$this->Session->read("una_pag_antes").'" class="breadcrumb">Usuarios</a>
	<a class="breadcrumb">Agregar</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_usuarios").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('User', array(
	'type' => 'file'
)); ?>
	
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row margin_nada azul_4">
				<div style="padding:10px 0 0 20px; font-size:30px;">
					Plantilla
				</div>
			</div>

			<div class="row margin_nada">
				<div style="padding:10px 0 0 20px; font-size:20px;">
					1 - Descargar el archivo Excel a continuación.<br>
					2 - LLenar los campos que se piden (el archivo cuenta con comentarios de ayuda).<br>
					3 - Escoger el mismo archivo con los datos llenos.<br>
					4 - Enviar el archivo.<br><br>
					<span class="red-text">
						*Máximo 1,000 registros por plantilla.<br>
						*Tardará 2 minutos por cada 1,000 registros.<br>
					</span>
				</div>
			</div>


			<div class="row">

				<div class="col s6 padding_top">
					<div class="input-field">
						<a href="/users/descargar_excel/plantillaUsuarios" class="waves-effect waves-light btn">
							<i class="material-icons right white-text">file_download</i>
							Descargar
						</a>
					</div>
				</div>

				<div class="col s6 padding_top">
					<div class="file-field input-field">

						<div class="btn">
							<span>Archivo</span>
							<input name="data[archivo]" type="file">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
							<label id="data[archivo]-error" class="error validation_label validation_image" for="data[archivo]"></label>
						</div>
					</div>
				</div>

			</div>


			<div class="row padding_bottom margin_nada hide" id="aparece_loading">
				<div class="col s3 offset-s9 center">
					<div class="preloader-wrapper big active">
						<div class="spinner-layer azul_5">
							<div class="circle-clipper left"><div class="circle"></div></div>
							<div class="gap-patch"><div class="circle"></div></div>
							<div class="circle-clipper right"><div class="circle"></div></div>
						</div>
					</div>
					<br>
					<span class="enviando">
						Enviando Archivo
					</span>
				</div>
			</div>

			<div class="row margin_nada" id="desaparece_loading">
				<div class="col m12">	
					<button class="right btn waves-effect waves-light" type="submit" name="action">
						Enviar Archivo
						<i class="material-icons right">send</i>
					</button>
				</div>
			</div>
			<br>

		</div>
	</div>

<?php echo $this->Form->end(); ?>




<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$.validator.messages.required = '*Requerido';

	$('#UserSubirExcelForm').validate({
		rules: {
			'data[archivo]': {
				required: true
			}
		}
	});

	$('#UserSubirExcelForm').submit(function(event)
	{
		if ($('#UserSubirExcelForm').valid())
		{
			$("#desaparece_loading").addClass("hide");
			$("#aparece_loading").removeClass("hide");
		}
	});

<?php $this->Html->scriptEnd(); ?>