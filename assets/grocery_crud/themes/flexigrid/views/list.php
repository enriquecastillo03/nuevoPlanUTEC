<?php 
 $url1="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//echo $funcion;
$column_width = (int)(80/count($columns));
	
	if(!empty($list)){
?><div class="bDiv" >
		<table cellspacing="0" cellpadding="0" border="0" id="flex1">
		<thead>
			<tr class='hDiv'>
				<?php foreach($columns as $column){?>
				<th width='<?php echo $column_width?>%'>
					<div class="text-left field-sorting <?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?><?php echo $order_by[1]?><?php }?>" 
						rel='<?php echo $column->field_name?>'>
						<?php echo $column->display_as?>
					</div>
				</th>
				<?php }?>
				<?php if(!$unset_delete || !$unset_edit || !empty($actions) || $texto){?>
				<th align="left" abbr="tools" axis="col1" class="" width='20%'>
					<div class="text-right">
						<?php echo $this->l('list_actions'); ?>
					</div>
				</th>
				<?php }?>
			</tr>
		</thead>		
		<tbody>
<?php foreach($list as $num_row => $row){ ?>        
		<tr  <?php if($num_row % 2 == 1){?>class="erow"<?php }?>>
			<?php foreach($columns as $column){?>
			<td width='<?php echo $column_width?>%' class='<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?>sorted<?php }?>'>
				<div class='text-left'><?php echo $row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;' ; ?></div>
			</td>
			<?php }?>
		

			<?php if(!$unset_delete || !$unset_edit || !empty($actions) ||$texto){?>
			<td align="left" width='20%'>
				<div class='tools'>		
                     
                     	<?php if($texto){?>
                    	<a class="validar" onclick="validar('<?php echo base_url().$funcion;?>','<?php echo $row->custom_f;?>','<?php echo $url1;?>');" href='<?php echo $funcion.'/'.$row->custom_f;?>' title='Custom function' >
                    			<?php echo $texto; ?>
                    	</a>
                    <?php }?>
                    <?php if($texto){?>
                    	<a class="validar" onclick="validar('<?php echo base_url().$funcion1;?>','<?php echo $row->custom_f;?>','<?php echo $url1;?>');" href='<?php echo $funcion1.'/'.$row->custom_f;?>' title='Custom function' >
                    			<?php echo $texto1; ?>
                    	</a>
                    <?php }?>




					<?php if(!$unset_delete){?>
                    	<a href='<?php echo $row->delete_url?>' title='<?php echo $this->l('list_delete')?> <?php echo $subject?>' class="delete-row" >
                    			<span class='delete-icon'></span>
                    	</a>
                    <?php }?>

                    

                    <?php if(!$unset_edit){?>
						<a href='<?php echo $row->edit_url?>' title='<?php echo $this->l('list_edit')?> <?php echo $subject?>'><span class='edit-icon'></span></a>
					<?php }?>
					<?php 
					if(!empty($row->action_urls)){
						foreach($row->action_urls as $action_unique_id => $action_url){ 
							$action = $actions[$action_unique_id];
					?>
							<a href="<?php echo $action_url; ?>" class="<?php echo $action->css_class; ?> crud-action" title="<?php echo $action->label?>"><?php 
								if(!empty($action->image_url))
								{
									?><img src="<?php echo $action->image_url; ?>" alt="<?php echo $action->label?>" /><?php 	
								}
							?></a>		
					<?php }
					}
					?>					
                    <div class='clear'></div>
				</div>
			</td>
			<?php }?>
		</tr>
<?php } ?>        
		</tbody>
		</table>
	</div>
<?php }else{?>
	<br/>
	&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->l('list_no_items'); ?>
	<br/>
	<br/>
<?php }?>	

<script>

	$('.validar').click(function(event) {

        // Stop the Search input reloading the page by preventing its default action
        event.preventDefault();
    });
   

function validar(url, valor,refreshh){

//event.preventDefault();

//alert(valor);

			$.ajax({
			  type: 'POST',
			  url: url,
			  data: {valor:valor},
			  success: function(data){
			//alert(url);
			$.fancybox("<div style='color:black; background-color:white;'>"+data+"</div>");
			   
			  refresh(refreshh);
			  //alert(data);
			  
			  }
			});
		
			}


////No se aprobo la solicitud
/*function no_validar(url, valor,refreshh){
event.preventDefault();
//alert(valor);

$.ajax({
  type: 'POST',
  url: url,
  data: {valor:valor},
  success: function(data){
    //$.fancybox(data);
    //$('body').jAlert('Welcome to jAlert Demo Page', "success");
    //call_fancybox();
  refresh(refreshh);
  //alert(data);
  
  }
});
}

*/

function refresh(refreshh){
//alert(refreshh);
$.ajax({
  url: refreshh+"/ajax_list",
  success: function(data){
  	$('#ajax_list').html(data);
  }
});


}

</script>