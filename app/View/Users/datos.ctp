

<?php $this->assign('title', $usuario["User"]["nombre"].' '.$usuario["User"]["a_paterno"].' '.$usuario["User"]["a_materno"].' - Kappi');  ?>

<?php $this->set('titulo_vista', $this->Session->read("tipo_bread")); ?>
<?php $this->set("breadcrumbs",
	'<a href="'.$this->Session->read("una_pag_antes").'" class="breadcrumb">'.$this->Session->read("tipo_bread").'</a>
	<a class="breadcrumb">'.$usuario["User"]["nombre"].' '.$usuario["User"]["a_paterno"].' '.$usuario["User"]["a_materno"].'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_usuarios").addClass("active");
		$("#usuarios_accion_<?php echo substr($this->Session->read("una_pag_antes"), 1) ?>").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<div class="contenedor">
	<div class="titulo_en_datos">
		<table>
			<tr>
				<td width="100">
					<?php if (!empty($usuario['User']['imagene_id'])): ?>
						<img class="responsive-img materialboxed circle" src="<?php echo $usuario['Imagene']['ruta'] ?><?php echo $usuario['Imagene']['nombre'] ?>">
					<?php else: ?>
						<img class="responsive-img materialboxed circle" src="/img/default_user.png">
					<?php endif ?>
				</td>
				<td>
					<?php echo $usuario["User"]["nombre"] ?>
					<?php echo $usuario["User"]["a_paterno"] ?>
					<?php echo $usuario["User"]["a_materno"] ?>
				</td>
			</tr>
		</table>
	</div>
</div>

<div id="todos_los_datos" class="contenedor">
	<div class="row">

		<div class="col s12 m6">
			<div class="bg_blanco">

				<ul class="collection with-header">
					<li class="collection-header">
						<h5>Identificador</h5>
					</li>
					<li class="collection-item">
						<div>
							<?php echo $usuario["User"]["identificador"] ?>
						</div>
					</li>
				</ul>
				<ul class="collection with-header">
					<li class="collection-header">
						<h5>Usuario</h5>
					</li>
					<li class="collection-item">
						<div>
							<?php echo $usuario["User"]["username"] ?>
						</div>
					</li>
				</ul>

				<?php if (!empty($usuario["User"]["correo"])): ?>
					<ul class="collection with-header">
						<li class="collection-header">
							<h5>Correo</h5>
						</li>
						<li class="collection-item">
							<div>
								<?php echo $usuario["User"]["correo"] ?>
							</div>
						</li>
					</ul>
				<?php endif ?>

				<?php if (!empty($usuario["User"]["celular"])): ?>
					<ul class="collection with-header">
						<li class="collection-header">
							<h5>Celular</h5>
						</li>
						<li class="collection-item">
							<div>
								<?php echo $usuario["User"]["celular"] ?>
							</div>
						</li>
					</ul>
				<?php endif ?>
			</div>
		</div>

		<?php if (!empty($miembros)): ?>
					
			<div class="col s12 m6" id="datos_niveles">
				<div class="bg_blanco">

					<ul class="collection with-header">
						<li class="collection-header">
							<h5>Familia <?php echo $usuario['Familia']['nombre'] ?></h5>
							ID: <?php echo $usuario['Familia']['identificador'] ?>
						</li>
					</ul>

					<table style="margin-top:7.5px;">
						<tbody>

							<?php foreach ($miembros as $key => $miembro): ?>
								
								<tr>
									<td width="100" style="padding-right:30px;">
										<?php if (!empty($miembro['User']['imagene_id'])): ?>
											<img class="responsive-img materialboxed circle" src="<?php echo $miembro['Imagene']['ruta'] ?><?php echo $miembro['Imagene']['nombre'] ?>">
										<?php else: ?>
											<img class="responsive-img materialboxed circle" src="/img/default_user.png">
										<?php endif ?>
									</td>
									<td>
										<?php echo $miembro['User']['a_paterno'] ?>
										<?php echo $miembro['User']['a_materno'].", " ?>
										<?php echo $miembro['User']['nombre'] ?>
									</td>
								</tr>

							<?php endforeach ?>
							
						</tbody>
					</table>

				</div>
			</div>

		<?php endif ?>

	</div>
</div>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function(){
		$('.materialboxed').materialbox();
	});

<?php $this->Html->scriptEnd(); ?>