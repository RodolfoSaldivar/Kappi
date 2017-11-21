

<div class="bg_blanco">	

	<!-- Barra de busqueda y boton de agregar -->
	<div class="contenedor">	
		<div class="row margin_nada" style="padding-top:10px;">
			<div class="col s10 offset-s1 m10">
				<?php include 'barra_busqueda_enviados.ctp';?>
			</div>

			<?php if ($this->Session->read('Auth.User.tipo') == "Maestro"): ?>
				<div class="m2 hide-on-small-only">
					<a href="/<?php echo $tipo ?>/mandar" class="bg_complementario right btn-floating btn-large waves-effect waves-light tooltipped" data-position="left" data-delay="50" data-tooltip="Mandar <?php echo $this->Session->read("tipo"); ?>" style="margin-right:10px;">
						<i class="material-icons">add</i>
					</a>
				</div>
			<?php endif ?>
				
		</div>
	</div>


	<!-- Donde viene la tabla de informacion -->
	<div class="contenedor">
		<table class="bordered highlight">
			<thead>
				<tr>
					<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
						<th>Emisor</th>
					<?php endif ?>	
					<th>Fecha</th>
				</tr>
			</thead>

			<tbody id="filtro_a_cambiar_enviados">
				<?php foreach ($enviados as $key => $enviado): ?>

					<tr id="submit<?php echo $enviado['DestinoExtra']['id_encriptada'] ?>">
						<?php if ($user_tipo == "Superadministrador" || $user_tipo == "Administrador"): ?>
							<td>
								<?php echo $enviado["Profe"]["a_paterno"] ?>
								<?php echo $enviado["Profe"]["a_materno"].", " ?>
								<?php echo $enviado["Profe"]["nombre"] ?>
							</td>

						<?php endif ?>
						<td>
							<?php echo $enviado["DestinoExtra"]["fecha"] ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>

<?php foreach ($enviados as $key => $enviado): ?>
	<form id="forma<?php echo $enviado['DestinoExtra']['id_encriptada'] ?>" action="/extras/datos" method="post">
		<input type="hidden" name="emisor" value="<?php echo $enviado['DestinoExtra']['emi_encrip'] ?>">
		<input type="hidden" name="fecha" value="<?php echo $enviado['DestinoExtra']['fecha'] ?>">
	</form>
<?php endforeach ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	<?php foreach ($enviados as $key => $enviado): ?>

		$(document).on("click", "#submit<?php echo $enviado['DestinoExtra']['id_encriptada'] ?>", function()
		{
			$("#forma<?php echo $enviado['DestinoExtra']['id_encriptada'] ?>").submit();
		});

	<?php endforeach ?>

<?php $this->Html->scriptEnd(); ?>