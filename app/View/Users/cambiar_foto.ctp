

<?php $this->assign('title', 'Cambiar Foto - Kappi');  ?>

<?php $this->set('titulo_vista', 'Cambiar Foto'); ?>


<?php echo $this->Form->create('User', array(
	'type' => 'file'
)); ?>

	<?php echo $this->Form->hidden('id', array('value' => $usuario['User']['id'])); ?>
	
	<div class="bg_blanco">
		<div class="contenedor">

			<!-- Area de datos -->
			<div class="row">

				<!-- Segunda columna; razon y logo -->
				<div class="col s12 m6 padding_top padding_bottom">

					<div class="file-field input-field">
						<div class="btn">
							<span>Foto</span>
							<input name="data[User][foto]" type="file">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
							<label id="data[User][foto]-error" class="error validation_label validation_image" for="data[User][foto]"></label>
						</div>
					</div>

					<div>
						<img class="circle responsive-img materialboxed" src="<?php echo $usuario['Imagene']['ruta'] ?><?php echo $usuario['Imagene']['nombre'] ?>">
					</div>

				</div>
			</div>

			<div class="row margin_nada">
				<div class="col m12">	
					<button class="right btn waves-effect waves-light" type="submit" name="action">
						Cambiar Foto
						<i class="material-icons right">send</i>
					</button>
				</div>
			</div>
			<br>

		</div>
	</div>

<?php echo $this->Form->end(); ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).on("change", "#todas_familias", function()
	{
		if ($('#todas_familias').val() != "nada")
			filtrarResultado();
		else
			$('#filtro_a_cambiar').children().remove();
	});

	function filtrarResultado()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Users', 'action' => 'miembros_de_familia']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar').replaceWith(response);

	            $(document).ready(function(){
					$('.materialboxed').materialbox();
				});
	        },
	        data: {
	        	familia: $('#todas_familias').val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$.validator.messages.required = '*Requerido';

	$('#UserCambiarFotoForm').validate({
		rules: {
			'data[User][foto]': {
				required: true
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>