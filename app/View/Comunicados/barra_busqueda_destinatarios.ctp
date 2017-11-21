

<ul id="forma_busqueda" class="collapsible popout" data-collapsible="accordion">
	<li id="sombra">
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
					<label for="FiltroNombre">Nombre</label>
					<input type="text" id="FiltroNombre">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">recent_actors</i>
					<label for="FiltroTipo">Rol</label>
					<input type="text" id="FiltroTipo">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">visibility</i>
					<label for="FiltroVisto">Visto</label>
					<input type="text" id="FiltroVisto" placeholder="Si/No">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">done_all</i>
					<label for="FiltroFirmado">Firmado</label>
					<input type="text" id="FiltroFirmado" placeholder="Si/No">
				</div>

			</div>

		</div>

	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#FiltroNombre').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroTipo').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroVisto').donetyping(function()
	{ filtrarResultado(); });
	$('#UserId').donetyping(function()
	{ FiltroFirmado(); });

	function filtrarResultado()
	{
		var destinatarios = '<?php echo base64_encode(serialize($destinatarios)) ?>';

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Comunicados', 'action' => 'filtrar_destinatarios']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar').replaceWith(response);
	        },
	        data: {
	        	nombre: $('#FiltroNombre').val(),
	        	tipo: $('#FiltroTipo').val(),
	        	visto: $('#FiltroVisto').val(),
	        	firmado: $('#FiltroFirmado').val(),
	        	destinatarios: destinatarios
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>