

<?php $this->assign('title', $colegio["Colegio"]["nombre_corto"].' - Kappi'); ?>

<?php $this->set('titulo_vista', "Colegios"); ?>
<?php $this->set("breadcrumbs",
	'<a href="/colegios" class="breadcrumb">Colegios</a>
	<a class="breadcrumb">'.$colegio["Colegio"]["nombre_corto"].'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_colegios").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<div class="contenedor">
	<div class="titulo_en_datos">
		<table>
			<tr>
				<td width="90">
					<img class="left responsive-img materialboxed" src="<?php echo $colegio['Imagene']['ruta'] ?><?php echo $colegio['Imagene']['nombre'] ?>">
				</td>
				<td><?php echo $colegio["Colegio"]["nombre_comercial"] ?></td>
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
						<h5>Nombre Corto</h5>
					</li>
					<li class="collection-item">
						<div>
							<?php echo $colegio["Colegio"]["nombre_corto"] ?>
						</div>
					</li>
				</ul>

				<ul class="collection with-header">
					<li class="collection-header">
						<h5>Teléfono</h5>
					</li>
					<li class="collection-item">
						<div>
							<?php echo $colegio["Colegio"]["telefono"] ?>
						</div>
					</li>
				</ul>

				<ul class="collection with-header" style="padding-bottom:15px;">
					<li class="collection-header">
						<h5>Razón Social</h5>
					</li>
					<li class="collection-item">
						<div>
							<?php echo $colegio["Colegio"]["razon_social"] ?>
						</div>
					</li>
				</ul>

			</div>
		</div>

		<div class="col s12 m6" id="datos_niveles">
			<div class="bg_blanco">

				<ul class="collection with-header">
					<li class="collection-header">
						<div class="row margin_nada">
							<div class="col s12">
								<h5>Niveles</h5>
							</div>
						</div>
						
					</li>

					<li class="collection-item">
						<ul class="collapsible" data-collapsible="expandable">

							<?php foreach ($colegio['Nivele'] as $key => $nivel): ?>
								<li>
									<div class="collapsible-header active">
										<?php echo $nivel['nombre'] ?>
									</div>
									<div class="collapsible-body">
										<div class="row margin_nada">	
											<div class="col s12">	
												<ul class="collection">
													<?php foreach ($nivel['grados'] as $llave => $grado): ?>
														<li class="collection-item">
															<?php echo $grado['Grado']['nombre']; ?>
															<span style="padding-left:30%;">
																id: <?php echo $grado['Grado']['identificador']; ?>
															</span>
														</li>
													<?php endforeach ?>
											    </ul>
											</div>
										</div>
									</div>
								</li>
							<?php endforeach ?>
								
						</ul>
					</li>
				</ul>

			</div>
		</div>

	</div>
</div>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function(){
		$('.materialboxed').materialbox();
	});

<?php $this->Html->scriptEnd(); ?>