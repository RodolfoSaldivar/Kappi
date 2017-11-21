

<div class="switch">
	<label>
		No
		<input <?php if ($alumno["User"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Activo][<?php echo $alumno["User"]["encriptada"] ?>]' id='Activo<?php echo $alumno["User"]["encriptada"] ?>' value='<?php echo $alumno["User"]["activo"] ?>'>
		<span class="lever"></span>
		Si
	</label>
</div>