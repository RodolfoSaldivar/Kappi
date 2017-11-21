

<?php $this->assign('title', 'Mamás - Kappi');  ?>

<?php $this->set('titulo_vista', "Mamás"); ?>
<?php $this->set("breadcrumbs",
	'<a class="breadcrumb">Usuarios</a>
	<a class="breadcrumb">Mamás</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_usuarios").addClass("active");
		$("#usuarios_accion_madres").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>

<?php //Vista que se reusa para todos los tipos de usuario ?>
<?php include 'index_usuarios.ctp';?>