

<?php $this->assign('title', 'Resultados Carga Masiva - Kappi');  ?>

<?php $this->set('titulo_vista', 'Resultados Carga Masiva'); ?>
<?php $this->set("breadcrumbs",
	'<a href="'.$this->Session->read("una_pag_antes").'" class="breadcrumb">Usuarios</a>
	<a class="breadcrumb">Resultados</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_usuarios").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


	
	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row">
				<div style="padding:20px 0 20px 20px; font-size:30px;">
					Filas en plantilla:<br>
					<?php echo $fila ?><br><br>
					Agregados al sistema:<br>
					<?php echo $agregados ?><br><br>
					Actualizados:<br>
					<?php echo $actualizados ?><br>
				</div>
			</div>

		</div>
	</div>