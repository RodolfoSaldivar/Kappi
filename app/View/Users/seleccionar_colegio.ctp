

<?php $this->assign('title', 'Seleccionar Colegio - Kappi');  ?>

<div class="row" style="margin-top:30px;">
	<div class="card-panel col l6 offset-l3 m8 offset-m2 s10 offset-s1" style="padding-top:20px;">
		
		<div class="row center blanco" style="font-size: 30px;">
			Seleccionar Colegio
		</div>
		
		<div class="row center blanco">
			Verá la información como si fuera administrador del colegio que escoga. 
		</div>
		
		<?php echo $this->Form->create("Colegio") ?>

			<?php echo $this->Form->hidden('id'); ?>

			<div class="contenedor">
				<table class="bordered centered">
					<thead>
						<tr>
							<th class="blanco">Logo</th>
							<th class="padding_left_no_small blanco">Nombre</th>
						</tr>
					</thead>

					<tbody id="filtro_a_cambiar">
						<?php foreach ($colegios as $key => $colegio): ?>
							<tr style="cursor:pointer;" id="colegio_<?php echo $colegio["Colegio"]["id"] ?>">
								<td class="logo_small_menor">
									<img class="responsive-img" src="<?php echo $colegio['Imagene']['ruta'] ?><?php echo $colegio['Imagene']['nombre'] ?>">
								</td>
								<td class="padding_left_no_small complementario">
									<?php echo $colegio["Colegio"]["nombre_comercial"] ?>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>

		<?php echo $this->Form->end(); ?>
	</div>
</div>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	<?php foreach ($colegios as $key => $colegio): ?>
		
		$(document).on("click", "#colegio_<?php echo $colegio["Colegio"]["id"] ?>", function()
		{
			$("#ColegioId").val("<?php echo $colegio["Colegio"]["id"] ?>")
			$("#ColegioSeleccionarColegioForm").submit();
		});

	<?php endforeach ?>

<?php $this->Html->scriptEnd(); ?>