

<?php $this->assign('title', 'No Autorizado - Kappi'); ?>

<div class="contenedor">
	<br><br>
	<div class="row">
		<table class="card-panel red col s12 m8 offset-m2">
			<tr>
				<td class="center">
					<i class="material-icons large blanco">warning</i>
				</td>
				<td class="no_aut center">
					<?php echo $this->Session->read("mensaje_autorizacion"); ?>
				</td>
			</tr>
		</table>
	</div>
</div>