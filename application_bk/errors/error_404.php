<!DOCTYPE html>
<html>

<!-- Mirrored from z-themes.net/skate/demo1/error.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 08 Nov 2012 19:46:34 GMT -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>Error</title>

<link rel="shortcut icon" href="<?php echo base_url();?>media/imagenes/sistema/favicon.ico" />
<link rel="apple-touch-icon" href="<?php echo base_url();?>media/imagenes/sistema/apple-touch-icon.png" />

<!--Stylesheets-->
<link rel="stylesheet" type='text/css' href="<?php echo base_url()?>stylesheets/style.css"/>
<link rel="stylesheet" type='text/css' href="<?php echo base_url()?>stylesheets/colors.css"/>

<!--Scripts-->
<script type="text/javascript" src="<?php echo base_url()?>scripts/jquery.library.1.7.2.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>scripts/jquery.ui.library.custom.1.8.21.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>scripts/<?php echo base_url()?>jquery.accordion.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>scripts/jquery.tabbed.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>scripts/jquery.placeholder.js"></script>


<!--[if lt IE 9]>
	<script src="scripts/css3-mediaqueries.js"></script>
<![endif]-->

</head>
<body class="error_page">
<div id="wrapper">
	<div id="error_box" class="content">
		<span class="error_line_1"><?php echo $heading; ?></span>
		<span class="error_line_2"><?php echo $message; ?></span>		
		<p>
			<a class="button rounded orange" href="<?php echo base_url()?>index.php"><span class="icon en">(</span><strong>Regresar al inicio</strong></a>
		</p>
	</div>
</div>
</body>

<!-- Mirrored from z-themes.net/skate/demo1/error.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 08 Nov 2012 19:46:34 GMT -->
</html>