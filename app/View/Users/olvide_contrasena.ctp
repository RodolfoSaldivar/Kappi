

<?php $this->assign('title', 'Resetear Contraseña - Kappi');  ?>

<div class="row padding_top">

	<div class="card-panel col l4 offset-l4 m6 offset-m3 s10 offset-s1 padding_top" style="margin-top:5%;">

		<div class="row center blanco">
			<h5><b>
				Reestablecer contraseña
			</b></h5>
		</div>

		<div class="row center">
			<h6 class="col s10 offset-s1 blanco">
				Escriba el nombre de su usuario y se le mandará un correo electrónico
			</h6>
		</div>
		
		<?php echo $this->Form->create() ?>

			<div class="row">	
				<div class="input-field col l8 offset-l2 m10 offset-m1 s12">
					<i class="material-icons prefix">account_circle</i>
					<label for="UserUsername">
						Usuario
						<label id="UserUsername-error" class="error validation_label" for="UserUsername"></label>
					</label>
					<input name="data[User][username]" type="text" id="UserUsername" aria-required="true" class="error" aria-invalid="true" required>
				</div>
			</div>

			<div class="row">
				<div class="center">
					<button class="btn waves-effect waves-light" type="submit" name="action">
						Enviar Correo
						<i class="material-icons right">mail_outline</i>
					</button>
				</div>
			</div>					

		<?php echo $this->Form->end(); ?>
	</div>
</div>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$.validator.messages.required = '*Requerido';

	$('#UserOlvideContrasenaForm').validate();

<?php $this->Html->scriptEnd(); ?>