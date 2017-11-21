

<?php $this->assign('title', 'Iniciar Sesión - Kappi');  ?>

<div class="row" style="margin-top:30px;">
	<div class="card-panel col l4 offset-l4 m6 offset-m3 s10 offset-s1" style="padding-top:20px;">
		<div class="center col s10 offset-s1">
			<img class="responsive-img" src="/img/kappi.png">
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
				<div class="input-field col l8 offset-l2 m10 offset-m1 s12">
					<i class="material-icons prefix">lock</i>
					<label for="UserPassword">
						Contraseña
						<label id="UserPassword-error" class="error validation_label" for="UserPassword"></label>
					</label>
					<input name="data[User][password]" type="password" id="UserPassword" aria-required="true" class="error" aria-invalid="true" required>
				</div>
			</div>

			<div class="row">
				<div class="center">
					<button class="btn waves-effect waves-light" type="submit" name="action">
						Iniciar Sesión
						<i class="material-icons right">send</i>
					</button>
				</div>
			</div>	

			<div class="row center">
				<a href="/olvide_contrasena"><u>¿Has olvidado la contraseña?</u></a>
			</div>				

		<?php echo $this->Form->end(); ?>
	</div>
</div>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$.validator.messages.required = '*Requerido';

	$('#UserLoginForm').validate();

<?php $this->Html->scriptEnd(); ?>