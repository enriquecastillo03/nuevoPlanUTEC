<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.accordion.js"></script>
<?php  	
	$this->set_js($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js($this->default_theme_path.'/flexigrid/js/flexigrid-edit.js');
?>
<div class="flexigrid crud-form" style='width: 100%;'>
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<?php echo $this->l('form_edit'); ?> <?php echo $subject?>
			</div>		
			<div class='clear'></div>				
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>	
	</div>
<div id='main-table-box'>
	<?php echo form_open( $update_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
	<div class='form-div'>
		<?php
		$counter = 0; 
			foreach($fields as $field)
			{
				$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
				$counter++;
		?>
			<div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				
				<div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
					<?php echo $input_fields[$field->field_name]->input?>
				</div>

				<small>
					<?php echo $input_fields[$field->field_name]->display_as?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""?>
				</small>
				<div class='clear'></div>	
			</div>
		<?php }?>
		<?php if(!empty($hidden_fields)){?>
		<!-- Start of hidden inputs -->
			<?php 
				foreach($hidden_fields as $hidden_field){
					echo $hidden_field->input;
				}
			?>
		<!-- End of hidden inputs -->
		<?php }?>		
		<div id='report-error' class='report-div error'></div>
		<div id='report-success' class='report-div success'></div>		
	</div>
	<div class='clear'></div>	
	<div class="pDiv">
		<p>
			<button type='submit' class="button dodger small"><?php echo $this->l('form_update_changes'); ?> </button>
		
<?php 	if(!$this->unset_back_to_list) { ?>			
		
			<button type='button' id="save-and-go-back-button" class="button dodger small"><?php echo $this->l('form_update_and_go_back'); ?></button>
		
			<button type='button' onclick='javascript: return goToList()' class="button dodger small"><?php echo $this->l('form_cancel'); ?></button>
		
<?php 	} ?>				
			</p>
		<div class='clear'></div>	
	</div>
	<?php echo form_close(); ?>
</div>
</div>	
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>