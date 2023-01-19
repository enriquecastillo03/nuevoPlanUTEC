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

