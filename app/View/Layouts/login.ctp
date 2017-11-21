

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $this->fetch('title'); ?></title>

	<!--Import Google Icon Font-->
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<?php echo $this->Html->css('materialize.min.css'); ?>
	<?php echo $this->Html->css('style.css'); ?>
	<?php echo $this->fetch('css'); ?>

	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body class="bg_azul_3">

	<?php echo $this->fetch('content'); ?>

	<!--Import jQuery before materialize.js-->
	<?php echo $this->Html->script('jquery-2.1.1.min.js'); ?>
	<?php echo $this->Html->script('materialize.min.js'); ?>
	<?php echo $this->Html->script('jquery.validate.min.js'); ?>
	<?php echo $this->Html->script('alphanumeric.js'); ?>
	<?php echo $this->Html->script('equalTo.js'); ?>
	<?php echo $this->fetch('script'); ?>

	<script type="text/javascript">
		
		<?php echo $this->Session->flash('flash', array(
		    'element' => 'mensaje_toast'
		)); ?>

	</script>
			
</body>
</html>


