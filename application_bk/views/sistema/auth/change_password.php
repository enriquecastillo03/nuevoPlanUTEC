<?php
$old_password = array(
	'name'	=> 'old_password',
	'id'	=> 'old_password',
	'value' => set_value('old_password'),
	'size' 	=> 30,
);
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
<?php echo form_open($this->uri->uri_string()); ?>
<p>
<?php echo form_password($old_password,'','placeholder="Contraseña Actual"'); ?>
</p>
<p>
<?php echo form_password($new_password,'','placeholder="Nueva Contraseña"'); ?>
</p>
<p>
<?php echo form_password($confirm_new_password,'','placeholder="Confirmar contraseña"'); ?>
</p>
<?php echo validation_errors(); ?>
<?php echo form_submit('change', 'Guardar Cambios'); ?>
<?php echo form_close(); ?>