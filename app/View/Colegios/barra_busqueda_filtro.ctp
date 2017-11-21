

<tbody id="filtro_a_cambiar">
	<?php foreach ($colegios as $key => $colegio): ?>
	<?php if ($this->Session->read('mi_colegio') == $colegio['Colegio']['id'] || $this->Session->read('Auth.User.tipo') == "Superadministrador"): ?>
			
		<tr>
			<td width="130" class="hide-on-med-and-down" style="padding-right:30px;">
				<img class="responsive-img materialboxed" src="<?php echo $colegio['Imagene']['ruta'] ?><?php echo $colegio['Imagene']['nombre'] ?>">
			</td>
			<td onclick="window.location = 'colegios/datos/<?php echo $colegio["Colegio"]["encriptada"] ?>'">
				<a href="colegios/datos/<?php echo $colegio["Colegio"]["encriptada"] ?>">
					<?php echo $colegio["Colegio"]["nombre_comercial"] ?>
				</a>
			</td>
			<td onclick="window.location = 'colegios/datos/<?php echo $colegio["Colegio"]["encriptada"] ?>'"><?php echo $colegio["Colegio"]["nombre_corto"] ?></td>
			<td onclick="window.location = 'colegios/datos/<?php echo $colegio["Colegio"]["encriptada"] ?>'"><?php echo $colegio["Colegio"]["telefono"] ?></td>
			<td class="center" id="poner_switch_<?php echo $colegio["Colegio"]["encriptada"] ?>">
				<div class="switch">
					<label>
						No
						<input <?php if ($colegio["Colegio"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Activo][<?php echo $colegio["Colegio"]["encriptada"] ?>]' id='Activo<?php echo $colegio["Colegio"]["encriptada"] ?>' value='<?php echo $colegio["Colegio"]["activo"] ?>'>
						<span class="lever"></span>
						Si
					</label>
				</div>
			</td>
			<td class="center ver_editar_acciones">
				<!-- <a href="colegios/datos/<?php echo $colegio["Colegio"]["encriptada"] ?>">
					<i class="material-icons">visibility</i>
				</a>
				<br class="hide-on-med-and-down"> -->
				<a class="hide-on-small-only" href="colegios/editar/<?php echo $colegio["Colegio"]["encriptada"] ?>">
					<i class="material-icons">edit</i>
				</a>
			</td>
		</tr>
	
	<?php endif ?>
	<?php endforeach ?>
</tbody>