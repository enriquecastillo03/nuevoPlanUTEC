<!DOCTYPE html>
<html>

<!-- Mirrored from z-themes.net/skate/demo1/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 08 Nov 2012 19:46:34 GMT -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>Planilla UTEC | Warsoft</title>

<link rel="shortcut icon" href="<?php echo base_url();?>media/imagenes/sistema/favicon.ico" />
<link rel="apple-touch-icon" href="<?php echo base_url();?>media/imagenes/sistema/apple-touch-icon.png" />

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
	'placeholder' => 'Usuario'
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
	'class' => 'password',
	'placeholder' => 'Contraseña'
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<body class="login_page">	
<div id="wrapper">
	<div id="login_box" class="content">
		<img src="<?php echo base_url();?>/media/imagenes/index/logo_utec.jpg" style="width:30%; display: block;margin-left: auto;margin-right: auto"><br>
		<?php echo form_open($this->uri->uri_string()); ?>
			<p>
				
		
		<?php echo form_input($login); ?>
		<small style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></small>
	
		<?php echo form_password($password); ?>
		<small style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></small>
	

	<?php if ($show_captcha) {
		if ($use_recaptcha) { ?>
		<table>
	<tr>
		<td colspan="2">
			<div id="recaptcha_image"></div>
		</td>
		<td>
			<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
			<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="recaptcha_only_if_image">Enter the words above</div>
			<div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
		</td>
		<td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
		<td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
		<?php echo $recaptcha_html; ?>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan="3">
			<p>Enter the code exactly as it appears:</p>
			<?php echo $captcha_html; ?>
		</td>
	</tr>
	<tr>
		<td><?php echo form_label('Codigo de confirmacion', $captcha['id']); ?></td>
		<td><?php echo form_input($captcha); ?></td>
		<td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
	</tr>
	<?php }
	} ?>

	<tr>
		<td colspan="3">
			
		</td>
	</tr>
</table>
</p>
			<p>
<?php echo form_submit('submit', 'Ingresar'); ?>

			</p>
<?php echo form_close(); ?>
	</div>
</div>
</body>

<!-- Mirrored from z-themes.net/skate/demo1/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 08 Nov 2012 19:46:34 GMT -->
</html>