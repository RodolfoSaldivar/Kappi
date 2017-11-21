

<ul id="forma_busqueda" class="collapsible popout" data-collapsible="accordion">
	<li id="sombra">
		<!-- Lo que se ve en la barra de busqueda -->
		<div class="collapsible-header">
			<span class="complementario">Buscar</span>
			<i class="material-icons complementario">search</i>
		</div>

		<!-- Los campos que se deben llenar -->
		<div class="collapsible-body bg_blanco padding_top">

			<div class="row margin_nada">
				<div class="input-field col s12">
					<i class="material-icons prefix">person</i>
					<label for="FiltroEmisorR">Emisor</label>
					<input type="text" id="FiltroEmisorR">
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12 m6">
					<i class="material-icons prefix">subject</i>
					<label for="FiltroAsuntoR">Asunto</label>
					<input type="text" id="FiltroAsuntoR">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">today</i>
					<label for="FiltroFechaR">Fecha</label>
					<input type="text" id="FiltroFechaR">
				</div>
			</div>

		</div>

	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#FiltroEmisorR').donetyping(function()
	{ filtrarResultadoR(); });
	$('#FiltroAsuntoR').donetyping(function()
	{ filtrarResultadoR(); });
	$('#FiltroFechaR').donetyping(function()
	{ filtrarResultadoR(); });

	function filtrarResultadoR()
	{
		var recibidos = '<?php echo base64_encode(serialize($recibidos)) ?>';

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'comunicados', 'action' => 'filtrar_recibidos']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar_recibidos').replaceWith(response);
	        },
	        data: {
	        	emisor: $('#FiltroEmisorR').val(),
	        	asunto: $('#FiltroAsuntoR').val(),
	        	fecha: $('#FiltroFechaR').val(),
	        	tipo: "<?php echo $tipo ?>",
	        	recibidos: recibidos
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>