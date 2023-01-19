<!DOCTYPE html>
<html>
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
<?php echo form_open($this->uri->uri_string()); ?>
<table>
	<tr>
		<td><?php echo form_label('New Password', $new_password['id']); ?></td>
		<td><?php echo form_password($new_password); ?></td>
		<td style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?></td>
		<td><?php echo form_password($confirm_new_password); ?></td>
		<td style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></td>
	</tr>
</table>
<?php echo form_submit('change', 'Change Password'); ?>
<?php echo form_close(); ?>
</div>
</div>
</body>
</html>