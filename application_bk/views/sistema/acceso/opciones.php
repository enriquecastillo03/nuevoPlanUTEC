<?php
 foreach ($opciones as $opc) {
	if($opc['opc_padre']==$nivel){
		$check = "";
        foreach ($oxr as $oxropc)
        	{
            	if ($opc['opc_id'] == $oxropc['opc_id'])
                	{
                    	$check = "checked";
                    }
             }             
 ?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="Checkbox1" value="<?php echo $opc['opc_id']?>" title="<?php echo $opc['opc_descripcion'] ?>" <?php echo $check ?> onchange="cambiarpermiso(this)" />
	<?php echo $opc['opc_nombre'] ?><br />
<?php 
	foreach ($opciones as $opc2) {
		if($opc['opc_id']==$opc2['opc_padre']){
			$check2 = "";
        foreach ($oxr as $oxropc)
        	{
            	if ($opc2['opc_id'] == $oxropc['opc_id'])
                	{
                    	$check2 = "checked";
                    }
             }             

 ?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="Checkbox1" value="<?php echo $opc2['opc_id']?>" title="<?php echo $opc2['opc_descripcion'] ?>" <?php echo $check2 ?> onchange="cambiarpermiso(this)" />
	<?php echo $opc2['opc_nombre'] ?><br />
<?php
	}}}}

	 ?>