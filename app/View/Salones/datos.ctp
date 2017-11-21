

<?php $this->assign('title', 'Salón '.$salon["Salone"]["nombre"].' - Kappi');  ?>

<?php $this->set('titulo_vista', 'Salón'); ?>
<?php $this->set("breadcrumbs",
	'<a href="/ciclos" class="breadcrumb">Ciclos</a>
	<a href="/salones_index/'.$salon["Salone"]["ciclo_id"].'" class="breadcrumb">'.$ciclo["Ciclo"]["nombre"].'</a>
	<a class="breadcrumb">'.$salon["Salone"]["nombre"].'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$("#accion_de_ciclos").addClass("active");
	});

	$(document).ready(function(){
		$('.materialboxed').materialbox();
	});

<?php $this->Html->scriptEnd(); ?>


<div class="contenedor">
	<div class="titulo_en_datos" id="bread_datos_salon">
		<a class="breadcrumb"><?php echo $salon["Ciclo"]["nombre"] ?></a>
        <a class="breadcrumb"><?php echo $grado["Nivele"]["nombre"] ?></a>
        <a class="breadcrumb"><?php echo $salon["Grado"]["nombre"] ?></a>
        <a class="breadcrumb"><?php echo $salon["Salone"]["nombre"] ?></a>
	</div>
</div>


<div class="bg_blanco">
	<div class="contenedor">

		<div class="row margin_nada azul_4">
			<div style="padding:10px 0 0 20px; font-size:30px;">
				Profesor
			</div>
		</div>

		<div class="row">
			<table>
				<tr>
					<td width="150">
						<?php if (empty($profesor['User']['imagene_id'])): ?>
							<img class="circle left responsive-img materialboxed" src="/img/default_user.png">
						<?php else: ?>
							<img class="circle left responsive-img materialboxed" src="<?php echo $profesor['Imagene']['ruta'] ?><?php echo $profesor['Imagene']['nombre'] ?>">
						<?php endif ?>
					</td>
					<td class="miembros_nombres">
						<?php echo $profesor["User"]["a_paterno"] ?>
						<?php echo $profesor["User"]["a_materno"].", " ?>
						<?php echo $profesor["User"]["nombre"] ?>
					</td>
				</tr>
			</table>
		</div>

	</div>
</div>

<div class="bg_blanco">
	<div class="contenedor">

		<div class="row margin_nada azul_4">
			<div style="padding:10px 0 0 20px; font-size:30px;">
				Alumnos Inscritos = <?php echo count($inscritos) ?>
			</div>
		</div>

		<div class="row">
			<table>

				<?php foreach ($inscritos as $key => $alumno): ?>
					<tr>
						<td width="150">
							<?php if (empty($alumno['User']['imagene_id'])): ?>
								<img class="circle left responsive-img materialboxed" src="/img/default_user.png">
							<?php else: ?>
								<img class="circle left responsive-img materialboxed" src="<?php echo $alumno['Imagene']['ruta'] ?><?php echo $alumno['Imagene']['nombre'] ?>">
							<?php endif ?>
						</td>
						<td class="miembros_nombres">
							<?php echo $alumno["User"]['a_paterno'] ?>
							<?php echo $alumno["User"]['a_materno'].", " ?>
							<?php echo $alumno["User"]['nombre'] ?>
						</td>
					</tr>
				<?php endforeach ?>

			</table>
		</div>

	</div>
</div>