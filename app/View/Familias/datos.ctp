

<?php $this->assign('title', $familia["Familia"]["nombre"].' - Kappi'); ?>

<?php $this->set('titulo_vista', "Familias"); ?>
<?php $this->set("breadcrumbs",
	'<a href="/familias" class="breadcrumb">Familias</a>
	<a class="breadcrumb">'.$familia["Familia"]["nombre"].'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_familias").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<div class="contenedor">
	<div class="titulo_en_datos">
		<?php echo $familia["Familia"]["nombre"] ?>
		<span style="font-size:15px; padding-left:20px;">
			ID: <?php echo $familia["Familia"]["identificador"] ?>
		</span>
	</div>
</div>

<div class="contenedor">
	<div class="bg_blanco row">
		<div class="col s12 l10">
			<table>

				<?php foreach ($familia['User'] as $key => $usuario): ?>
					<tr>
						<td width="100">
							<?php if (!empty($usuario['imagene_id'])): ?>
								<img class="responsive-img materialboxed circle" src="<?php echo $usuario['Imagene']['ruta'] ?><?php echo $usuario['Imagene']['nombre'] ?>">
							<?php else: ?>
								<img class="responsive-img materialboxed circle" src="/img/default_user.png">
							<?php endif ?>
						</td>
						<td class="miembros_nombres">
							<?php echo $usuario["a_paterno"] ?>
							<?php echo $usuario["a_materno"].", " ?>
							<?php echo $usuario["nombre"] ?>
						</td>
						<td class="miembros_nombres hide-on-med-and-down">
							<?php echo $usuario["tipo"] ?>
						</td>
						<td class="miembros_nombres hide-on-med-and-down">
							<?php if ($usuario['activo'] == 1): ?>
								<div>
									<i class="material-icons left">check</i>Activo
								</div>
							<?php else: ?>
								<div>
									<i class="material-icons left">clear</i>Inactivo
								</div>
							<?php endif ?>
						</td>
					</tr>
				<?php endforeach ?>
					
			</table>
		</div>	
	</div>
</div>