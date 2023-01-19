<!DOCTYPE html>
<html>

<!-- Mirrored from z-themes.net/skate/demo1/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 08 Nov 2012 19:46:34 GMT -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>Satelite | Login Page</title>

<link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.ico" />
<link rel="apple-touch-icon" href="<?php echo base_url();?>images/apple-touch-icon.png" />

<!--Stylesheets-->
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/style.css"/>
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/colors.css"/>

<!--Scripts-->
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.library.1.7.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.ui.library.custom.1.8.21.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.accordion.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.tabbed.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.placeholder.js"></script>

<!--[if lt IE 9]>
	<script src="scripts/css3-mediaqueries.js"></script>
<![endif]-->

</head>
<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'class' => 'username',
	'placeholder' => 'Correo'
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
<body class="login_page">
<div id="wrapper">
	<div id="login_box" class="content">
<?php echo form_open($this->uri->uri_string()); ?>
<table>
			
		<?php echo form_input($login); ?>
		<small style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></small>
	
</table>
<?php echo form_submit('reset', 'Obtener nueva contraseña'); ?>
<?php echo form_close(); ?>

</div>
</div>
</body>

<!-- Mirrored from z-themes.net/skate/demo1/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 08 Nov 2012 19:46:34 GMT -->
</html>

