<?php
	$this->set_css($this->default_theme_path.'/satelite/css/flexigrid.css');
	$this->set_js($this->default_theme_path.'/satelite/js/flexigrid.js');
	$this->set_js($this->default_theme_path.'/satelite/js/jquery.form.js');
?>
<script type='text/javascript'>
	var base_url = '<?php echo base_url();?>';

	var subject = '<?php echo $subject?>';
	var ajax_list_info_url = '<?php echo $ajax_list_info_url?>';
	var unique_hash = '<?php echo $unique_hash; ?>';

	var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";

</script>
<div id="hidden-operations"></div>
<div id='report-error' class='report-div error'></div>
<div id='report-success' class='report-div success report-list' <?php if($success_message !== null){?>style="display:block"<?php }?>><?php 
if($success_message !== null){?>
	<p><?php echo $success_message; ?></p>
<?php }
?></div>	
<div class="satelite" style='width: 100%;'>
	<div id='main-table-box'>
	
	<?php if(!$unset_add || !$unset_export || !$unset_print){?>
	<div class="tDiv">
		<div class="tDiv2">
			<?php if(!$unset_add){?>		
			<a class="button dodger small" href="<?php echo $add_url?>" title="<?php echo $this->l('list_add'); ?> <?php echo $subject?>"><strong><?php echo $this->l('list_add'); ?> <?php echo $subject?></strong></a>		
			<?php }?>
		</div>
		<div class="tDiv3">
			<?php if(!$unset_export) { ?>
        	<a class="export-anchor" data-url="<?php echo $export_url; ?>" target="_blank">
				<div class="fbutton">
					<div>
						<span class="export"><?php echo $this->l('list_export');?></span>
					</div>
				</div>
            </a>
			<div class="btnseparator"></div>
			<?php } ?>
			<?php if(!$unset_print) { ?>
        	<a class="print-anchor" data-url="<?php echo $print_url; ?>">
				<div class="fbutton">
					<div>
						<span class="print"><?php echo $this->l('list_print');?></span>
					</div>
				</div>
            </a>
			<div class="btnseparator"></div>
			<?php }?>						
		</div>
		<div class='clear'></div>
	</div>
	<?php }?>
	
	<div id='ajax_list'>
		<?php echo $list_view?>
	</div>
	</div>
</div>
