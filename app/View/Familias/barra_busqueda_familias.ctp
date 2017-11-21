

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

				<div class="input-field col s12 m6 l4">
					<i class="material-icons prefix">group</i>
					<label for="FiltroNombre">Nombre</label>
					<input type="text" id="FiltroNombre">
				</div>
				
				<div class="input-field col s12 m6 l4">
					<i class="material-icons prefix">vpn_key</i>
					<label for="FiltroId">Identificador</label>
					<input type="text" id="FiltroId">
				</div>
				
				<div class="input-field col s12 m6 l4">
					<i class="material-icons prefix">person</i>
					<label for="FiltroMiembro">Miembro</label>
					<input type="text" id="FiltroMiembro">
				</div>

			</div>

		</div>

	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#FiltroMiembro').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroNombre').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroId').donetyping(function()
	{ filtrarResultado(); });

	function filtrarResultado()
	{
		$("#mostrar_todos").addClass("hide");

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Familias', 'action' => 'filtrar_familias']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar').replaceWith(response);
	        },
	        data: {
	        	miembro: $('#FiltroMiembro').val(),
	        	nombre: $('#FiltroNombre').val(),
	        	identificador: $('#FiltroId').val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>