

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
					<label for="FiltroNombreComercial">Nombre Comercial</label>
					<input type="text" id="FiltroNombreComercial">
				</div>

				<div class="input-field col s12">
					<i class="material-icons prefix">text_format</i>
					<label for="FiltroNombreCorto">Nombre Corto</label>
					<input type="text" id="FiltroNombreCorto">
				</div>

				<div class="input-field col s12">
					<i class="material-icons prefix">phone</i>
					<label for="FiltroTelefono">Teléfono</label>
					<input type="text" id="FiltroTelefono">
				</div>

			</div>

		</div>

	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#FiltroNombreComercial').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroNombreCorto').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroTelefono').donetyping(function()
	{ filtrarResultado(); });

	function filtrarResultado()
	{
		var colegios = '<?php echo base64_encode(serialize($colegios)) ?>';

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Colegios', 'action' => 'barra_busqueda_filtro']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar').replaceWith(response);
	            $('.materialboxed').materialbox();
	        },
	        data: {
	        	nombre_comercial: $('#FiltroNombreComercial').val(),
	        	nombre_corto: $('#FiltroNombreCorto').val(),
	        	telefono: $('#FiltroTelefono').val(),
	        	colegios: colegios
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>