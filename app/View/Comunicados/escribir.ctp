

<?php $this->assign('title', 'Escribir '.$this->Session->read("tipo").' - Kappi');  ?>

<?php $this->set('titulo_vista', 'Escribir '.$this->Session->read("tipo")); ?>
<?php $this->set("breadcrumbs",
	'<a href="'.$this->Session->read("una_pag_antes").'" class="breadcrumb">'.$this->Session->read("tipo_bread").'</a>
	<a class="breadcrumb">Escribir</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_mensajes").addClass("active");
		$("#mensajes_accion_<?php echo substr($this->Session->read("una_pag_antes"), 1) ?>").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('Comunicado', array(
	'type' => 'file'
)); ?>

	<div class="bg_blanco">
		<div class="contenedor">

			<?php //Significa que es una tarea o es un comunicado ?>
			<?php if ($this->Session->read("tipo") != "Circular"): ?>
					
				<div class="row azul_4">
					<div class="col m2" style="padding-top:10px; padding-bottom:20px; font-size:30px;">
						Para:
					</div>

					<div class="input-field col m4 l3">
						<i class="material-icons prefix">group</i>
						<select id="salon_escogido" name="salon">
							<option value="nada" disabled>Salón</option>

							<?php foreach ($salones as $key => $salon): ?>

								<option value="<?php echo $salon["Salone"]['encriptada'] ?>"><?php echo $salon["Salone"]['nombre'] ?></option>

							<?php endforeach ?>
						</select>
						<label id="salon_escogido-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
					</div>

					<div class="input-field col m4 l3" id="area_modo" style="display:none;">

						<select id="select_modo" name="modo">
							<option value="1" selected>Todo el salón</option>
							<option value="2">Alumnos específicos</option>

							<?php if ($this->Session->read("tipo") == "Comunicado"): ?>
								<option value="3">Todas las mamás y papás</option>
								<option value="4">Mamás y papás específicos</option>
							<?php endif ?>

						</select>
					</div>

				</div>

				<div id="alumnos_especificos_cambiar">
				</div>

			<?php else: ?>
				<?php //Significa que es una circular ?>

				<div class="row azul_4">
					<div class="col m2" style="padding-top:10px; padding-bottom:20px; font-size:30px;">
						Para:
					</div>

					<div class="input-field col m3 l3">
						<select id="nivel_escogido" name="nivel">
							<option value="todos" selected="">Toda la escuela</option>

							<?php foreach ($niveles as $key => $nivel): ?>

								<option value="<?php echo $nivel["Nivele"]['encriptada'] ?>"><?php echo $nivel["Nivele"]['nombre'] ?></option>

							<?php endforeach ?>
						</select>
						<label id="nivel_escogido-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
					</div>

					<div class="input-field col m3 l3">
						<div id="grados_poner">
						</div>
					</div>

					<div class="input-field col m3 l3">
						<div id="salones_poner">
						</div>
					</div>
				</div>

				<div class="row padding_bottom" id="circular_check">

					<div id="check_absolute" class="col m2">
						<label id="checkM-error" class="error validation_label" for="checkM"></label>
						<label id="checkP-error" class="error hide" for="checkP"></label>
						<label id="checkA-error" class="error hide" for="checkA"></label>
					</div>

					<div class="col s2 offset-m2">
						<input class="check_desti" type="checkbox" id="checkM" name="checkM" />
						<label for="checkM">Mamás</label>
					</div>
					<div class="col s2">
						<input class="check_desti" type="checkbox" id="checkP" name="checkP" />
						<label for="checkP">Papás</label>
					</div>
					<div class="col s2">
						<input class="check_desti" type="checkbox" id="checkA" name="checkA" />
						<label for="checkA">Alumnos</label>
					</div>
				</div>

			<?php endif ?>	
		</div>
	</div>


	
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row azul_4">
				<div class="col m3 l2" style="padding-top:10px; padding-bottom:20px; font-size:30px;">
					Asunto:
				</div>

				<div class="input-field col m9 l10">
					<label for="ComunicadoAsunto">
						<label id="ComunicadoAsunto-error" class="error validation_label" for="ComunicadoAsunto"></label>
					</label>
					<input name="data[Comunicado][asunto]" type="text" id="ComunicadoAsunto" aria-required="true" class="error" aria-invalid="true" value="<?php echo @$reusado["Comunicado"]["asunto"] ?>">
				</div>

			</div>

		</div>
	</div>


	
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row">
				<div class="col m3 l2 azul_4" style="padding-top:10px; padding-bottom:20px; font-size:30px;">
					Mensaje:
				</div>

				<div class="input-field col m9 l10">

					<textarea name="data[Comunicado][mensaje]" id="ComunicadoMensaje" class="materialize-textarea error"></textarea>

					<label for="ComunicadoMensaje">
						<label id="ComunicadoMensaje-error" class="error validation_label" for="ComunicadoMensaje"></label>
					</label>
				</div>

			</div>


			<?php $existe = 0; ?>
			<?php if (in_array($reusado["Comunicado"]["firmado"], array("0", "1"))) $existe = 1; ?>
			<div class="row azul_4">
				<div class="col m9 l10 offset-m3 offset-l2">	
					<input type="checkbox" id="firma" name="firma" <?php if($existe == 1 && $reusado["Comunicado"]["firmado"] == 1) echo 'checked="checked"'; ?>/>
					<label for="firma">
						Solicitar firmar el mensaje.
					</label>
				</div>
			</div>



			<?php if (!empty($reusado["Archivo"])): ?>
				
				<div class="row">
					<div class="col m9 l10 offset-m3 offset-l2">
						<?php foreach ($reusado["Archivo"] as $key => $pdf): ?>
							<div class="col s2" id="pdf_adios_<?php echo $pdf[0]['encriptada'] ?>" style="margin-bottom:20px;">
								<input type="hidden" name="data[Comunicado][PDF_reusado][<?php echo $key ?>]" value='<?php echo $pdf[0]["encriptada"] ?>'>
								<img class="imagenes_descargar col s7" src="/img/img_pdf.png">
								<a href="/descargar_pdf/<?php echo $pdf[0]['encriptada'] ?>" target="_blank">
									<i class="material-icons btn_descarga" style="margin-bottom:10px;">visibility</i>
								</a><br>
								<a id="pdf_remover_<?php echo $pdf[0]['encriptada'] ?>" style="cursor:pointer;">
									<i class="material-icons btn_descarga">remove_circle</i>
								</a>
							</div>
						<?php endforeach ?>
					</div>
				</div>

			<?php endif ?>



			<?php if (!empty($reusado["Imagene"])): ?>
				
				<div class="row">
					<div class="col m9 l10 offset-m3 offset-l2">
						<?php foreach ($reusado["Imagene"] as $key => $imagen): ?>
							<div class="col s2" id="imagen_adios_<?php echo $imagen[0]['encriptada'] ?>" style="margin-bottom:20px;">
								<input type="hidden" name="data[Comunicado][Imagenes_reusadas][<?php echo $key ?>]" value="<?php echo $imagen[0]["encriptada"] ?>">
								<img class="imagenes_descargar materialboxed col s7" src="<?php echo $imagen[0]['ruta'] ?><?php echo $imagen[0]['nombre'] ?>">
								<a id="imagen_remover_<?php echo $imagen[0]['encriptada'] ?>" style="cursor:pointer;">
									<i class="material-icons btn_descarga">remove_circle</i>
								</a>
							</div>
						<?php endforeach ?>
					</div>
				</div>

			<?php endif ?>



			<div id="donde_agregar_pdf">
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
							<input name="data[Comunicado][PDF][0]" type="file">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path" type="text">
							<label id="comunicadoNum0" class="error validation_label validation_archivo" for="data[Comunicado][PDF][0]">* Requerido</label>
						</div>
					</div>
				</div>
			</div>



			<div id="donde_agregar_imagenes">
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
							<input name="data[Comunicado][Imagenes][0]" type="file">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path" type="text">
							<label id="imageneNum0" class="error validation_label validation_imagene" for="data[Comunicado][Imagenes][0]">* Requerido</label>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="row padding_bottom hide" id="aparece_loading">
			<div class="col s3 offset-s9 center" style="padding-top:50px;">
				<div class="preloader-wrapper big active">
					<div class="spinner-layer azul_5">
						<div class="circle-clipper left"><div class="circle"></div></div>
						<div class="gap-patch"><div class="circle"></div></div>
						<div class="circle-clipper right"><div class="circle"></div></div>
					</div>
				</div>
				<br>
				<span class="enviando">
					Enviando <?php echo $this->Session->read("tipo") ?>
				</span>
			</div>
		</div>


		<div id="desaparece_loading">
			<div class="row azul_4">
				<div class="col m12">	
					<input type="checkbox" id="guardar" name="guardar" />
					<label class="right" for="guardar">
						Guardar <?php echo $this->Session->read("tipo") ?>
					</label>
				</div>
			</div>

			<div id="enviar_mensaje">
				<div class="row margin_nada">
					<div class="col m12">
						<button class="right btn waves-effect waves-light" type="submit" name="action">
							Enviar <?php echo $this->Session->read("tipo") ?>
							<i class="material-icons right">send</i>
						</button>
					</div>
				</div>
			</div>

			<div id="guardar_mensaje" class="hide">
				<div class="row">
					<div class="col m12">
						<button class="right btn waves-effect waves-light" type="submit" name="action">
							Guardar y Enviar
							<i class="material-icons right">send</i>
						</button>
					</div>
				</div>
				<div class="row margin_nada">
					<div class="col m12">
						<button class="right btn waves-effect waves-light" type="submit" name="action" value="sg">
							Solo Guardar
							<i class="material-icons right">save</i>
						</button>
					</div>
				</div>
			</div>
			<br>
		</div>
		
	</div>



<?php echo $this->Form->end(); ?>


<?php $this->Html->script('require_from_group', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$('select').material_select();

		<?php if (!empty($reusado)): ?>
			$('#ComunicadoMensaje').val('<?php echo preg_replace('#\R+#', ' ', $reusado["Comunicado"]["mensaje"]) ?>');

  			$('#ComunicadoMensaje').trigger('autoresize');
		<?php endif ?>

		<?php if (!empty($reusado["Archivo"])): ?>
			<?php foreach ($reusado["Archivo"] as $key => $pdf): ?>
				$(document).on("click", "#pdf_remover_<?php echo $pdf[0]['encriptada'] ?>", function()
				{
					$("#pdf_adios_<?php echo $pdf[0]['encriptada'] ?>").remove();
				});
			<?php endforeach ?>
		<?php endif ?>

		<?php if (!empty($reusado["Imagene"])): ?>
			<?php foreach ($reusado["Imagene"] as $key => $imagen): ?>
				$(document).on("click", "#imagen_remover_<?php echo $imagen[0]['encriptada'] ?>", function()
				{
					$("#imagen_adios_<?php echo $imagen[0]['encriptada'] ?>").remove();
				});
			<?php endforeach ?>
		<?php endif ?>
	});

	$.validator.messages.required = '*Requerido';
	$.validator.messages.alphanumeric = '*Solo letras y números';

	$('#ComunicadoEscribirForm').validate({
		rules: {
			'data[Comunicado][asunto]': {
				required: true,
				alphanumeric: true
			},
			'data[Comunicado][mensaje]': {
				required: true,
				alphanumeric: true
			}
		}
	});

	<?php if ($this->Session->read("tipo") != "Circular"): ?>
			
		$('#ComunicadoEscribirForm').submit(function(event)
		{
			if(!$('#salon_escogido').val())
			{
				$("#salon_escogido-error").css("display", "initial");
				event.preventDefault();
			}
			else
				if ($('#ComunicadoEscribirForm').valid())
				{
					$("#desaparece_loading").addClass("hide");
					$("#aparece_loading").removeClass("hide");
				}
		});

	<?php else: ?>

		<?php
			//Aparece el simbolo de cargando y se muestra
		?>
		$(document).on("submit", "#ComunicadoEscribirForm", function()
		{	
			$("#desaparece_loading").addClass("hide");
			$("#aparece_loading").removeClass("hide");
		});

	<?php endif ?>	



	<?php
		//Si solo quiere mandar tareas a ciertos miembros del salon
	?>
	if ($("#salon_escogido").val() != "")
	{
		$("#salon_escogido-error").css("display", "none");
		$("#area_modo").show();
	}
	
	$(document).on("change", "#salon_escogido", function()
	{	
		$("#salon_escogido-error").css("display", "none");
		$("#area_modo").show();
	});



	<?php
		//Cuando acepta el guardar el mensaje
	?>

	$(document).on("change", "#guardar", function()
	{	
		var quiere_guardar = $("#guardar").prop("checked");

		if (quiere_guardar)
		{
			$("#guardar_mensaje").removeClass("hide");
			$("#enviar_mensaje").addClass("hide");
		}
		else
		{
			$("#guardar_mensaje").addClass("hide");
			$("#enviar_mensaje").removeClass("hide");
		}
	});



	<?php
		//Cuando le pica al checkbox de Alumnos
	?>

	$(document).on("change", "#checkA", function()
	{	
		if ($('#checkA').prop('checked'))
		{
			<?php //Se acaba de aceptar ?>
			$('#checkM').prop('checked', true);
			$('#checkP').prop('checked', true);

			$('#checkM').attr('disabled', true);
			$('#checkP').attr('disabled', true);
		}
		else
		{
			$('#checkM').removeAttr('disabled');
			$('#checkP').removeAttr('disabled');
		}
	});

	<?php if ($this->Session->read("tipo") == "Circular"): ?>
		
		$(".check_desti").each(function(){
        	$(this).rules('add', {
		        require_from_group: [1, ".check_desti"]
		    });
        });

	<?php endif ?>




	<?php
		//El ajax donde aparecen los grados que pertenecen al nivel
	?>

	$(document).on("change", "#nivel_escogido", function()
	{
		if ($(this).val() != "todos")
			mostrarGrados();
		else
		{
			$("#grados_poner").hide();
			$("#salones_poner").hide();
		}

	});

	function mostrarGrados()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Comunicados', 'action' => 'mostrar_grados']); ?>',
	        success: function(response)
	        {
	            $('#grados_poner').replaceWith(response);

	            $('select').material_select();

	            $("#salones_poner").hide();
	        },
	        data: {
	        	nivel: $('#nivel_escogido').val()
	        }
	    });
	}




	<?php
		//El ajax donde aparecen los salones que pertenecen al grado
	?>

	$(document).on("change", "#grado_escogido", function()
	{
		if ($(this).val() != "todos")
			mostrarSalones();
		else
			$("#salones_poner").hide();

	});

	function mostrarSalones()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Comunicados', 'action' => 'mostrar_salones']); ?>',
	        success: function(response)
	        {
	            $('#salones_poner').replaceWith(response);

	            $('select').material_select();
	        },
	        data: {
	        	grado: $('#grado_escogido').val()
	        }
	    });
	}




	<?php
		//El ajax donde aparecen los alumnos que pertenecen a ese salon
	?>

	$(document).on("change", "#select_modo", function()
	{
		if ($(this).val() == 2 || $(this).val() == 4)
			alumnosEspecificos();
		else
			$("#alumnos_especificos_cambiar").hide();
	});

	function alumnosEspecificos()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Comunicados', 'action' => 'alumnos_especificos']); ?>',
	        success: function(response)
	        {
	            $('#alumnos_especificos_cambiar').replaceWith(response);

	            $("#ComunicadoEscribirForm").validate();
	            $(".alumnos_salon").each(function(){
	            	$(this).rules('add', {
				        require_from_group: [1, ".alumnos_salon"]
				    });
	            });
	        },
	        data: {
	        	salon: $('#salon_escogido').val()
	        }
	    });
	}




	<?php
		//El ajax para agregar pdf
	?>

	var cont_pdf = 0;

	$(document).on("click", "#agregar_pdf", function()
	{
		if ($('input[name="data[Comunicado][PDF]['+cont_pdf+']"]').val() == "")
		{
			$('#comunicadoNum'+cont_pdf).css("display", "inline");
		}
		else
		{
			cont_pdf++;

			if (cont_pdf <= 5)
				$.ajax({
			        type:'POST',
			        cache: false,
			        url: '<?= Router::Url(['controller' => 'Comunicados', 'action' => 'agregar_pdf']); ?>',
			        success: function(response)
			        {
			        	$("#eliminar_boton_pdf").remove();
			            $('#donde_agregar_pdf').append(response);
			            if (cont_pdf == 5)
			            	$("#eliminar_boton_pdf").remove();
			        },
			        data: {
			        	cont_pdf: cont_pdf
			        }
			    }); 
		}

			   	
	});




	<?php
		//El ajax para agregar imagenes
	?>

	var cont_imagenes = 0;

	$(document).on("click", "#agregar_imagen", function()
	{ 
		if ($('input[name="data[Comunicado][Imagenes]['+cont_imagenes+']"]').val() == "")
		{
			$('#imageneNum'+cont_imagenes).css("display", "inline");
		}
		else
		{	
			cont_imagenes++;

			if (cont_imagenes <= 20)
				$.ajax({
			        type:'POST',
			        cache: false,
			        url: '<?= Router::Url(['controller' => 'Comunicados', 'action' => 'agregar_imagen']); ?>',
			        success: function(response)
			        {
			        	$("#eliminar_boton_imagenes").remove();
			            $('#donde_agregar_imagenes').append(response);
			            if (cont_imagenes == 20)
			            	$("#eliminar_boton_imagenes").remove();
			        },
			        data: {
			        	cont_imagenes: cont_imagenes
			        }
			    });
		}    	
	});

<?php $this->Html->scriptEnd(); ?>