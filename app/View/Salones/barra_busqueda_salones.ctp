

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
					<i class="material-icons prefix">book</i>
					<label for="FiltroNivel">Nivel</label>
					<input type="text" id="FiltroNivel">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">bookmark_border</i>
					<label for="FiltroGrado">Grado</label>
					<input type="text" id="FiltroGrado">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">format_color_text</i>
					<label for="FiltroSalon">Salón</label>
					<input type="text" id="FiltroSalon">
				</div>
			
				<div class="input-field col s12 m6">
					<i class="material-icons prefix">person</i>
					<label for="FiltroMaestro">Maestro</label>
					<input type="text" id="FiltroMaestro">
				</div>

			</div>

		</div>

	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#FiltroNivel').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroGrado').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroSalon').donetyping(function()
	{ filtrarResultado(); });
	$('#FiltroMaestro').donetyping(function()
	{ filtrarResultado(); });

	function filtrarResultado()
	{
		var salones = '<?php echo base64_encode(serialize($salones)) ?>';

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Salones', 'action' => 'filtrar_salones']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar').replaceWith(response);
	        },
	        data: {
	        	nivel: $('#FiltroNivel').val(),
	        	grado: $('#FiltroGrado').val(),
	        	salon: $('#FiltroSalon').val(),
	        	maestro: $('#FiltroMaestro').val(),
	        	salones: salones
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>