

<?php $this->assign('title', $this->Session->read("tipo_bread").' - Kappi'); ?>

<?php $this->set('titulo_vista', $this->Session->read('tipo_bread')); ?>
<?php $this->set("breadcrumbs",
	'<a href="'.$this->Session->read("una_pag_antes").'" class="breadcrumb">'.$this->Session->read("tipo_bread").'</a>
	<a class="breadcrumb">'.$fecha.'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_mensajes").addClass("active");
		$("#mensajes_accion_<?php echo substr($this->Session->read("una_pag_antes"), 1) ?>").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<div class="contenedor">
	<div class="titulo_en_datos">
		<?php echo $fecha ?>
	</div>
</div>


<div class="contenedor">
	<div class="bg_blanco row">
		<div class="col s12">
			<table class="bordered highlight">
				<thead>
					<tr>
						<th>Nombre</th>
						<th><?php echo $this->Session->read('tipo_bread') ?></th>
					</tr>
				</thead>

				<tbody id="filtro_a_cambiar_enviados">
					<?php foreach ($alumnos as $keyAl => $alumno): ?>

						<tr>
							<td width="500">
								<?php echo $alumno[0]["a_paterno"] ?>
								<?php echo $alumno[0]["a_materno"].", " ?>
								<?php echo $alumno[0]["nombre"] ?>
							</td>
							<td>
								<?php foreach ($alumno['Extras'] as $keyEx => $extra): ?>
									<table>
									<tr>
										<td valign="center" width="75">
											<img width="75" src="<?php echo $extra[0][0]["ruta"].$extra[0][0]["nombre"] ?>">
										</td>
										<td valign="center">
											<?php echo $extra[0][0]['descripcion'] ?>
										</td>
									</tr>
								</table>
								<?php endforeach ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>	
	</div>
</div>