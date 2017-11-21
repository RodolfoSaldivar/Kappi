

<div class="bg_blanco">	

	<!-- Barra de busqueda y boton de agregar -->
	<div class="contenedor">	
		<div class="row margin_nada" style="padding-top:10px;">
			<div class="col s10 offset-s1 m10">
				<?php //include 'barra_busqueda_recibidos.ctp';?>
			</div>
		</div>
	</div>


	<!-- Donde viene la tabla de informacion -->
	<div class="contenedor">
		<table class="bordered highlight">
			<thead>
				<tr>
					<th>Fecha</th>
					<th><?php echo $this->Session->read('tipo_bread') ?></th>
				</tr>
			</thead>

			<tbody id="filtro_a_cambiar_recibidos">
				<?php foreach ($recibidos as $keyRe => $recibido): ?>
					<tr class="cursor_normal">
						<td>
							<?php echo $recibido["Fecha"] ?>
						</td>
						<td>
							<?php foreach ($recibido['Extras'] as $keyEx => $extra): ?>
								<table>
									<tr class="cursor_normal">
										<td valign="center" width="75">
											<img width="75" src="<?php echo $extra["Imagene"]["ruta"].$extra["Imagene"]["nombre"] ?>">
										</td>
										<td valign="center">
											<?php echo $extra['Extra']['descripcion'] ?>
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