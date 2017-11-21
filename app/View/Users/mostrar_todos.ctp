

<?php $this->assign('title', $this->Session->read("tipo_bread").' - Kappi');  ?>

<?php $this->set('titulo_vista', $this->Session->read("tipo_bread")); ?>
<?php $this->set("breadcrumbs",
	'<a class="breadcrumb">Usuarios</a>
	<a class="breadcrumb">'.$this->Session->read("tipo_bread").'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_usuarios").addClass("active");
		$("#usuarios_accion_<?php echo substr($this->Session->read("una_pag_antes"), 1) ?>").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>

<?php //Vista que se reusa para todos los tipos de usuario ?>
<?php include 'index_usuarios.ctp';?>