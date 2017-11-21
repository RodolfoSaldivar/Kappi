<?php var_dump($this->Session->read("AlumnosParaInscribirFiltro")) ?>

<tbody id="buscar_por_nombre_cambiar">

	<?php foreach ($alumnos as $key => $alumno): ?>
		
		<tr id="mostrar_<?php echo $alumno["User"]["id"] ?>">
			<input type="hidden" value="<?php echo $alumno["User"]["id"] ?>">
			<td width="130" class="hide-on-med-and-down" style="padding-right:30px;">
				<?php if (empty($alumno['User']['imagene_id'])): ?>
					<img class="circle left responsive-img materialboxed" src="/img/default_user.png">
				<?php else: ?>
					<img class="circle left responsive-img materialboxed" src="<?php echo $alumno['Imagene']['ruta'] ?><?php echo $alumno['Imagene']['nombre'] ?>">
				<?php endif ?>
			</td>
			<td>
				<?php echo $alumno["User"]['a_paterno'] ?>
				<?php echo $alumno["User"]['a_materno'].", " ?>
				<?php echo $alumno["User"]['nombre'] ?>
			</td>
			<td>
				<a class="agregar_inscribir waves-effect waves-light btn">
					Inscribir
				</a>
			</td>
		</tr>

	<?php endforeach ?>
	
</tbody>