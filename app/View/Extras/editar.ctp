

<?php $this->assign('title', 'Editar '.$extra_tipo.' - Kappi'); ?>

<?php $this->set('titulo_vista', 'Editar '.$extra_tipo); ?>
<?php $this->set("breadcrumbs",
	'<a href="/extras" class="breadcrumb">Extras</a>
	<a href="/extras" class="breadcrumb">'.$extra_tipo.'</a>
	<a class="breadcrumb">Editar</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_extras").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('Extra', array(
	'type' => 'file'
)); ?>

	<?php echo $this->Form->hidden('id', array('value' => $extra['Extra']['id'])); ?>
	
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row margin_nada azul_4">
				<div style="padding:10px 0 0 20px; font-size:30px;">
					<?php echo $extra_tipo ?>
				</div>
			</div>

			<!-- Area de datos -->
			<div class="row" style="padding-bottom:10px;">

				<div class="col s6 padding_top">

					<div class="input-field">
						<i class="material-icons prefix">format_color_text</i>
						<label for="ExtraDescripcion">
							Descripción
							<label id="ExtraDescripcion-error" class="error validation_label" for="ExtraDescripcion"></label>
						</label>

						<textarea name="data[Extra][descripcion]" id="ExtraDescripcion" class="materialize-textarea error"></textarea>

					</div>

				</div>

				<div class="col s6 padding_top">

					<div class="file-field input-field">
						<div class="btn">
							<span>Imagen</span>
							<input name="data[Extra][imagen]" type="file">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
							<label id="data[Extra][imagen]-error" class="error validation_label validation_image" for="data[Extra][imagen]"></label>
						</div>
					</div>

					<div>
						<img class="responsive-img materialboxed" src="<?php echo $extra['Imagene']['ruta'] ?><?php echo $extra['Imagene']['nombre'] ?>">
					</div>

				</div>
			</div>

			<div class="row margin_nada">
				<div class="col m12">
					<button class="right btn waves-effect waves-light" type="submit" name="action">
						Editar <?php echo $extra_tipo ?>
						<i class="material-icons right">send</i>
					</button>
				</div>
			</div>
			<br>

		</div>
	</div>

<?php echo $this->Form->end(); ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#ExtraDescripcion').val('<?php echo $extra["Extra"]["descripcion"] ?>');
	$('#ExtraDescripcion').trigger('autoresize');

	$.validator.messages.required = '*Requerido';
	$.validator.messages.alphanumeric = '*Solo letras y números';

	$('#ExtraAgregarForm').validate({
		rules: {
			'data[Extra][descripcion]': {
				required: true,
				alphanumeric: true
			},
			'data[Extra][imagen]': {
				required: true
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>