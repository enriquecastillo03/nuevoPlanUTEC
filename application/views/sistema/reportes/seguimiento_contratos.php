
<h3 align="center">CREACIÓN DE REPORTE</h3>
<p>
	<input type="text" name="rep_nombre" id="rep_nombre" placeholder="Digite el Nombre del Reporte">
	<small>Nombre del reporte</small>
</p>
<p>
	<select name="rxr_id_rol" id="rxr_id_rol">
		<option value='0' selected>[Selecione...]</option>		
			<?php
	            foreach ($roles as $rol) {  	
	            
				?>
				<option value="<?php echo $rol['rol_id']?>"><?php echo $rol['rol_nombre'];?></option>
			<?php } ?>
	</select>
	<small>Rol</small>
</p>
<p>
<form name="formu_query" id="formu_query" method="post" action="<?php echo base_url();?>sistema/reportes/reporteria">
	<div class="content_full">
		<table class="static_table highlight contenedor" id="datagried">		
			<thead>
				<tr>
					<th class="center" style="width:3%;">
						<span class="icon en"></span>
						<span class="title">TIPO</span>
					</th>
					<th class="center" style="width:20%;">
						<span class="icon en"></span>
						<span class="title">DESCRIPCIÓN</span>
					</th>
					<th class="center" style="width:20%;">
						<span class="icon en"></span>
						<span class="title">ACCIÓN</span>
					</th>
				</tr>
			</thead>
			<tbody id="contenedor" class="contenedor">
				<tr>
					<td height="100">
						<strong style='color:blue;'>SELECT</strong>
					</td>
					<td class="select_td_1" id="select_td_1">
						*
					</td>
					<td class="select_td_2" id="select_td_2">							
						<select name="funcion" id="funcion">
							<option value='0' selected>[Selecione...]</option>
							<option value='1'>SUM</option>
							<option value='2'>MAX</option>
							<option value='3'>MIN</option>
							<option value='4'>AVG</option>
							<option value='5'>COUNT</option>
						</select>	
						<small>Función</small>				
						<select name="campos" id="campos">
							<option value='0' selected>[Selecione...]</option>
						</select>
						<small>Campos</small>	
						<button type="button" class="button green" id="introducir2"><span class="icon en">W</span>Introducir</button>					
					</td>
				</tr>
				<tr>
					<td height="100">
						<strong style='color:blue;'>FROM</strong>
					</td>
					<td class="from_td_1" id="from_td_1">
					</td>
					<td class="from_td_2" id="from_td_2">						
						<select name="relacion" id="relacion">
							<option value='0' selected>[Selecione...]</option>
						</select>
						<small>Tipo de relación</small>
						<select name="tablas" id="tablas">
							<option value='0'selected>[Selecione...]</option>
						</select>
						<small>Tablas</small>
						<select name="tabla1" id="tabla1">
							<option value='0'selected>[Selecione...]</option>
						</select>
						<small>Tabla 1 de relación</small>
						<select name="campo1" id="campo1">
							<option value='0'selected>[Selecione...]</option>
						</select>
						<small>Campo 1 de relación</small>
						<select name="tabla2" id="tabla2">
							<option value='0'selected>[Selecione...]</option>
						</select>
						<small>Tabla 2 de relación</small>
						<select name="campo2" id="campo2">
							<option value='0'selected>[Selecione...]</option>
						</select>
						<small>Campo 2 de relación</small>
						<button type="button" class="button green" id="introducir"><span class="icon en">W</span>Introducir</button>
					</td>
				</tr>
			</tbody>
		</table>
		<small>Query</small>	
		</p>
	    </br>
	    </br>
		<button type="button" class="button dodger" id="enviar"><span class="icon en">W</span>Guardar Query</button>
	</div>
