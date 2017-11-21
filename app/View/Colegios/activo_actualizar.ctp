

<div class="switch">
	<label>
		No
		<input <?php if ($colegio["Colegio"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Activo][<?php echo $colegio["Colegio"]["encriptada"] ?>]' id='Activo<?php echo $colegio["Colegio"]["encriptada"] ?>' value='<?php echo $colegio["Colegio"]["activo"] ?>'>
		<span class="lever"></span>
		Si
	</label>
</div>