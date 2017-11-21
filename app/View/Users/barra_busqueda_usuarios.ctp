

<ul id="forma_busqueda" class="collapsible popout" data-collapsible="accordion">
	<li>
		<!-- Lo que se ve en la barra de busqueda -->
		<div class="collapsible-header">
			<span class="complementario">Buscar</span>
			<i class="material-icons complementario">search</i>
		</div>

		<!-- Los campos que se deben llenar -->
		<div class="collapsible-body bg_blanco padding_top">
			<div class="row">

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">format_color_text</i>
					<label for="UserNombre">Nombre</label>
					<input type="text" id="UserNombre">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">vpn_key</i>
					<label for="UserId">Identificador</label>
					<input type="text" id="UserId">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">account_circle</i>
					<label for="UserUsername">Usuario</label>
					<input type="text" id="UserUsername">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">mail</i>
					<label for="UserCorreo">Correo</label>
					<input type="email" id="UserCorreo">
				</div>

			</div>
		</div>

	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#UserUsername').donetyping(function()
	{ filtrarResultado(); });
	$('#UserNombre').donetyping(function()
	{ filtrarResultado(); });
	$('#UserCorreo').donetyping(function()
	{ filtrarResultado(); });
	$('#UserId').donetyping(function()
	{ filtrarResultado(); });

	function filtrarResultado()
	{
		$("#mostrar_todos").addClass("hide");

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Users', 'action' => 'filtrar_usuarios']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar').replaceWith(response);
	            $('.materialboxed').materialbox();
	        },
	        data: {
	        	username: $('#UserUsername').val(),
	        	nombre: $('#UserNombre').val(),
	        	correo: $('#UserCorreo').val(),
	        	identificador: $('#UserId').val(),
	        	tipo: "<?php echo $tipo ?>"
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>