<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>Planilla UTEC</title>

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
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
<body class="login_page">
<div id="wrapper">
	<div id="login_box" class="content">
    <h1 style="color: grey;"><?php echo $message; ?></h1>
</div></div></body></html>