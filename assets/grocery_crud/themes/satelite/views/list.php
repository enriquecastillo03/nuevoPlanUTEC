<?php 
 $url1="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//echo $funcion;
$column_width = (int)(80/count($columns));
	
	
?>
<script type="text/javascript">
$(document).ready(function(){
	$("#table").dataTable();
});
</script>
<div class="bDiv" >
		<table id="table" class="data_table">
		<thead>
			<tr class='hDiv'>				
				<?php foreach($columns as $column){?>
				<th class="center"  width='<?php echo $column_width?>%'>
				<span class="th">
					<span class="arrow"></span>
					<span class="icon en"></span>
					<span class="title"><?php echo $column->display_as?></span>
				</span>
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
			<td>
				<?php echo $row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;' ; ?>
			</td>
			<?php }?>
		

			<?php if(!$unset_delete || !$unset_edit || !empty($actions) ||$texto){?>
			<td align="left" width='20%'>
				<div class='tools'>		
                     
                     	<?php if($texto){?>
                    	
                    			<?php 
                    			//si el count==1 significa que es un string simple cargara el fancybox segun l evento on clik!
                                $texto2= explode(',', $texto);

                                if(count($texto2)==1){
                                ?>
                                <a class="validar tbutton" url="<?php echo base_url().$funcion;?>" valor="<?php echo $row->custom_f;?>" refreshh="<?php echo $url1;?>"  href='' title='Custom function' ><?php echo $texto2[0];?></a>
                                <?php	
                               	
                                }
                                if(count($texto2)>1){
                                ?>
                                <a class="validar tbutton" url="<?php echo base_url().$funcion;?>" valor="<?php echo $row->custom_f;?>" refreshh="<?php echo $url1;?>"  href='' title='Custom function' ><?php echo $texto2[1];?></a>
                                <?php } ?>
                    
                    <?php }?>
                   <?php if($texto1){?>
                    	
                    			<?php 
                    			//si el count==1 significa que es un string simple cargara el fancybox segun l evento on clik!
                                $texto3= explode(',', $texto1);

                                if(count($texto3)==1){
                                ?>
                                <a class="validar tbutton" url="<?php echo base_url().$funcion1;?>" valor="<?php echo $row->custom_f;?>" refreshh="<?php echo $url1;?>"  href='' title='Custom function' ><?php echo $texto3[0];?></a>
                                <?php	
                               	
                                }
                                if(count($texto3)>1){
                                ?>
                                <a class="validar tbutton" url="<?php echo base_url().$funcion1;?>" valor="<?php echo $row->custom_f;?>" refreshh="<?php echo $url1;?>"  href='' title='Custom function' ><?php echo $texto3[1];?></a>
                                <?php } ?>
                    
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
								}else{
									echo $action->label;
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

<?php 
if(isset($texto2) && count($texto2)==1):?>
<script>
$('.validar').click(function(event) {
         event.preventDefault();
           url=  $(this).attr("url");
         valor= $(this).attr("valor");
        refresh=  $(this).attr("refreshh");
		$.ajax({
			  type: 'POST',
			  url: url,
			  data: {valor:valor},
			  success: function(data){
			//alert(url);
			$.fancybox("<div style='color:black; background-color:white;'>"+data+"</div>");
			   
			 // refresh(refreshh);
			  //alert(data);
			 }
			});
        // Stop the Search input reloading the page by preventing its default action
       
    });
   
</script>
<?php endif; ?>

<?php if(isset($texto2) && count($texto2)>1):?>
	<?php if($texto2[0]=="1"):?>
<script>
$('.validar').mouseover(function(event){
         event.preventDefault();
           url=  $(this).attr("url");
         valor= $(this).attr("valor");
        refresh=  $(this).attr("refreshh");
		$.ajax({
			  type: 'POST',
			  url: url,
			  data: {valor:valor},
			  success: function(data){
			//alert(url);
			$.fancybox("<div style='color:black; background-color:white;'>"+data+"</div>");
			   
			 // refresh(refreshh);
			  //alert(data);
			 }
			});
        // Stop the Search input reloading the page by preventing its default action
       
    });

   
</script>
<?php endif; ?>
<?php endif; ?>









<script>
function refresh(refreshh){

$.ajax({
  url: refreshh+"/ajax_list",
  success: function(data){
  
  	$('#ajax_list').html(data);

    
  }
});


}

</script>