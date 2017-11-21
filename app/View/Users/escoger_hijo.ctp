

<?php $this->assign('title', 'Escoger Hijo - Kappi');  ?>

<div class="row" style="margin-top:30px;">
	<div class="card-panel col l6 offset-l3 m8 offset-m2 s10 offset-s1" style="padding-top:20px;">
		
		<div class="row center blanco" style="font-size: 30px;">
			Seleccionar Hijo
		</div>
		
		<div class="row center blanco">
			Verá la información del hijo que seleccione. 
		</div>
		
		<?php echo $this->Form->create("User") ?>

			<?php echo $this->Form->hidden('id'); ?>

			<div class="contenedor">
				<table class="bordered centered">
					<thead>
						<tr>
							<th class="blanco">Foto</th>
							<th class="padding_left_no_small blanco">Nombre</th>
						</tr>
					</thead>

					<tbody id="filtro_a_cambiar">
						<?php foreach ($hijos as $key => $hijo): ?>
							<tr style="cursor:pointer;" id="user_<?php echo $hijo["User"]["id"] ?>">
								<td class="logo_small_menor">
									<?php if (!empty($hijo['User']['imagene_id'])): ?>
										<img class="responsive-img circle" src="<?php echo $hijo['Imagene']['ruta'] ?><?php echo $hijo['Imagene']['nombre'] ?>">
									<?php else: ?>
										<img class="responsive-img circle" src="/img/default_user.png">
									<?php endif ?>
								</td>
								<td class="padding_left_no_small complementario">
									<?php echo $hijo["User"]["nombre"] ?>
									<?php echo $hijo["User"]["a_paterno"] ?>
									<?php echo $hijo["User"]["a_materno"] ?>
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

	<?php foreach ($hijos as $key => $hijo): ?>
		
		$(document).on("click", "#user_<?php echo $hijo["User"]["id"] ?>", function()
		{
			$("#UserId").val("<?php echo $hijo["User"]["id"] ?>")
			$("#UserEscogerHijoForm").submit();
		});

	<?php endforeach ?>

<?php $this->Html->scriptEnd(); ?>