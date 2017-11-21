

<?php $this->assign('title', 'Cambiar Contraseña - Kappi'); ?>

<div class="row padding_top">

	<div class="card-panel col l4 offset-l4 m6 offset-m3 s10 offset-s1 padding_top" style="margin-top:5%;">

		<div class="row center blanco">
			<h5><b>
				Cambiar contraseña
			</b></h5>
		</div>

		<div class="row center">
			<h6 class="col s10 offset-s1 blanco">
				Escriba la contraseña actual y después a la que quiere cambiar.
			</h6>
		</div>
		
		<?php echo $this->Form->create() ?>

			<div class="row">	
				<div class="input-field col l10 offset-l1 m10 offset-m1 s12">
					<i class="material-icons prefix">lock</i>
					<label for="UserVieja">
						Contraseña Actual
						<label id="UserVieja-error" class="error validation_label" for="UserVieja"></label>
					</label>
					<input name="data[User][vieja]" type="password" id="UserVieja" aria-required="true" class="error" aria-invalid="true" required>
				</div>
			</div>

			<div class="row">	
				<div class="input-field col l10 offset-l1 m10 offset-m1 s12">
					<i class="material-icons prefix">add_box</i>
					<label for="UserNueva">
						Contraseña Nueva
						<label id="UserNueva-error" class="error validation_label" for="UserNueva"></label>
					</label>
					<input name="data[User][nueva]" type="password" id="UserNueva" aria-required="true" class="error" aria-invalid="true" required>
				</div>
			</div>

			<div class="row">	
				<div class="input-field col l10 offset-l1 m10 offset-m1 s12">
					<i class="material-icons prefix">repeat</i>
					<label for="UserRepetida">
						Confirmar Contraseña
						<label id="UserRepetida-error" class="error validation_label" for="UserRepetida"></label>
					</label>
					<input name="data[User][repetida]" type="password" id="UserRepetida" aria-required="true" class="error" aria-invalid="true" required>
				</div>
			</div>

			<div class="row">
				<div class="center">
					<button class="btn waves-effect waves-light" type="submit" name="action">
						Cambiar Contraseña
						<i class="material-icons right">import_export</i>
					</button>
				</div>
			</div>					

		<?php echo $this->Form->end(); ?>
	</div>
</div>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$.validator.messages.required = '*Requerido';
	$.validator.messages.equalTo = '*Misma Contraseña';
	$.validator.messages.alphanumeric = '*Letras y números';
	$.validator.messages.minlength = '*Entre 8 y 20 caracteres';

	$('#UserCambiarContrasenaForm').validate({
		rules: {
			'data[User][nueva]': {
				alphanumeric: true,
      			minlength: 8
			},
			'data[User][repetida]': {
				equalTo: '#UserNueva'
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>