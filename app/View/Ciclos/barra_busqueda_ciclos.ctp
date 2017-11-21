

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
				<div class="input-field col s12">
					<i class="material-icons prefix">format_color_text</i>
					<label for="FiltroNombre">Nombre</label>
					<input type="text" id="FiltroNombre">
				</div>

				<div class="input-field col s12">
					<i class="material-icons prefix">today</i>
					<label for="FiltroFechaInicio">Fecha Inicio</label>
					<input type="text" id="FiltroFechaInicio">
				</div>

				<div class="input-field col s12">
					<i class="material-icons prefix">event</i>
					<label for="FiltroFechaFin">Fecha Fin</label>
					<input type="text" id="FiltroFechaFin">
				</div>

			</div>

		</div>

	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#FiltroNombre').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroFechaInicio').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroFechaFin').donetyping(function()
	{ filtrarResultado(); });

	function filtrarResultado()
	{
		var ciclos = '<?php echo base64_encode(serialize($ciclos)) ?>';

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Ciclos', 'action' => 'filtrar_ciclos']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar').replaceWith(response);
	        },
	        data: {
	        	nombre: $('#FiltroNombre').val(),
	        	fecha_inicio: $('#FiltroFechaInicio').val(),
	        	fecha_fin: $('#FiltroFechaFin').val(),
	        	ciclos: ciclos
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>