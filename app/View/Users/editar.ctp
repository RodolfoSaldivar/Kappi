

<?php $this->assign('title', 'Editar '.$this->Session->read("tipo").' - Kappi');  ?>

<?php $this->set('titulo_vista', 'Editar '.$this->Session->read("tipo")); ?>
<?php $this->set("breadcrumbs",
	'<a href="'.$this->Session->read("una_pag_antes").'" class="breadcrumb">'.$this->Session->read("tipo_bread").'</a>
	<a class="breadcrumb">Editar</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_usuarios").addClass("active");
		$("#usuarios_accion_<?php echo substr($this->Session->read("una_pag_antes"), 1) ?>").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('User', array(
	'type' => 'file'
)); ?>

	<?php echo $this->Form->hidden('id', array('value' => $usuario['User']['id'])); ?>
	
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
						<i class="material-icons prefix">vpn_key</i>
						<label for="UserIdentificador">
							Identificador
							<label id="UserIdentificador-error" class="error validation_label" for="UserIdentificador"></label>
						</label>
						<input name="data[User][identificador]" type="text" id="UserIdentificador" aria-required="true" class="error" aria-invalid="true" value="<?php echo $usuario['User']['identificador'] ?>">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">format_color_text</i>
						<label for="UserNombre">
							Nombre
							<label id="UserNombre-error" class="error validation_label" for="UserNombre"></label>
						</label>
						<input name="data[User][nombre]" type="text" id="UserNombre" aria-required="true" class="error" aria-invalid="true" value="<?php echo $usuario['User']['nombre'] ?>">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">format_color_text</i>
						<label for="UserAPaterno">
							Apellido Paterno
							<label id="UserAPaterno-error" class="error validation_label" for="UserAPaterno"></label>
						</label>
						<input name="data[User][a_paterno]" type="text" id="UserAPaterno" aria-required="true" class="error" aria-invalid="true" value="<?php echo $usuario['User']['a_paterno'] ?>">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">format_color_text</i>
						<label for="UserAMaterno">
							Apellido Materno
							<label id="UserAMaterno-error" class="error validation_label" for="UserAMaterno"></label>
						</label>
						<input name="data[User][a_materno]" type="text" id="UserAMaterno" aria-required="true" class="error" aria-invalid="true" value="<?php echo $usuario['User']['a_materno'] ?>">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">lock</i>
						<label for="UserPassword">
							Contraseña
							<label id="UserPassword-error" class="error validation_label" for="UserPassword"></label>
						</label>
						<input name="data[User][password]" type="text" id="UserPassword" aria-required="true" class="error" aria-invalid="true" value="<?php echo $usuario['User']['password'] ?>">
					</div>

				</div>
			
				<!-- Segunda columna; razon y logo -->
				<div class="col s6 padding_top padding_bottom">

					<div class="input-field">
						<i class="material-icons prefix">phone</i>
						<label for="UserCelular">
							Celular
							<label id="UserCelular-error" class="error validation_label" for="UserCelular"></label>
						</label>
						<input name="data[User][celular]" type="text" id="UserCelular" aria-required="true" class="error" aria-invalid="true" value="<?php echo $usuario['User']['celular'] ?>">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">mail</i>
						<label for="UserCorreo">
							Correo
							<label id="UserCorreo-error" class="error validation_label" for="UserCorreo"></label>
						</label>
						<input name="data[User][correo]" type="text" id="UserCorreo" aria-required="true" class="error" aria-invalid="true" value="<?php echo $usuario['User']['correo'] ?>">
					</div>

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

		</div>
	</div>

	<?php //Solo cuando se agreguen alumnos; se pedira el grado y nivel al que va, por referencia al momento de hacer los ciclos escolares
		if ($this->Session->read("tipo") == "Alumno"): ?>
		
		<div class="bg_blanco">
			<div class="contenedor">

				<div class="row margin_nada azul_4">
					<div style="padding:10px 0 0 20px; font-size:30px;">
						Nivel y Grado al que va
					</div>
				</div>

				<div class="row padding_top">
					<div class="input-field col s6">
						<i class="material-icons prefix">book</i>
						<select id="select_grado" name="data[User][grado_id]">

							<?php if (empty($usuario['User']['grado_id'])): ?>
								<option value="nada" disabled selected>Nivel y Grado</option>
							<?php endif ?>

							<?php foreach ($nivelesYgrados as $key => $nivgra): ?>
								
								<optgroup label="<?php echo $nivgra['Nivele']['nombre'] ?>">

									<?php foreach ($nivgra['Grado'] as $keyGrado => $grado): ?>
										
										<option value="<?php echo $grado['encriptada'] ?>" <?php if($usuario['User']['grado_id'] == $grado["id"]) echo "selected"; ?>><?php echo $grado['nombre'] ?></option>

									<?php endforeach ?>

								</optgroup>

							<?php endforeach ?>
						</select>
						<label id="select_grado-error" class="validation_label" style="position:absolute!important;">*Requerido</label>

					</div>
				</div>
			</div>
		</div>

	<?php endif ?>



	<div class="bg_blanco">
		<div class="contenedor">

			<?php if (in_array($this->Session->read('tipo'), array("Alumno", "Madre", "Padre"))): ?>
				
				<div class="row margin_nada azul_4">
					<div style="padding:10px 0 0 20px; font-size:30px;">
						Familia
					</div>
				</div>

				<div class="row">
					<div class="input-field col s6">
						<i class="material-icons prefix">group</i>
						<select id="todas_familias" name="data[Familia]">

							<option value="nada">-----</option>

							<?php foreach ($familias as $key => $fam): ?>

								<option value="<?php echo $fam[0]["encriptada"] ?>" <?php if($usuario['User']['familia_id'] == $fam[0]["id"]) echo "selected"; ?>><?php echo $fam[0]["nombre"] ?></option>

							<?php endforeach ?>

						</select>
						<label id="Familia-error" class="validation_label" for="Familia" style="position:absolute!important;">*Requerido</label>
					</div>
				</div>

			<?php endif ?>

			<div class="row">
				<div class="col s6" id="filtro_a_cambiar">
					<?php if (!empty($familia)): ?>
						<table>
							<tbody>

								<?php foreach ($familia as $key => $usu): ?>
									
									<tr>
										<td width="100" class="hide-on-med-and-down" style="padding-right:30px;">
											<?php if (!empty($usu["User"]['imagene_id'])): ?>
												<img class="responsive-img materialboxed circle" src="<?php echo $usu['Imagene']['ruta'] ?><?php echo $usu['Imagene']['nombre'] ?>">
											<?php else: ?>
												<img class="responsive-img materialboxed circle" src="/img/default_user.png">
											<?php endif ?>
										</td>
										<td>
											<?php echo $usu["User"]['nombre'] ?>
											<?php echo $usu["User"]['a_paterno'] ?>
											<?php echo $usu["User"]['a_materno'] ?>
										</td>
									</tr>

								<?php endforeach ?>
								
							</tbody>
						</table>
					<?php endif ?>	
				</div>
			</div>

			<div class="row margin_nada">
				<div class="col m12">	
					<button class="right btn waves-effect waves-light" type="submit" name="action">
						Editar <?php echo $this->Session->read("tipo") ?>
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

	$(document).ready(function() {
		$('select').material_select();
	});

	$.validator.messages.required = '*Requerido';
	$.validator.messages.alphanumeric = '*Solo letras y números';
	$.validator.messages.minlength = '*Entre 8 y 20 caracteres';

	$('#UserEditarForm').validate({
		rules: {
			'data[User][identificador]': {
				required: true,
				alphanumeric: true
			},
			'data[User][nombre]': {
				required: true,
				alphanumeric: true
			},
			'data[User][a_paterno]': {
				required: true,
				alphanumeric: true
			},
			'data[User][a_materno]': {
				required: true,
				alphanumeric: true
			},
			<?php if ($this->Session->read("tipo") != "Alumno"): ?>
				'data[User][correo]': {
					required: true,
					alphanumeric: true,
					email: true					
				},
				'data[User][celular]': {
					required: true,
					alphanumeric: true
				},
			<?php endif ?>
			'data[User][password]': {
				required: true,
				alphanumeric: true,
      			minlength: 8
			}
		}
	});

	<?php //Solo cuando se editen alumnos sin grado; se pedira el grado y nivel al que va, por referencia al momento de hacer los ciclos escolares
		if ($this->Session->read("tipo") == "Alumno"): ?>
		
		$('#UserEditarForm').submit(function(event)
		{
			if(!$('#select_grado').val())
			{
				$("#select_grado-error").css("display", "initial");
				event.preventDefault();
			}
		});

		$(document).on("change", "#select_grado", function()
		{
			$("#select_grado-error").css("display", "none");
		});

	<?php endif ?>

<?php $this->Html->scriptEnd(); ?>