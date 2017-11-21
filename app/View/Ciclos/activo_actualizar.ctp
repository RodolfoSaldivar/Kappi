

<div class="switch">
	<label>
		No
		<input <?php if ($ciclo["Ciclo"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Activo][<?php echo $ciclo["Ciclo"]["encriptada"] ?>]' id='Activo<?php echo $ciclo["Ciclo"]["encriptada"] ?>' value='<?php echo $ciclo["Ciclo"]["activo"] ?>'>
		<span class="lever"></span>
		Si
	</label>
	<div class="ciclo_mensaje hide"><?php echo @$ciclo['mensaje'] ?></div>
</div>