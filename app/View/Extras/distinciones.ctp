

<?php $this->assign('title', 'Distinciones - Kappi');  ?>

<?php $this->set('titulo_vista', "Distinciones"); ?>
<?php $this->set("breadcrumbs",
	'<a class="breadcrumb">Mensajes</a>
	<a class="breadcrumb">Distinciones</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_mensajes").addClass("active");
		$("#mensajes_accion_distinciones").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<div class="row">

	<?php if (in_array($this->Session->read('Auth.User.tipo'), array('Alumno', 'Madre', 'Padre'))): ?>

		<div id="todos_recibidos" class="col s12">
			<?php include 'index_recibidos.ctp';?>
		</div>

	<?php else: ?>

		<div id="todos_enviados" class="col s12">
			<?php include 'index_enviados.ctp';?>
		</div>

	<?php endif ?>	
</div>