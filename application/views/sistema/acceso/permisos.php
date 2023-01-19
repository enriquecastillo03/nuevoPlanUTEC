<script>
 $(document).ready(function () {
        $("#rol").select2();
        $("#opc").select2();
        cargarOpciones();
    });

 function cargarOpciones()
 {
 	rol=$("#rol").val();
 	opc=$("#opc").val();
 	 $.ajax
		({
		    type: 'post',
		    url: "<?php echo base_url($modulo.'/'.$control.'/opciones')?>",
		    data: { rol: rol, opc: opc },
		    success: function (json) {
		        $('#opciones').html(json);
		    }
		});
 }

 function cambiarpermiso(opc) {
   if (opc.checked)
        url = "<?php echo base_url($modulo.'/'.$control.'/addopc')?>";
    else
        url = "<?php echo base_url($modulo.'/'.$control.'/delopc')?>";
    rol = $("#rol").val();
    $.ajax
		({
		    type: 'post',
		    url: url,
		    data: { rol: rol, opc: opc.value}
		});
 }
</script>
<select id="rol" name="rol" onchange="cargarOpciones()">
	<?php
		foreach ($rol as $rol)
		{
	?>
			<option value="<?php echo $rol['rol_id'] ?>"><?php echo $rol['rol_nombre'] ?></option>		
	<?php
		}
	?>	
</select>
<small>Rol</small>
<select id="opc" name="opc" onchange="cargarOpciones()">
	<option value="0">Principales</option>
	<?php	
		foreach ($opc as $opc) 
		{
	?>
			<option value="<?php echo $opc['opc_id'] ?>"><?php echo $opc['opc_nombre'] ?></option>		
	<?php
		}
	?>	
</select>
<small>Menu</small>

<div id="opciones"></div>