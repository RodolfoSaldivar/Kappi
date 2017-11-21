

<?php $this->assign('title', 'Resultados Carga Masiva - Kappi');  ?>

<?php $this->set('titulo_vista', 'Resultados Carga Masiva'); ?>
<?php $this->set("breadcrumbs",
	'<a href="/familias" class="breadcrumb">Familias</a>
	<a class="breadcrumb">Resultados</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_familias").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


	
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row">
				<div style="padding:20px 0 20px 20px; font-size:30px;">
					Filas en plantilla:<br>
					<?php echo $fila ?><br><br>
					Agregados al sistema:<br>
					<?php echo $agregadas ?><br><br>
					Actualizados:<br>
					<?php echo $actualizadas ?><br>
				</div>
			</div>

		</div>
	</div>