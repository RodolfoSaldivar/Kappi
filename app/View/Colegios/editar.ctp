

<?php $this->assign('title', 'Editar Colegio - Kappi');  ?>

<?php $this->set('titulo_vista', "Editar Colegio"); ?>
<?php $this->set("breadcrumbs",
	'<a href="/colegios" class="breadcrumb">Colegios</a>
	<a class="breadcrumb">Editar</a>
	<a class="breadcrumb">'.$colegio["Colegio"]["nombre_corto"].'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_colegios").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('Colegio', array(
	'type' => 'file'
)); ?>

	<?php echo $this->Form->hidden('id', array('value' => $colegio['Colegio']['id'])); ?>
	
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
						<input name="data[Colegio][nombre_comercial]" type="text" id="ColegioNombreComercial" aria-required="true" class="error" aria-invalid="true" value="<?php echo $colegio["Colegio"]["nombre_comercial"] ?>">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">text_format</i>
						<label for="ColegioNombreCorto">
							Nombre Corto
							<label id="ColegioNombreCorto-error" class="error validation_label" for="ColegioNombreCorto"></label>
						</label>
						<input name="data[Colegio][nombre_corto]" type="text" id="ColegioNombreCorto" aria-required="true" class="error" aria-invalid="true" value="<?php echo $colegio["Colegio"]["nombre_corto"] ?>">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">phone</i>
						<label for="ColegioTelefono">
							Teléfono
							<label id="ColegioTelefono-error" class="error validation_label" for="ColegioTelefono"></label>
						</label>
						<input name="data[Colegio][telefono]" type="text" id="ColegioTelefono" aria-required="true" class="error" aria-invalid="true" value="<?php echo $colegio["Colegio"]["telefono"] ?>">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">fingerprint</i>
						<label for="ColegioRazonSocial">
							Razón Social
							<label id="ColegioRazonSocial-error" class="error validation_label" for="ColegioRazonSocial"></label>
						</label>
						<input name="data[Colegio][razon_social]" type="text" id="ColegioRazonSocial" aria-required="true" class="error" aria-invalid="true" value="<?php echo $colegio["Colegio"]["razon_social"] ?>">
					</div>

				</div>
			
				<!-- Segunda columna; razon y logo -->
				<div class="col s6 padding_top padding_bottom">

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

					<div>
						<img class="responsive-img materialboxed" src="<?php echo $colegio['Imagene']['ruta'] ?><?php echo $colegio['Imagene']['nombre'] ?>">
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

					<?php foreach ($colegio["Nivele"] as $keyNivel => $nivel): ?>
						
						<div class="nivel_existente">

							<input type="hidden" name="data[Colegio][nivel][<?php echo $keyNivel ?>][id]" value="<?php echo $nivel["id"] ?>" id="ColegioId">

							<?php if ($keyNivel != 0): ?>
								<div class="divider_grueso"></div>
							<?php endif ?>

							<div class="row margin_nada <?php if ($keyNivel != 0) echo "mayor_margin" ?>">

								<div class="input-field col s7">
									<i class="material-icons prefix">book</i>
									<label for="ColegioNivel<?php echo $keyNivel ?>Nombre">
										Nivel
										<label id="ColegioNivel<?php echo $keyNivel ?>Nombre-error" class="error validation_label" for="ColegioNivel<?php echo $keyNivel ?>Nombre"></label>
									</label>
									<input name="data[Colegio][nivel][<?php echo $keyNivel ?>][nombre]" type="text" id="ColegioNivel<?php echo $keyNivel ?>Nombre" aria-required="true" class="error" aria-invalid="true" value="<?php echo $nivel["nombre"] ?>">
								</div>

							</div>

							<div id="donde_agregar_grados_<?php echo $keyNivel ?>" class="row margin_nada">

								<?php foreach ($nivel["grados"] as $keyGrado => $grado): ?>

									<div class="row margin_nada">

										<input type="hidden" name="data[Colegio][nivel][<?php echo $keyNivel ?>][grado][<?php echo $keyGrado ?>][id]" value="<?php echo $grado["Grado"]["id"] ?>">
									
										<div class="col s1 offset-s1 input-field">
											<i class="small flecha_volteada material-icons">subdirectory_arrow_left</i>
										</div>

										<div class="col s4 input-field grado_existente">
											<i class="material-icons prefix">bookmark_border</i>
											<label for="ColegioNivel<?php echo $keyNivel ?>Grado<?php echo $keyGrado ?>Nombre">
												Grado
												<label id="ColegioNivel<?php echo $keyNivel ?>Grado<?php echo $keyGrado ?>Nombre-error" class="error validation_label" for="ColegioNivel<?php echo $keyNivel ?>Grado<?php echo $keyGrado ?>Nombre"></label>
											</label>
											<input name="data[Colegio][nivel][<?php echo $keyNivel ?>][grado][<?php echo $keyGrado ?>][nombre]" type="text" id="ColegioNivel<?php echo $keyNivel ?>Grado<?php echo $keyGrado ?>Nombre" aria-required="true" class="error" aria-invalid="true" value="<?php echo $grado["Grado"]["nombre"] ?>">
										</div>

										<div class="col s4 input-field">
											<i class="material-icons prefix">vpn_key</i>
											<label for="ColegioNivel<?php echo $keyNivel ?>Grado<?php echo $keyGrado ?>Identificador">
												Identificador
												<label id="ColegioNivel<?php echo $keyNivel ?>Grado<?php echo $keyGrado ?>Identificador-error" class="error validation_label" for="ColegioNivel<?php echo $keyNivel ?>Grado<?php echo $keyGrado ?>Identificador"></label>
											</label>
											<input name="data[Colegio][nivel][<?php echo $keyNivel ?>][grado][<?php echo $keyGrado ?>][identificador]" type="text" id="ColegioNivel<?php echo $keyNivel ?>Grado<?php echo $keyGrado ?>Identificador" aria-required="true" class="error" aria-invalid="true" value="<?php echo $grado["Grado"]["identificador"] ?>">
										</div>	

									</div>

								<?php endforeach ?>	
									
							</div>

							<div class="row">
								<div class="col s11">
									<a class="right bg_complementario_claro btn-floating waves-effect waves-light tooltipped" data-position="left" data-delay="50" data-tooltip="Agregar Grado" id="agregar_grado_<?php echo $keyNivel ?>">
										<i class="material-icons">add</i>
									</a>
								</div>
							</div>
							
						</div>

					<?php endforeach ?>

				</div>
			</div>

			<div class="row margin_nada">
				<div class="col m12">	
					<button class="right btn waves-effect waves-light" type="submit" name="action">
						Editar Colegio
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

	$('#ColegioEditarForm').validate({
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
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function()
	{
		$('.tooltipped').tooltip({delay: 50});

		var niveles_existentes = 0;
		var grados_existentes = 0;

		<?php foreach ($colegio["Nivele"] as $keyNivel => $nivel): ?>

			niveles_existentes++;

			$('#agregar_grado_<?php echo $keyNivel ?>').tooltip({delay: 50});

	    	$("#agregar_grado_<?php echo $keyNivel ?>").click(function()
		    {
		    	agregarGrado(<?php echo $keyNivel ?>);
		    });

		    $('#ColegioNivel<?php echo $keyNivel ?>Nombre').rules("add", { required:true, alphanumeric:true });

			<?php foreach ($nivel["grados"] as $keyGrado => $grado): ?>
				
				grados_existentes++;

			    $('#ColegioNivel<?php echo $keyNivel ?>Grado<?php echo $keyGrado ?>Nombre').rules("add", { required:true, alphanumeric:true });

			<?php endforeach ?>

		<?php endforeach ?>

	    $("#agregar_nivel").click(function()
	    {
	    	niveles_existentes++;
	        agregarNivel(niveles_existentes);
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