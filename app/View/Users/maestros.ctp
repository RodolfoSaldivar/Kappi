

<?php $this->assign('title', 'Maestros - Kappi');  ?>

<?php $this->set('titulo_vista', "Maestros"); ?>
<?php $this->set("breadcrumbs",
	'<a class="breadcrumb">Usuarios</a>
	<a class="breadcrumb">Maestros</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_usuarios").addClass("active");
		$("#usuarios_accion_maestros").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>

<?php //Vista que se reusa para todos los tipos de usuario ?>
<?php include 'index_usuarios.ctp';?>