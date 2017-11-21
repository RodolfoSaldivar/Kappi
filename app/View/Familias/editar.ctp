

<?php $this->assign('title', 'Editar Familia - Kappi'); ?>

<?php $this->set('titulo_vista', "Editar Familia"); ?>
<?php $this->set("breadcrumbs",
	'<a href="/familias" class="breadcrumb">Familias</a>
	<a class="breadcrumb">Editar</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_familias").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('Familia'); ?>

	<?php echo $this->Form->hidden('id', array('value' => $familia['Familia']['id'])); ?>
	
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row margin_nada azul_4">
				<div style="padding:10px 0 0 20px; font-size:30px;">
					Nombre
				</div>
			</div>

			<!-- Area de datos -->
			<div class="row" style="padding-bottom:10px;">

				<div class="col s6 padding_top">

					<div class="input-field">
						<i class="material-icons prefix">vpn_key</i>
						<label for="FamiliaIdentificador">
							Identificador
							<label id="FamiliaIdentificador-error" class="error validation_label" for="FamiliaIdentificador"></label>
						</label>
						<input name="data[Familia][identificador]" type="text" id="FamiliaIdentificador" aria-required="true" class="error" aria-invalid="true" value="<?php echo $familia["Familia"]["identificador"] ?>">
					</div>

				</div>

				<div class="col s6 padding_top">

					<div class="input-field">
						<i class="material-icons prefix">format_color_text</i>
						<label for="FamiliaNombre">
							Nombre
							<label id="FamiliaNombre-error" class="error validation_label" for="FamiliaNombre"></label>
						</label>
						<input name="data[Familia][nombre]" type="text" id="FamiliaNombre" aria-required="true" class="error" aria-invalid="true" value="<?php echo $familia["Familia"]["nombre"] ?>">
					</div>

				</div>

			</div>
		</div>
	</div>

	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row margin_nada azul_4">
				<div style="padding:10px 0 0 20px; font-size:30px;">
					Miembros
					<a class="bg_complementario btn-floating waves-effect waves-light tooltipped" data-position="right" data-delay="50" data-tooltip="Agregar Miembro" id="agregar_miembro">
						<i class="material-icons">add</i>
					</a>
				</div>
			</div>

			<div class="row margin_nada">

				<div class="col s8 padding_top" id="donde_agregar_miembros">
					
					<?php foreach ($familia['User'] as $key => $miembro): ?>

						<div class="row margin_nada" id="quitar_miembro_<?php echo $key ?>">
							<div class="miembro_existente input-field col s10">

								<input name="data[Miembros][<?php echo $key ?>]" id="real_miembro_<?php echo $key ?>" type="hidden" value="<?php echo $miembro["id"] ?>"/>

								<i class="material-icons prefix">person</i>
								<select id="miembro<?php echo $key ?>" disabled>

									<option disabled selected><?php echo $miembro["a_paterno"]." ".$miembro["a_materno"].", ".$miembro["nombre"] ?></option>

								</select>

							</div>
				
							<div class="col s2">
								<div class="col s1">
									<a class="input-field bg_complementario btn-floating waves-effect waves-light" id="miembro_eliminar_<?php echo $key ?>">
										<i class="material-icons">remove</i>
									</a>
								</div>
							</div>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).on("click", "#miembro_eliminar_<?php echo $key ?>", function()
	{
		$('#quitar_miembro_<?php echo $key ?>').remove();
	});

<?php $this->Html->scriptEnd(); ?>
						</div>

					<?php endforeach ?>

				</div>

			</div>

			<div class="row margin_nada">
				<div class="col m12">
					<button class="right btn waves-effect waves-light" type="submit" name="action">
						Editar Familia
						<i class="material-icons right">send</i>
					</button>
				</div>
			</div>
			<br>

		</div>
	</div>

<?php echo $this->Form->end(); ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$('.tooltipped').tooltip({delay: 50});
		$('select').material_select();
	});

	$('#FamiliaEditarForm').submit(function(event)
	{
		$(".miembro_existente").each(function()
		{
			if($('select option:selected', this).val() == "nada")
			{
				$("label", this).css("display", "initial");
				event.preventDefault();
			}
		});
			
	});

	$.validator.messages.required = '*Requerido';
	$.validator.messages.alphanumeric = '*Solo letras y números';

	$('#FamiliaEditarForm').validate({
		rules: {
			'data[Familia][identificador]': {
				required: true,
				alphanumeric: true
			},
			'data[Familia][nombre]': {
				required: true,
				alphanumeric: true
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	var contador = <?php echo $contador ?>;

	$(document).on("click", "#agregar_miembro", function()
	{
		btnAgregar();
	});

	$(document).on("change", "#miembro"+contador, function()
	{
		var nombre = $(this).val();
		$("#Miembro"+contador+"-error").css("display", "none");
		$('#miembro'+contador).attr("disabled", true);
		$('#miembro'+contador).material_select();
		$('#real_miembro_'+contador).val(nombre);
	});

	function btnAgregar()
	{
		var usuario = $('#miembro'+contador+' option:selected').val();

		if(usuario == "nada")
		{
			$("#Miembro"+contador+"-error").css("display", "initial");
		}
		else
			agregarMiembro(usuario);
	}

	function agregarMiembro(usuario)
	{
		contador++;

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Familias', 'action' => 'agregar_miembro']); ?>',
	        success: function(response)
	        {
	            $("#donde_agregar_miembros").append(response);
	            $(document).ready(function()
	            {
					$('#miembro'+contador).material_select();
				});

				$(document).on("change", "#miembro"+contador, function()
				{
					var nombre = $(this).val();
					$("#Miembro"+contador+"-error").css("display", "none");
					$("#miembro_eliminar_"+contador).css("display", "none");
					$('#miembro'+contador).attr("disabled", true);
					$('#miembro'+contador).material_select();
					$('#real_miembro_'+contador).val(nombre);
				});

				$(document).on("click", "#miembro_eliminar_"+contador, function()
				{
					$('#quitar_miembro_'+contador).remove();
					contador--;
				});
	        },
	        data: {
	        	contador: contador,
	        	usuario: usuario
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>