

<?php $this->assign('title', $mensaje["Comunicado"]["asunto"]);  ?>

<?php if ($this->Session->read("status_mensaje") == "Recibido"): ?>
	<?php $this->set('titulo_vista', "Recibido"); ?>
<?php else: ?>
	<?php $this->set('titulo_vista', "Enviado"); ?>
<?php endif ?>

<?php $this->set("breadcrumbs",
	'<a class="breadcrumb">Mensajes</a>
	<a href="'.$this->Session->read("una_pag_antes").'" class="breadcrumb">'.$this->Session->read("tipo_bread").'</a>'
) ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	<?php $accion = "#mensajes_accion_".substr($this->Session->read("una_pag_antes"), 1); ?>
	$(document).ready(function() {
		$("#accion_de_mensajes").addClass("active");
		$("<?php echo $accion ?>").addClass("active");
	});

<?php $this->Html->scriptEnd(); ?>


<?php if (in_array($this->Session->read('Auth.User.tipo'), array("Administrador", "Maestro")) && $mensaje['Comunicado']['user_id'] == $this->Session->read('Auth.User.id')): ?>
	<?php if ($mensaje["Comunicado"]["guardado"] == 1): ?>
		<div class="contenedor">
			<div class="row azul_4" style="font-weight:bold;">
				* Mensaje guardado
			</div>
		</div>
	<?php endif ?>
<?php endif ?>


<?php if (in_array($this->Session->read('Auth.User.tipo'), array("Maestro", "Administrador")) && $mensaje['Comunicado']['user_id'] == $this->Session->read('Auth.User.id')): ?>
	<?php if ($mensaje["Comunicado"]["guardado"] == 0): ?>
		<div class="contenedor">
			<div class="row azul_4" style="font-weight:bold;">
				<a href="/guardar_mensaje/<?php echo $comunicado_id ?>" class="waves-effect waves-light btn">Guardar Mensaje</a>
			</div>
		</div>
	<?php endif ?>
<?php endif ?>

<div class="bg_blanco">
	<div class="contenedor">
		<div class="row azul_4 asunto">
			<?php echo $mensaje["Comunicado"]["asunto"] ?>
		</div>	
	</div>
</div>

<div class="bg_blanco">	
	<div class="contenedor">
		<div class="row azul_4 mensaje">
			<?php echo $mensaje["Comunicado"]["mensaje"] ?>
		</div>	
	</div>
</div>

<div class="row">
	<?php foreach ($todos_pdf as $key => $pdf): ?>
		<div class="col s2" style="margin-bottom:20px;">
			<img class="imagenes_descargar col s7" src="/img/img_pdf.png">
			<a href="/descargar_pdf/<?php echo $pdf['Archivo']['encriptada'] ?>" target="_blank">
				<i class="material-icons btn_descarga" style="margin-bottom:10px;">visibility</i>
			</a><br>
			<a href="/descargar_pdf/<?php echo $pdf['Archivo']['encriptada'] ?>/1">
				<i class="material-icons btn_descarga">file_download</i>
			</a>
		</div>
	<?php endforeach ?>
</div>


<div class="row">
	<?php foreach ($imagenes as $key => $imagen): ?>
		<div class="col s2" style="margin-bottom:20px;">
			<img class="imagenes_descargar materialboxed col s7" src="<?php echo $imagen[0]['ruta'] ?><?php echo $imagen[0]['nombre'] ?>">
			<a href="/descargar_imagenes/<?php echo $imagen[0]['encriptada'] ?>">
				<i class="material-icons btn_descarga">file_download</i>
			</a>
		</div>
	<?php endforeach ?>
</div>

<?php $esta_firmado = $recibido_por["Destinatario"]["firmado"] ?>
<?php if ($this->Session->read("status_mensaje") == "Recibido" && $esta_firmado != 2): ?>
	
	<div class="row">
		<div class="col m12">
			<a <?php if ($esta_firmado == 0) echo 'href="/firmar_mensaje/'.$comunicado_id.'"' ?> class="waves-effect waves-light btn <?php if ($esta_firmado == 1) echo "disabled" ?>">
				<i class="material-icons right blanco">done_all</i>
				Firmar
			</a>
		</div>
	</div>

<?php endif ?>


<?php if ($this->Session->read("status_mensaje") == "Enviado"): ?>
			
	<div class="divider_3"></div>
	<br>

	<div class="bg_blanco">	
		<div class="contenedor">

			<div class="row azul_4">
				<div style="padding-top:10px; font-size:30px;">
					Destinatarios
				</div>
			</div>

			<div class="row margin_nada">
				<div class="col s10 offset-s1 offset-m1">
					<?php include 'barra_busqueda_destinatarios.ctp';?>
				</div>
			</div>

			<table class="responsive-table bordered">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Rol</th>
						<th>Visto</th>
						<th>Firmado</th>
					</tr>
				</thead>

				<tbody id="filtro_a_cambiar">
					<?php foreach ($destinatarios as $key => $destinatario): ?>
						<tr class="cursor_normal">
							<td>
								<?php echo $destinatario[0]["a_paterno"] ?>
								<?php echo $destinatario[0]["a_materno"].", " ?>
								<?php echo $destinatario[0]["nombre"] ?>
							</td>
							<td><?php echo $destinatario[0]["tipo"] ?></td>
							<td>
								<?php if ($destinatario[0]["visto"] == 0): ?>
									No
								<?php else: ?>
									<?php echo $destinatario[0]["fecha_visto"]." / ".$destinatario[0]["visto"] ?>
								<?php endif ?>
							</td>
							<td>
								<?php if ($destinatario[0]["firmado"] == 0): ?>
									No
								<?php endif ?>
								<?php if ($destinatario[0]["firmado"] == 1): ?>
									<?php echo $destinatario[0]["fecha_firmado"] ?>
								<?php endif ?>
								<?php if ($destinatario[0]["firmado"] == 2): ?>
									---
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>

<?php endif ?>