

<?php $this->assign('title', 'Agregar Colegio - Kappi'); ?>

<?php $this->set('titulo_vista', "Agregar Colegio"); ?>
<?php $this->set("breadcrumbs",
	'<a href="/colegios" class="breadcrumb">Colegios</a>
	<a class="breadcrumb">Agregar</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_colegios").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('Colegio', array(
	'type' => 'file'
)); ?>
	
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row margin_nada azul_4">
				<div style="padding:10px 0 0 20px; font-size:30px;">
					Datos
				</div>
			</div>

			<!-- Area de datos -->
			<div class="row">

				<!-- Primer columna; comercial, corto y telefono -->
				<div class="col s6 padding_top padding_bottom">

					<div class="input-field">
						<i class="material-icons prefix">format_color_text</i>
						<label for="ColegioNombreComercial">
							Nombre Comercial
							<label id="ColegioNombreComercial-error" class="error validation_label" for="ColegioNombreComercial"></label>
						</label>
						<input name="data[Colegio][nombre_comercial]" type="text" id="ColegioNombreComercial" aria-required="true" class="error" aria-invalid="true">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">text_format</i>
						<label for="ColegioNombreCorto">
							Nombre Corto
							<label id="ColegioNombreCorto-error" class="error validation_label" for="ColegioNombreCorto"></label>
						</label>
						<input name="data[Colegio][nombre_corto]" type="text" id="ColegioNombreCorto" aria-required="true" class="error" aria-invalid="true">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">phone</i>
						<label for="ColegioTelefono">
							Teléfono
							<label id="ColegioTelefono-error" class="error validation_label" for="ColegioTelefono"></label>
						</label>
						<input name="data[Colegio][telefono]" type="text" id="ColegioTelefono" aria-required="true" class="error" aria-invalid="true">
					</div>

				</div>
			
				<!-- Segunda columna; razon y logo -->
				<div class="col s6 padding_top padding_bottom">

					<div class="input-field">
						<i class="material-icons prefix">fingerprint</i>
						<label for="ColegioRazonSocial">
							Razón Social
							<label id="ColegioRazonSocial-error" class="error validation_label" for="ColegioRazonSocial"></label>
						</label>
						<input name="data[Colegio][razon_social]" type="text" id="ColegioRazonSocial" aria-required="true" class="error" aria-invalid="true">
					</div>

					<div class="file-field input-field">
						<div class="btn">
							<span>Logo</span>
							<input name="data[Colegio][logo]" type="file">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
							<label id="data[Colegio][logo]-error" class="error validation_label validation_image" for="data[Colegio][logo]"></label>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>

	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row margin_nada azul_4">
				<div style="padding:10px 0 0 20px; font-size:30px;">
					Niveles
					<a class="bg_complementario btn-floating waves-effect waves-light tooltipped" data-position="right" data-delay="50" data-tooltip="Agregar Nivel" id="agregar_nivel">
						<i class="material-icons">add</i>
					</a>
				</div>
			</div>

			<div class="row margin_nada">

				<div class="col s12 padding_top" id="donde_agregar_niveles">

					<div class="nivel_existente">

						<div class="row margin_nada">

							<div class="input-field col s7">
								<i class="material-icons prefix">book</i>
								<label for="ColegioNivel1Nombre">
									Nivel
									<label id="ColegioNivel1Nombre-error" class="error validation_label" for="ColegioNivel1Nombre"></label>
								</label>
								<input name="data[Colegio][nivel][1][nombre]" type="text" id="ColegioNivel1Nombre" aria-required="true" class="error" aria-invalid="true">
							</div>

						</div>

						<div id="donde_agregar_grados_1" class="row margin_nada">

							<div class="row margin_nada">
								<div class="col s1 offset-s1 input-field">
									<i class="small flecha_volteada material-icons">subdirectory_arrow_left</i>
								</div>

								<div class="col s4 input-field grado_existente">
									<i class="material-icons prefix">bookmark_border</i>
									<label for="ColegioNivel1Grado1Nombre">
										Grado
										<label id="ColegioNivel1Grado1Nombre-error" class="error validation_label" for="ColegioNivel1Grado1Nombre"></label>
									</label>
									<input name="data[Colegio][nivel][1][grado][1][nombre]" type="text" id="ColegioNivel1Grado1Nombre" aria-required="true" class="error" aria-invalid="true">
								</div>

								<div class="col s4 input-field">
									<i class="material-icons prefix">vpn_key</i>
									<label for="ColegioNivel1Grado1Identificador">
										Identificador
										<label id="ColegioNivel1Grado1Identificador-error" class="error validation_label" for="ColegioNivel1Grado1Identificador"></label>
									</label>
									<input name="data[Colegio][nivel][1][grado][1][identificador]" type="text" id="ColegioNivel1Grado1Identificador" aria-required="true" class="error" aria-invalid="true">
								</div>
							</div>	

						</div>

						<div class="row">
							<div class="col s11">
								<a class="right bg_complementario_claro btn-floating waves-effect waves-light tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar Grado" id="agregar_grado_1">
									<i class="material-icons">add</i>
								</a>
							</div>
						</div>
						
					</div>

				</div>
			</div>

			<div class="row margin_nada">
				<div class="col m12">	
					<button class="right btn waves-effect waves-light" type="submit" name="action">
						Agregar Colegio
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
	$.validator.messages.alphanumeric = '*Solo letras y números';

	$('#ColegioAgregarForm').validate({
		rules: {
			'data[Colegio][nombre_comercial]': {
				required: true,
				alphanumeric: true
			},
			'data[Colegio][nombre_corto]': {
				required: true,
				alphanumeric: true
			},
			'data[Colegio][telefono]': {
				required: true,
				alphanumeric: true
			},
			'data[Colegio][razon_social]': {
				required: true,
				alphanumeric: true
			},
			'data[Colegio][logo]': {
				required: true
			},
			'data[Colegio][nivel][1][nombre]': {
				required: true,
				alphanumeric: true
			},
			'data[Colegio][nivel][1][grado][1][nombre]': {
				required: true,
				alphanumeric: true
			},
			'data[Colegio][nivel][1][grado][1][identificador]': {
				required: true,
				alphanumeric: true
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function()
	{
		$('.tooltipped').tooltip({delay: 50});
		var niveles_existentes = 1;
		var grados_existentes = 1;

	    $("#agregar_nivel").click(function()
	    {
	    	niveles_existentes++;
	        agregarNivel(niveles_existentes);
	    });

	    $("#agregar_grado_1").click(function()
	    {
	    	agregarGrado(1);
	    });

	    function agregarNivel(nivel)
	    {
		    if($("#donde_agregar_niveles .nivel_existente").length <= 5)
	        {	
	        	if($("#donde_agregar_niveles .nivel_existente").length == 5)
	        		$("#agregar_nivel").addClass("hide");

	        	$.ajax({
			        type:'POST',
			        cache: false,
			        url: '<?= Router::Url(['controller' => 'colegios', 'action' => 'agregar_nivel']); ?>',
			        success: function(response)
			        {
			        	$("#donde_agregar_niveles").append(response);

			        	$('#agregar_grado_'+nivel).tooltip({delay: 50});

					    $("#eliminar_nivel_"+nivel).click(function()
					    {
					    	$(this).parent().parent().parent().remove();
					    	$("#agregar_nivel").removeClass("hide");
					    });

				    	$("#agregar_grado_"+nivel).click(function()
					    {
					    	agregarGrado(nivel);
					    });

					    $('#ColegioNivel'+nivel+'Nombre').rules("add", { required:true, alphanumeric:true });
					    $('#ColegioNivel'+nivel+'Grado1Nombre').rules("add", { required:true, alphanumeric:true });
					    $('#ColegioNivel'+nivel+'Grado1Identificador').rules("add", { required:true, alphanumeric:true });

			        },
			        data: {
			        	nivel: nivel
			        }
			    });
		    }
	    }

	    function agregarGrado(nivel)
	    {
	    	grados_existentes++;

	    	if($("#donde_agregar_grados_"+nivel+" .grado_existente").length <= 9)
	        {	
	        	if($("#donde_agregar_grados_"+nivel+" .grado_existente").length == 9)
	        		$("#agregar_grado_"+nivel).addClass("hide");

	        	$.ajax({
			        type:'POST',
			        cache: false,
			        url: '<?= Router::Url(['controller' => 'colegios', 'action' => 'agregar_grado']); ?>',
			        success: function(response)
			        {
			        	$("#donde_agregar_grados_"+nivel).append(response);

					    $("#nivel_"+nivel+"_eliminar_grado_"+grados_existentes).click(function()
					    {
					    	$(this).parent().parent().remove();
					    	$("#agregar_grado_"+nivel).removeClass("hide");
					    });

					    $('#ColegioNivel'+nivel+'Grado'+grados_existentes+'Nombre').rules("add", { required:true, alphanumeric:true });
					    $('#ColegioNivel'+nivel+'Grado'+grados_existentes+'Identificador').rules("add", { required:true, alphanumeric:true });

			        },
			        data: {
			        	nivel: nivel,
			        	grado: grados_existentes
			        }
			    });
		    }
	    }
	});

<?php $this->Html->scriptEnd(); ?>