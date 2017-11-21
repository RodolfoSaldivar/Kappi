

<?php $this->assign('title', 'Mandar '.$this->Session->read("tipo").' - Kappi');  ?>

<?php $this->set('titulo_vista', 'Mandar '.$this->Session->read("tipo")); ?>
<?php $this->set("breadcrumbs",
	'<a href="'.$this->Session->read("una_pag_antes").'" class="breadcrumb">'.$this->Session->read("tipo_bread").'</a>
	<a class="breadcrumb">Mandar</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_mensajes").addClass("active");
		$("#mensajes_accion_<?php echo substr($this->Session->read("una_pag_antes"), 1) ?>").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('DestinoExtra'); ?>


<div class="row">
<div class="col s12 m6" style="margin-bottom:40px;">

	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row azul_4 margin_nada">
				<div class="col m4 l3" style="padding-top:10px; padding-bottom:20px; font-size:30px;">
					Para:
				</div>

				<div class="input-field col m8 l7">
					<i class="material-icons prefix">group</i>
					<select id="salon_escogido" name="salon">
						<option value="nada" disabled>Salón</option>

						<?php foreach ($salones as $key => $salon): ?>

							<option value="<?php echo $salon["Salone"]['encriptada'] ?>"><?php echo $salon["Salone"]['nombre'] ?></option>

						<?php endforeach ?>
					</select>
					<label id="salon_escogido-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
				</div>
			</div>

			<div class="row">
				<div class="input-field col m8 offset-m4 col l7 offset-l3" id="area_modo" style="display:none;">

					<select id="select_modo" name="modo">
						<option value="1" selected>Todo el salón</option>
						<option value="2">Alumnos específicos</option>
					</select>
				</div>

			</div>

			<div id="alumnos_especificos_cambiar">
			</div>		

		</div>
	</div>
</div>


<div class="col s12 m6">
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row azul_4 holiwi">
				<div style="padding:10px 0 0 20px; font-size:30px;">
					<?php echo $this->Session->read("tipo_bread") ?>
					<?php foreach ($extras as $key => $extra): ?>

						<label class="error validation_label <?php if($key != 0) echo "hide" ?>" for="data[Extras][<?php echo $extra["Extra"]["encriptada"] ?>]"></label>
						
					<?php endforeach ?>
				</div>
			</div>

			<!-- Area de datos -->
			<div class="row padding_bottom" style="padding-left: 20px;">
				<?php foreach ($extras as $key => $extra): ?>
					
					<div class="padding_bottom padding_top">

						<table style="width:auto">
							<tr>
								<td valign="center">
									<input class="todos_extra" type="checkbox" id="extra_<?php echo $extra["Extra"]["encriptada"] ?>" name="data[Extras][<?php echo $extra["Extra"]["encriptada"] ?>]" />
				      				<label for="extra_<?php echo $extra["Extra"]["encriptada"] ?>">
				      					<?php echo $extra['Extra']['descripcion'] ?>
				      				</label>
								</td>
								<td valign="center" width="75">
									<img width="75" src="<?php echo $extra["Imagene"]["ruta"].$extra["Imagene"]["nombre"] ?>">
								</td>
							</tr>
						</table>

					</div>

				<?php endforeach ?>
			</div>


			<div class="row padding_bottom hide" id="aparece_loading">
				<div class="col s6 offset-s6 center">
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


			<div class="row margin_nada" id="desaparece_loading">
				<div class="col m12">
					<button class="right btn waves-effect waves-light" type="submit" name="action">
						Enviar <?php echo $this->Session->read("tipo") ?>
						<i class="material-icons right">send</i>
					</button>
				</div>
			</div>
			<br>

		</div>
	</div>

</div>
</div>


<?php echo $this->Form->end(); ?>


<?php $this->Html->script('require_from_group', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$('select').material_select();
	});

	$.validator.messages.required = '*Requerido';
	$.validator.messages.alphanumeric = '*Solo letras y números';

	$('#DestinoExtraMandarExtraForm').validate({
		rules: {
			<?php foreach ($extras as $extra): ?>
				'data[Extras][<?php echo $extra["Extra"]["encriptada"] ?>]': {
					require_from_group: [1, ".todos_extra"]
				},
			<?php endforeach ?>
		}
	});

	$('#DestinoExtraMandarExtraForm').submit(function(event)
	{
		if(!$('#salon_escogido').val())
		{
			$("#salon_escogido-error").css("display", "initial");
			event.preventDefault();
		}
		else
			if ($('#DestinoExtraMandarExtraForm').valid())
			{
				$("#desaparece_loading").addClass("hide");
				$("#aparece_loading").removeClass("hide");
			}
	});



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
		//El ajax donde aparecen los alumnos que pertenecen a ese salon
	?>

	$(document).on("change", "#select_modo", function()
	{
		if ($(this).val() == 2)
			alumnosEspecificos();
		else
			$("#alumnos_especificos_cambiar").hide();
	});

	function alumnosEspecificos()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Extras', 'action' => 'alumnos_especificos']); ?>',
	        success: function(response)
	        {
	            $('#alumnos_especificos_cambiar').replaceWith(response);

	            $("#DestinoExtraMandarExtraForm").validate();
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

<?php $this->Html->scriptEnd(); ?>