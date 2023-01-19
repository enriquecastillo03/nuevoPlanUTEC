<div class="container" style='width:400px; margin:0;'>
			
			<div class="box" style='width:400px; margin:0;'>
				<div class="head">
					<h2>						
						<span class="title">Identificacion</span>
					</h2>
				</div>
				<div class="content">

<form id='nuevo_elemento_cata'>
<p>
<input type='text' name='valor_nuevo_elemento'>
<small>Nueva opcion</small>
</p>
<p>
<input type='hidden' name='tabla' value="<?php echo $tabla; ?>">
<input type='hidden' name='contenedor' value="<?php echo $contenedor; ?>">
<input type='hidden' name='nombre_actual' value="<?php echo $nombre_actual; ?>">
<input type='hidden' name='id_actual' value="<?php echo $id_actual; ?>">
<input type='submit'>
</p>
</form>
</div>
</div>
</div>
<script>
$('#nuevo_elemento_cata').submit(function(event){
	event.preventDefault();
             $.ajax({
					  type: 'POST',
					  dataType: 'json',
					  url: '<?php echo base_url(); ?>'+'uatm/soluciones/guardar_nuevo_elemento_catalogo',
					  data: $('#nuevo_elemento_cata').serialize(),
					  success: function(data){
					   $('#'+data.contenedor).html('');
					    crear_nuevo_select(data.tabla, data.contenedor, data.nombre_actual, data.id_actual);
					   
					  }
					});

});

function crear_nuevo_select(tabla_choice, contenedor_choice, nombre_actual, id_actual){
	tabla_especifica = tabla_choice;
	contenedor_especifico = contenedor_choice;
             $.ajax({
					  type: 'POST',
					  dataType: 'json',
					  url: '<?php echo base_url(); ?>'+'uatm/soluciones/generar_nuevo_drop_catalogo',
					  data: {tabla_especifica:tabla_especifica, contenedor_especifico:contenedor_especifico, nombre_actual:nombre_actual, id_actual:id_actual},
					  success: function(data){
					  	//alert(data)
					  	$.fancybox.close();
					   $('#'+data.contenedor).html(data.nuevo_drop);
					    
					   
					  }
					});
}



</script>
