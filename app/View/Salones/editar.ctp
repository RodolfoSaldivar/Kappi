

<?php $this->assign('title', 'Editar Salón - Kappi');  ?>

<?php $this->set('titulo_vista', 'Editar Salón'); ?>
<?php $this->set("breadcrumbs",
	'<a href="/ciclos" class="breadcrumb">Ciclos</a>
	<a href="/salones_index/'.$salon["Salone"]["ciclo_id"].'" class="breadcrumb">'.$ciclo["Ciclo"]["nombre"].'</a>
	<a class="breadcrumb">Editar Salón</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_ciclos").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php echo $this->Form->create('Salone'); ?>

	<?php echo $this->Form->hidden('id', array('value' => $salon['Salone']['id'])); ?>

	<?php echo $this->Form->hidden('ciclo_id', array('value' => $salon['Salone']['ciclo_id'])); ?>
	
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row margin_nada azul_4">
				<div style="padding:10px 0 0 20px; font-size:30px;">
					Datos
				</div>
			</div>

			<!-- Area de datos -->
			<div class="row">

				<div class="col s6 padding_top padding_bottom">

					<div class="input-field">
						<i class="material-icons prefix">format_color_text</i>
						<label for="SaloneNombre">
							Salón
							<label id="SaloneNombre-error" class="error validation_label" for="SaloneNombre"></label>
						</label>
						<input name="data[Salone][nombre]" type="text" id="SaloneNombre" aria-required="true" class="error" aria-invalid="true" value="<?php echo $salon["Salone"]["nombre"] ?>">
					</div>

					<div class="input-field">
						<i class="material-icons prefix">account_circle</i>
						<select id="SaloneUserId" name="data[Salone][user_id]">

							<option value="nada" disabled>Maestro</option>

							<?php foreach ($maestros as $key => $maestro): ?>

								<option value="<?php echo $maestro[0]["encriptada"] ?>" <?php if ($salon["Salone"]["user_id"] == $maestro[0]["id"]) echo "selected"; ?>><?php echo $maestro[0]["a_paterno"]." ".$maestro[0]["a_materno"].", ".$maestro[0]["nombre"] ?></option>

							<?php endforeach ?>

						</select>
						<label id="SaloneUserId-error" class="validation_label" for="SaloneUserId" style="position:absolute!important;">*Requerido</label>
					</div>

				</div>
			
				<div class="col s6 padding_top padding_bottom">

					<input type="hidden" id="SaloneHidden" value="<?php echo $salon["Salone"]["grado_id"] ?>" name="data[Salone][grado_id]">

					<div class="input-field">
						<i class="material-icons prefix">book</i>
						<label class="active">
							Nivel y Grado
						</label>
						<input disabled type="text" value="<?php echo $grado["Nivele"]["nombre"]." - ".$grado["Grado"]["nombre"] ?>">
					</div>

				</div>
			</div>

		</div>
	</div>


	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row azul_4" style="padding-top:10px; font-size:30px;">
				<div class="left">
					Inscribir alumnos de
				</div>

				<div id="segundoSelect">	
					<select id="nivelYgrado" class="col l2 m3">
						<option value="nada" disabled selected>Nivel y Grado</option>
					</select>
				</div>

				<select id="modo" class="col s4">
					<option value="nada" disabled selected>Modo</option>
					<option value="1">Usando salones existentes</option>
					<option value="2">Buscando por nombre</option>
				</select>
			</div>

			<div id="moduloAgregarSalon" class="row margin_nada padding_bottom">

				<div class="col s6" id="seccionDeAgregado">
			
				</div>

				<div class="col s6 right" id="segundaMitad">

					<ul class="collection with-header">
						<li class="collection-header">
							<h4>
								Inscritos = 
								<span id="ponme_cont_ins">0</span>
							</h4>
						</li>
					</ul>

					<div id="todosAlumnosInscritos">
						
					</div>

					<div class="row margin_nada">
						<div class="col m12">	
							<button class="right btn waves-effect waves-light" type="submit" name="action">
								Editar Salón
								<i class="material-icons right">send</i>
							</button>
						</div>
					</div>
					<br>
				</div>
			</div>

		</div>
	</div>

<?php echo $this->Form->end(); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	<?php
		//Ajax del select de inscripcion de alumnos
	?>

	function segundoSelect()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Salones', 'action' => 'segundo_select']); ?>',
	        success: function(response)
	        {
	            $("#segundoSelect").replaceWith(response);

	            $(document).ready(function() {
					$('#nivelYgrado').material_select();
				});
	        },
	        data: {
	        	grado: $('#SaloneHidden').val()
	        }
	    });
	}


	<?php
		//Script usado para las valdaciones de nombre, grado y maestro
	?>

	$(document).ready(function() {
		$('select').material_select();
		segundoSelect();
		<?php foreach ($inscritos as $key => $inscrito): ?>
			agregarBorrarInscritos("<?php echo $inscrito["User"]["encriptada"] ?>", "d");
		<?php endforeach ?>
	});

	$.validator.messages.required = '*Requerido';
	$.validator.messages.alphanumeric = '*Solo letras y números';

	$('#SaloneEditarForm').validate({
		rules: {
			'data[Salone][nombre]': {
				required: true,
				alphanumeric: true
			}
		}
	});


	<?php
		//Script usado para mostrar el agregado despues de que se haya escogido el grado y el modo
	?>

	$(document).on("change", "#nivelYgrado", function()
	{
		if ($('#nivelYgrado').val() == "nuevos")
			$('#modo').parent().css("display", "none");
		else
			$('#modo').parent().css("display", "block");

		gradoYmodo();
	});

	$(document).on("change", "#modo", function()
	{
		gradoYmodo();
	});

	function gradoYmodo()
	{
		var llenos = true;

		if(!$('#nivelYgrado').val())
			llenos = false;

		if(!$('#modo').val())
			llenos = false;

		if($('#nivelYgrado').val() == "nuevos")
			llenos = true;

		if(llenos)
			mostrarAlumnos();
	}



	<?php 
		//Funcion para llamar en las respuestas de ciertos ajax
	?>

	function reload_inscribir()
	{
        $(document).ready(function(){
			$('.materialboxed').materialbox();
			$('#seleccion_salones').material_select();
		});

		$(".agregar_inscribir").click(function()
		{
			var alumno = $(this).parent().parent().children("input").val();
			agregarBorrarInscritos(alumno, "d");
		});
	}



	<?php 
		//Para sumar y restar el numero de inscritos
	?>

	var cont_inscritos = 0;

	function sumar_inscrito()
	{
		cont_inscritos++;
		$("#ponme_cont_ins").replaceWith('<span id="ponme_cont_ins">'+cont_inscritos+'</span>');
	}

	function restar_inscrito()
	{
		if (cont_inscritos > 0)	
		{
			cont_inscritos--;
			$("#ponme_cont_ins").replaceWith('<span id="ponme_cont_ins">'+cont_inscritos+'</span>');
		}
	}



	<?php 
		//Para no mostrar los que ya estan inscritos
	?>

	function no_mostrar()
	{
		$("#todosAlumnosInscritos tr > input").each(function()
		{
			var id = $(this).val();
			$("#mostrar_" + id).hide();
		});
	}



	<?php
		//Ajax para agregar la seccion de agregado
	?>

	function mostrarAlumnos()
	{
		
		$("#seccionDeAgregado").replaceWith('<div class="col s6 center" id="seccionDeAgregado" style="padding-top:50px;"><div class="preloader-wrapper big active"><div class="spinner-layer azul_5"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div>');

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Salones', 'action' => 'mostrar_alumnos']); ?>',
	        success: function(response)
	        {
	            $("#seccionDeAgregado").replaceWith(response);

	            reload_inscribir();

	            no_mostrar();
	        },
	        data: {
	        	grado: $('#nivelYgrado').val(),
	        	padre: $('#SaloneHidden').val(),
	        	modo: $('#modo').val(),
	        	ciclo: "<?php echo $salon["Salone"]["ciclo_id"] ?>",
	        	accion: "e",
	        	salon: "<?php echo $salon["Salone"]["id"] ?>"
	        }
	    });
	}



	<?php
		//Ajax para mover los alumnos al area de inscritos o quitarlos de ahi
	?>

	function agregarBorrarInscritos($alumno, $accion)
	{
		if($accion == "d")
		{
			$("#mostrar_"+$alumno).hide();
			sumar_inscrito();
		}
		if($accion == "f")
		{
			$("#mostrar_"+$alumno).show();
			restar_inscrito();
		}

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Salones', 'action' => 'inscribir_alumnos']); ?>',
	        success: function(response)
	        {
	            $("#todosAlumnosInscritos").replaceWith(response);

	            $(document).ready(function(){
					$('.materialboxed').materialbox();
				});

				$(".quitar_inscribir").click(function()
				{
					var alumno = $(this).parent().parent().children("input").val();
					agregarBorrarInscritos(alumno, "f");
				});
	        },
	        data: {
	        	alumno: $alumno,
	        	accion: $accion
	        }
	    });
	}



	<?php 
		//Se utiliza para filtrar los nombre cuando se escoge ese modo
	?>

	$(document).on("keyup paste", "#UserNombre", function() {
		filtrarResultado();
	});

	function filtrarResultado()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '<?= Router::Url(['controller' => 'Salones', 'action' => 'buscar_por_nombre']); ?>',
	        success: function(response)
	        {
	            $('#buscar_por_nombre_cambiar').replaceWith(response);

	            reload_inscribir();

	            no_mostrar();
	        },
	        data: {
	        	nombre: $('#UserNombre').val()
	        }
	    });
	}



	<?php 
		//Usado para que se vean los miembros de cada salon
	?>

	$(document).on("change", "#seleccion_salones", function()
	{
		var salon = $("#seleccion_salones").val();
		$(".escondeme").addClass("display_none");
		$(".salon_"+salon).removeClass("display_none");
	});

<?php $this->Html->scriptEnd(); ?>