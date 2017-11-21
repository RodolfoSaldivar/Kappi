

<div class="col s6" id="seccionDeAgregado">

	<?php if ($alumnos != "nuevos" && $alumnos != "ciclos"): ?>

		<?php if ($this->request->data["grado"] == "nuevos"): ?>

			<ul class="collection with-header">
				<li class="collection-header"><h4>Alumnos</h4></li>
			</ul>

		<?php else: ?>

			<?php if ($this->request->data["modo"] == 1): ?>

				<ul class="collection with-header" style="height:89.7px; overflow:visible;">
					<li class="collection-header">
						<div class="input-field col s12">
							<select id="seleccion_salones">
								<option value="nada" disabled selected>Salón</option>
								<?php foreach ($salones as $key => $salon): ?>
									<option value="<?php echo $salon["Salone"]["id"] ?>"><?php echo $salon["Salone"]["nombre"] ?></option>
								<?php endforeach ?>
							</select>
							<label>Salón</label>
						</div>
					</li>
				</ul>
						

			<?php else: ?>

				<ul class="collection with-header">
					<li class="collection-header">
						<div class="input-field col s12">
							<i class="material-icons prefix">format_color_text</i>
							<label for="UserNombre">Nombre</label>
							<input type="text" id="UserNombre">
						</div>
					</li>
				</ul>

			<?php endif ?>

		<?php endif ?>
			
		<div>
			<table>
				<tbody id="buscar_por_nombre_cambiar">

					<?php foreach ($alumnos as $key => $alumno): ?>
						
						<?php if ($this->request->data["modo"] == 1 && $this->request->data["grado"] != "nuevos"): ?>
							<tr id="mostrar_<?php echo $alumno[0]["id"] ?>" class="salon_<?php echo $alumno[0]["sal_id"] ?> display_none escondeme">
						<?php else: ?>
							<tr id="mostrar_<?php echo $alumno[0]["id"] ?>">
						<?php endif ?>

							<input type="hidden" value="<?php echo $alumno[0]["id"] ?>">
							<td width="130" class="hide-on-med-and-down" style="padding-right:30px;">
								<?php if (empty($alumno[0]['imagene_id'])): ?>
									<img class="circle left responsive-img materialboxed" src="/img/default_user.png">
								<?php else: ?>
									<img class="circle left responsive-img materialboxed" src="<?php echo $alumno['Imagene']['ruta'] ?><?php echo $alumno['Imagene']['nombre'] ?>">
								<?php endif ?>
							</td>
							<td>
								<?php echo $alumno[0]['a_paterno'] ?>
								<?php echo $alumno[0]['a_materno'].", " ?>
								<?php echo $alumno[0]['nombre'] ?>
							</td>
							<td>
								<a class="agregar_inscribir waves-effect waves-light btn">
									Inscribir
								</a>
							</td>
						</tr>

					<?php endforeach ?>
					
				</tbody>
			</table>
		</div>

	<?php else: ?>

		<ul class="collection with-header">
			<li class="collection-header">
				<h4>
					<i class="left material-icons">warning</i>
					<?php if ($alumnos == "nuevos"): ?>
						No hay alumnos recien inscritos que vayan ahí.
					<?php else: ?>
						No hay datos del ciclo pasado.
					<?php endif ?>
					
				</h4>
			</li>
		</ul>

	<?php endif ?>

</div>