

<ul id="forma_busqueda" class="collapsible popout" data-collapsible="accordion">
	<li id="sombra">
		<!-- Lo que se ve en la barra de busqueda -->
		<div class="collapsible-header">
			<span class="complementario">Buscar</span>
			<i class="material-icons complementario">search</i>
		</div>

		<!-- Los campos que se deben llenar -->
		<div class="collapsible-body bg_blanco padding_top">

			<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
					
				<div class="row margin_nada">
					<div class="input-field col s12">
						<i class="material-icons prefix">person</i>
						<label for="FiltroEmisorE">Emisor</label>
						<input type="text" id="FiltroEmisorE">
					</div>
				</div>

			<?php endif ?>	

			<div class="row">
				<div class="input-field col s12 m6">
					<i class="material-icons prefix">subject</i>
					<label for="FiltroAsuntoE">Asunto</label>
					<input type="text" id="FiltroAsuntoE">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">today</i>
					<label for="FiltroFechaE">Fecha</label>
					<input type="text" id="FiltroFechaE">
				</div>
			</div>

		</div>

	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#FiltroEmisorE').donetyping(function()
	{ filtrarResultadoE(); });
	$('#FiltroAsuntoE').donetyping(function()
	{ filtrarResultadoE(); });
	$('#FiltroFechaE').donetyping(function()
	{ filtrarResultadoE(); });

	function filtrarResultadoE()
	{
		var enviados = '<?php echo base64_encode(serialize($enviados)) ?>';

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'comunicados', 'action' => 'filtrar_enviados']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar_enviados').replaceWith(response);
	        },
	        data: {
	        	<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
	        		emisor: $('#FiltroEmisorE').val(),
	        	<?php endif ?>
	        	asunto: $('#FiltroAsuntoE').val(),
	        	fecha: $('#FiltroFechaE').val(),
	        	tipo: "<?php echo $tipo ?>",
	        	enviados: enviados
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>