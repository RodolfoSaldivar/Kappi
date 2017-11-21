

<div class="row" id="alumnos_especificos_cambiar">
	<div class="col s10 offset-s2">

		<div class="row margin_nada padding_bottom">
			<?php foreach ($alumnos as $key => $alumno): ?>

				<label class="error validation_label <?php if($key != 0) echo "hide" ?>" for="data[Alumnos][<?php echo $alumno["User"]["encriptada"] ?>]"></label>
				
			<?php endforeach ?>
		</div>

		<?php foreach ($alumnos as $key => $alumno): ?>

			<input class="alumnos_salon" type="checkbox" id="alumno_<?php echo $alumno["User"]["encriptada"] ?>" name="data[Alumnos][<?php echo $alumno["User"]["encriptada"] ?>]" />
			<label for="alumno_<?php echo $alumno["User"]["encriptada"] ?>">
				<?php echo $alumno["User"]["a_paterno"] ?>
				<?php echo $alumno["User"]["a_materno"].", " ?>
				<?php echo $alumno["User"]["nombre"] ?>
			</label>
			<br>

		<?php endforeach ?>
		<br>
	</div>
</div>