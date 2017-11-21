

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
						<label for="FiltroEmisorG">Emisor</label>
						<input type="text" id="FiltroEmisorG">
					</div>
				</div>

			<?php endif ?>	

			<div class="row">
				<div class="input-field col s12 m6">
					<i class="material-icons prefix">subject</i>
					<label for="FiltroAsuntoG">Asunto</label>
					<input type="text" id="FiltroAsuntoG">
				</div>

				<div class="input-field col s12 m6">
					<i class="material-icons prefix">message</i>
					<label for="FiltroMensajeG">Mensaje</label>
					<input type="text" id="FiltroMensajeG">
				</div>
			</div>

		</div>

	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#FiltroEmisorG').donetyping(function()
	{ filtrarResultadoG(); });
	$('#FiltroAsuntoG').donetyping(function()
	{ filtrarResultadoG(); });
	$('#FiltroMensajeG').donetyping(function()
	{ filtrarResultadoG(); });

	function filtrarResultadoG()
	{
		var guardados = '<?php echo base64_encode(serialize($guardados)) ?>';

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'comunicados', 'action' => 'filtrar_guardados']); ?>',
	        success: function(response)
	        {
	            $('#filtro_a_cambiar_guardados').replaceWith(response);
	            $('.tooltipped').tooltip();
	        },
	        data: {
	        	<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
	        		emisor: $('#FiltroEmisorG').val(),
	        	<?php endif ?>
	        	asunto: $('#FiltroAsuntoG').val(),
	        	mensaje: $('#FiltroMensajeG').val(),
	        	tipo: "<?php echo $tipo ?>",
	        	guardados: guardados
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>