</form>
</p>
<script type="text/javascript">
	var band=0;
	var band2=0;
	var cc;
	var ant;
	var ant2;
	var select_val="";
	var from_val="";
	crear_from();
	$(document).ready(function()
		{
		window.gSateliteBlue.jsSelect();
		window.gSateliteBlue.jsDatePicker();
		});
	$('#introducir2').click(function(){
		if($('#campos').val()!="0"){
			if(select_val.length>0)
				$('#select_td_1').append(", ");
			else
				$('#select_td_1').empty();				
			if($('#funcion').val()!="0")
				$('#select_td_1').append("<strong style='color:blue;'>"+$('[name="funcion"] :selected').text()+"</strong>(");				
			$('#select_td_1').append($('[name="campos"] :selected').text());			
			if($('#funcion').val()!="0")			
				$('#select_td_1').append(')');			
			select_val=select_val+$('[name="funcion"] :selected').val()+"****"+$('[name="campos"] :selected').val()+"++++";
			$('#funcion,#campos').val("0");
			$('#funcion,#campos').change();
		}
		$('#introducir').click();
	});

	$('#introducir').click(function(){
		if(($('#relacion').val()=="0" && $('#tablas').val()!="0" && $('#tabla1').val()=="0" && $('#campo1').val()=="0" && $('#tabla2').val()=="0" && $('#campo2').val()=="0") || ($('#relacion').val()!="0" && $('#tablas').val()!="0" && $('#tabla1').val()!="0" && $('#campo1').val()!="0" && $('#tabla2').val()!="0" && $('#campo2').val()!="0")){
			var tip;
			if($('[name="relacion"] :selected').val()=="0" && ant=="0") {
				if(ant=="0")
					tip=", <br>";
			}
			else {
				if($('[name="relacion"] :selected').val()=="0")
					tip=", <br>";
				else
					tip=" <br>"+$('[name="relacion"] :selected').text();
			}
			if(from_val.length>0)
				$('#from_td_1').append("<strong style='color:blue;'>"+tip+"</strong> ");
			$('#from_td_1').append($('[name="tablas"] :selected').text());

			if($('[name="relacion"] :selected').val()!="0")
				$('#from_td_1').append(" <strong style='color:blue;'>ON</strong> "+$('[name="tabla1"] :selected').text()+"."+$('[name="campo1"] :selected').text()+"="+$('[name="tabla2"] :selected').text()+"."+$('[name="campo2"] :selected').text()+" ");

			$('#tabla1,#tabla2').empty();

			if(band!=0)
				$('#tabla1,#tabla2').append(band);
			else
				$("#tabla1,#tabla2").append("<option value='0'>[Selecione...]</option>");

			$('#tabla1,#tabla2').append("<option value='"+$('[name="tablas"] :selected').text()+"'>"+$('[name="tablas"] :selected').text()+"</option>");

			if(from_val.length==0) {
				crear_relacion();
			}

			from_val=from_val+$('[name="relacion"] :selected').val()+"****"+$('[name="tablas"] :selected').val()+"****"+$('[name="campo1"] :selected').val()+"****"+$('[name="campo2"] :selected').val()+"++++";
			ant=$('[name="relacion"] :selected').val();
			$('#relacion,#tablas,#tabla1,#tabla2').val("0");
			$('#relacion,#tabla1,#tabla2').change();
			band=0;			
			band2=0;		
			$('#tablas').change();

		}
	});

	$('#tablas').change(function(){
		$('#tabla1,#tabla2').val("0");
		$('#tabla1,#tabla2').change();

		if(band2==0) {
			band2=$('#campos').html();		
		}
		else {
			$('#campos').empty();
			$('#campos').append(band2);
		}

		if($('#tablas').val()!="0"){
			var va="tab="+$('#tablas').val();
			$.ajax({
				async:	true, 
				url:	gSateliteBlue.baseUrl('sistema/reportes/buscar_campos'),
				type: "POST",
				dataType:"json",
				data: va,
				success: function(data){
					if(data.response === true){
						var rows = data.message;
						$.each(rows, function(index, element){
							$("#campos").append("<option value='" + element.campo_nombre + "'>" + $("#tablas").val() + "." + element.campo_nombre + "</option>");
						});	
					}
				},
				error:function(data){
					alert("No se pudo cargar los campos! Porfavor vuelva a intentarlo"); 
				}
			});
		}

		if(from_val.length>0){
			if(band==0) {	
				band=$('#tabla1,#tabla2').html();		
				if($(this).val()!="0") {
					$('#tabla1,#tabla2').append("<option value='"+$('[name="tablas"] :selected').text()+"'>"+$('[name="tablas"] :selected').text()+"</option>");
				}
			}
			else {
				$('#tabla1,#tabla2').empty();
				$('#tabla1,#tabla2').append(band);				
				if($(this).val()!="0") {
					$('#tabla1,#tabla2').append("<option value='"+$('[name="tablas"] :selected').text()+"'>"+$('[name="tablas"] :selected').text()+"</option>");
				}
			}
		}
	});

	$('#tabla1').change(function(){
		if($(this).val()!="0"){
			var va="tab="+$(this).val();
			$.ajax({
				async:	true, 
				url:	gSateliteBlue.baseUrl('sistema/reportes/buscar_campos'),
				type: "POST",
				dataType:"json",
				data: va,
				success: function(data){
					if(data.response === true){
						var rows = data.message;
						$("#campo1").empty();
						$("#campo1").append("<option value='0'>[Selecione...]</option>");
						$.each(rows, function(index, element){
							$("#campo1").append("<option value='" + element.campo_nombre + "'>" + element.campo_nombre + "</option>");
							$("#campos").append("<option value='" + element.campo_nombre + "'>" + $("#tabla1").val() + "." + element.campo_nombre + "</option>");
						});	
					}
				},
				error:function(data){
					alert("No se pudo cargar los campos de la Tabla No 1! Porfavor vuelva a intentarlo"); 
					$('#campo1').empty();
					$("#campo1").append("<option value='0'>[Selecione...]</option>");
					$('#campo1').val(0);
					$('#campo1').change();
				}
			});
		}
		else {			
			$('#campo1').empty();
			$("#campo1").append("<option value='0'>[Selecione...]</option>");
			$('#campo1').val(0);
			$('#campo1').change();
		}
	});

	$('#tabla2').change(function(){
		if($(this).val()!="0"){
			var va="tab="+$(this).val();
			$.ajax({
				async:	true, 
				url:	gSateliteBlue.baseUrl('sistema/reportes/buscar_campos'),
				type: "POST",
				dataType:"json",
				data: va,
				success: function(data){
					if(data.response === true){
						var rows = data.message;
						$("#campo2").empty();
						$("#campo2").append("<option value='0'>[Selecione...]</option>");
						$.each(rows, function(index, element){
							$("#campo2").append("<option value='" + element.campo_nombre + "'>" + element.campo_nombre + "</option>");
							$("#campos").append("<option value='" + element.campo_nombre + "'>" + $("#tabla2").val() + "." + element.campo_nombre + "</option>");
						});	
					}
				},
				error:function(data){
					alert("No se pudo cargar los campos de la Tabla No 2! Porfavor vuelva a intentarlo"); 
					$('#campo2').empty();
					$("#campo2").append("<option value='0'>[Selecione...]</option>");
					$('#campo2').val(0);
					$('#campo2').change();
				}
			});
		}
		else {			
			$('#campo2').empty();
			$("#campo2").append("<option value='0'>[Selecione...]</option>");
			$('#campo2').val(0);
			$('#campo2').change();
		}
	});

	function crear_relacion()
	{
		$('#relacion').append("<option value='1'>INNER JOIN</option>");
		$('#relacion').append("<option value='2'>LEFT JOIN</option>");
		$('#relacion').append("<option value='3'>RIGTH JOIN</option>");
	}

	function crear_from()
	{	
		var i=1;
        $.ajax({
			async:	true, 
			url:	gSateliteBlue.baseUrl('sistema/reportes/buscar_tablas'),
			type: "POST",
			dataType:"json",
			success: function(data){
				if(data.response === true){
					var rows = data.message;
					$("#tablas").empty();
					$("#tablas").append("<option value='0'>[Selecione...]</option>");
					$.each(rows, function(index, element){
						if(i==1){
							$("#tablas").append("<option value='" + element.tabla_nombre + "'>" + element.tabla_nombre + "</option>");           
							i=0;
						}
						else
							$("#tablas").append("<option value='" + element.tabla_nombre + "'>" + element.tabla_nombre + "</option>");
					});	
					$("#tablas").change();
				}
			},
			error:function(data){
				alert("No se pudo cargar las tablas! Porfavor vuelva a intentarlo");
			}
		});
	}

	function removeTags(string)
	{
  		return string.replace(/(?:<(?:script|style)[^>]*>[\s\S]*?<\/(?:script|style)>|<[!\/]?[a-z]\w*(?:\s*[a-z][\w\-]*=?[^>]*)*>|<!--[\s\S]*?-->|<\?[\s\S]*?\?>)[\r\n]*/gi, '');
	}

	$("#enviar").click(function () {
	if(from_val=="" || $('#rxr_id_rol').val()=="0" || $('#rep_nombre').val()==""){
		create("note_error", {
			title: 'Error en el guardado',
			text: 'Debe completar toda la informacion en el formulario'
			}, {
			expires: 3000
		});
	}
	else {		
		$.post(gSateliteBlue.baseUrl('sistema/reportes/guardar_query_reporte'), {
		   	sel        	: select_val,
		   	fro        	: from_val,
			sel_html	: removeTags($('#select_td_1').html()),
			fro_html	: removeTags($('#from_td_1').html()),
			rxr_id_rol	: $('#rxr_id_rol').val(),
			rep_nombre	: $('#rep_nombre').val()

        	}, 
        	function(data) {
            	if(data){ 
            		create("note_success", {
						title: 'Query-Reporte realizado satisfactoriamente',
						text: 'La solicitud fue guardada exitosamente'
						}, {
						expires: 1500
					});
					setTimeout("window.location.href=gSateliteBlue.baseUrl('sistema/reportes/reporteria')",500);
            	}
            	else {
            		create("note_error", {
                        title: 'Error en el registro de solicitud de guardado',
                        text: 'Ocurrio un error al momento de generar la solicitud'
	                    }, {
                        expires: 3000
                    });
            	}
        	});
	}
});
</script>