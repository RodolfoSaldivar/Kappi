

<?php $this->assign('title', 'Editar Ciclo - Kappi');  ?>

<?php $this->set('titulo_vista', "Editar Ciclo"); ?>
<?php $this->set("breadcrumbs",
	'<a href="/ciclos" class="breadcrumb">Ciclos</a>
	<a class="breadcrumb">Editar</a>
	<a class="breadcrumb">'.$ciclo["Ciclo"]["nombre"].'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_ciclos").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('Ciclo'); ?>

	<?php echo $this->Form->hidden('id', array('value' => $ciclo['Ciclo']['id'])); ?>

	<div class="bg_blanco">
		<div class="contenedor">
			
			<div class="row margin_nada azul_4">
				<div style="padding:10px 0 0 20px; font-size:30px;">
					Datos
				</div>
			</div>

			<div class="row">
				<div class="col s12 padding_top padding_bottom">

					<div class="input-field">
						<i class="material-icons prefix">format_color_text</i>
						<label for="CicloNombre">
							Nombre
							<label id="CicloNombre-error" class="error validation_label" for="CicloNombre"></label>
						</label>
						<input name="data[Ciclo][nombre]" type="text" id="CicloNombre" aria-required="true" class="error" aria-invalid="true" value="<?php echo $ciclo["Ciclo"]["nombre"] ?>">
					</div>

				</div>
			</div>

			<div class="row">
				<div class="input-field col s12 m6 padding_top padding_bottom">
					<i class="material-icons prefix">today</i>
					<input name="data[Ciclo][fecha_inicio]" id="CicloFechaInicio" type="date" class="datepicker">
					<label for="CicloFechaInicio">
						Fecha Inicio
						<label id="CicloFechaInicio-error" class="error validation_label" for="CicloFechaInicio"></label>
					</label>
				</div>
				<div class="input-field col s12 m6 padding_top padding_bottom">
					<i class="material-icons prefix">event</i>
					<input name="data[Ciclo][fecha_fin]" id="CicloFechaFin" type="date" class="datepicker">
					<label for="CicloFechaFin">
						Fecha Fin
						<label id="CicloFechaFin-error" class="error validation_label" for="CicloFechaFin"></label>
					</label>
				</div>
			</div>

			<div class="row margin_nada">
				<div class="col m12">	
					<button class="right btn waves-effect waves-light" type="submit" name="action">
						Editar Ciclo
						<i class="material-icons right">send</i>
					</button>
				</div>
			</div>
			<br>

		</div>
	</div>

<?php echo $this->Form->end(); ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$.validator.messages.required = '*Requerido';
	$.validator.messages.alphanumeric = '*Solo letras y números';

	$('#CicloEditarForm').validate({
		rules: {
			'data[Ciclo][nombre]': {
				required: true,
				alphanumeric: true
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>



<?php $this->Html->script('pickadate_default', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#CicloFechaInicio').pickadate(
	{
		onStart: function()
		{
			var $input = $('#CicloFechaInicio').pickadate();
			var picker = $input.pickadate('picker');

			var mes = new Date();
			var antes = mes.setFullYear(mes.getFullYear() - 1);
			picker.set('min', new Date(antes));

			var anio = new Date();
			var sigue = anio.setFullYear(anio.getFullYear() + 1);
			picker.set('max', new Date(sigue));

			this.set("select", "<?php echo $ciclo["Ciclo"]["fecha_inicio"] ?>", {format: "ddd - mmmm dd, yyyy"});
		},
		onOpen: function()
		{
			limitesFechas();
		}
	});

	$('#CicloFechaFin').pickadate(
	{
		onStart: function()
		{
			var $input = $('#CicloFechaFin').pickadate();
			var picker = $input.pickadate('picker');

			var mes = new Date();
			var antes = mes.setFullYear(mes.getFullYear() - 1);
			picker.set('min', new Date(antes));

			var anio = new Date();
			var sigue = anio.setFullYear(anio.getFullYear() + 2);
			picker.set('max', new Date(sigue));
			
			this.set("select", "<?php echo $ciclo["Ciclo"]["fecha_fin"] ?>", {format: "ddd - mmmm dd, yyyy"});
		},
		onOpen: function()
		{
			limitesFechas();
		}
	});

	function limitesFechas()
	{
		var from_$input = $('#CicloFechaInicio').pickadate(),
		from_picker = from_$input.pickadate('picker')

		var to_$input = $('#CicloFechaFin').pickadate(),
		to_picker = to_$input.pickadate('picker')

		// Check if there’s a “from” or “to” date to start with.
		if ( from_picker.get('value') ) {
			to_picker.set('min', from_picker.get('select'))
		}
		if ( to_picker.get('value') ) {
			from_picker.set('max', to_picker.get('select'))
		}

		// When something is selected, update the “from” and “to” limits.
		from_picker.on('set', function(event)
		{
			if ( event.select ) {
				to_picker.set('min', from_picker.get('select'))    
			}
			else if ( 'clear' in event ) {
				to_picker.set('min', false)
			}
		})
		to_picker.on('set', function(event)
		{
			if ( event.select ) {
				from_picker.set('max', to_picker.get('select'))
			}
			else if ( 'clear' in event ) {
				from_picker.set('max', false)
			}
		})
	}

<?php $this->Html->scriptEnd(); ?>