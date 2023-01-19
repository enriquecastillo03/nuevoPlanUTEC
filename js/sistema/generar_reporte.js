$("#parte1,#parte2").fadeOut(0);
var url_ant=($("#formu_reporte").attr("action"));
$(document).ready(function() {
	window.gSateliteBlue.jsSelect();
});
var cont_row=0;
$('#rep_id').change(function(){
	$('#resultado_reporte').empty();
	$("#fil_content").empty();
	$('#fil_content_2').empty();
	$('#tabla_3').css("display","none");
	$('#fil_content_3').empty();
	if($(this).val()!="0"){
		var va="rep_id="+$(this).val();
		$.ajax({
			async:	true, 
			url:	gSateliteBlue.baseUrl('sistema/reportes/buscar_filtros'),
			type: "POST",
			dataType:"json",
			data: va,
			success: function(data){
				$("#parte1,#parte2").fadeIn(300);
				var i=1;
				var j=1;
				var acu="";
				$("#fil_content").append('<tr><td width="175"></td><td width="800"></td><td width="20"></td></tr>');
				$.each(data.valores, function(index, element){
					$('#fil_content').append('<tr height="50"><td align="right">'+element.alias_campo+' &nbsp;&nbsp;&nbsp;</td><td><select name="'+element.nombre_campo+'" id="'+element.nombre_campo+'"><option value="0">[Seleccione...]</option>'+element.opciones+'</select></td><td align="center"><!--<input type="checkbox" name="chk_'+element.nombre_campo+'" id="chk_'+element.nombre_campo+'" title="Contabilizar '+element.alias_campo+'" value="1" />--></td></tr>');
				});
				window.gSateliteBlue.jsSelect();
				window.gSateliteBlue.jsDatePicker();	
			},
			error:function(data){
				alert("No se pudieron cargar los filtros del reporte! Porfavor vuelva a intentarlo"); 
				$('#fil_content').empty();
				$('#fil_content_2').empty();
				$('#tabla_3').css("display","none");
				$('#fil_content_3').empty();
			}
		});
		$.ajax({
			async:	true, 
			url:	gSateliteBlue.baseUrl('sistema/reportes/buscar_filtros_campos'),
			type: "POST",
			dataType:"json",
			data: va,
			success: function(data){
				var i=1;
				var j=1;
				var acu="";
				$.each(data.valores, function(index, element){
					acu=acu+'<thead><tr role="row">';
					acu=acu+'<th width="200" class="center sorting_asc"  role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Column 1 : activate to sort column descending"><span class="th"><span class="arrow"></span><span class="icon en"></span><span class="title">CAMPO</span></span></th>';
					acu=acu+'<th width="200" class="center sorting_asc"  role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Column 1 : activate to sort column descending"><span class="th"><span class="arrow"></span><span class="icon en"></span><span class="title">TIPO RELACION</span></span></th>';
					acu=acu+'<th width="100" class="center sorting_asc"  role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Column 1 : activate to sort column descending"><span class="th"><span class="arrow"></span><span class="icon en"></span><span class="title">VALOR</span></span></th>';
					acu=acu+'<th width="100" class="center sorting_asc"  role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Column 1 : activate to sort column descending"><span class="th"><span class="arrow"></span><span class="icon en"></span><span class="title">EXTRA</span></span></th>';
					acu=acu+'<th width="100" class="center sorting_asc"  role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Column 1 : activate to sort column descending"><span class="th"><span class="arrow"></span><span class="icon en"></span><span class="title">ACCION</span></span></th>';
					acu=acu+'</tr></thead><tbody>';
					acu=acu+'<tr height="50">';
					acu=acu+'<td rowspan="2"><select name="otro_filtro" id="otro_filtro">'+element.opciones+'</select></td>';
					acu=acu+'<td rowspan="2">';
					acu=acu+'<select name="tipo_relacion" id="tipo_relacion">';					
					acu=acu+'<option value="=">Igual a (=)</option>';				
					acu=acu+'<option value="<>">Diferente de (<>)</option>';				
					acu=acu+'<option value="<">Menor que (<)</option>';				
					acu=acu+'<option value="<=">Menor o igual que (<=)</option>';				
					acu=acu+'<option value=">">Mayor que (>)</option>';	
					acu=acu+'<option value=">=">Mayor o igual que (>=)</option>';				
					acu=acu+'<option value="like">Sea como (like)</option>';	
					acu=acu+'<option value="not like">No sea como (not like)</option>';				
					acu=acu+'<option value="is null">Sea vacío (is null)</option>';			
					acu=acu+'<option value="is not null">No sea vacío (is not null)</option>';
					acu=acu+'</select">';
					acu=acu+'</td>';
					acu=acu+'<td rowspan="2">';
					acu=acu+'<input type="text" name="valor" id="valor">';
					acu=acu+'</td>';
					acu=acu+'<td><input type="checkbox" name="chk_otro_filtro" id="chk_otro_filtro" title="Contabilizar" value="1" />&nbsp;Contabilizar</td>';
					acu=acu+'<td rowspan="2">';
					acu=acu+'<button type="button" class="button green" id="agregar_valor" name="agregar_valor"><span class="icon en">]</span>Agregar</button>';
					acu=acu+'</td>';
					acu=acu+'</tr>';
					acu=acu+'<tr><td><input type="checkbox" name="chk_otro_filtro_2" id="chk_otro_filtro_2" title="Sumar" value="1" />&nbsp;Sumar</td></tr>';
					acu=acu+'</tbody>';
					$('#fil_content_2').append(acu);
				});
				window.gSateliteBlue.jsSelect();
				
				$('#tipo_relacion').change(function(){
					if($(this).val()=="is null" || $(this).val()=="is not null")	
						$('#valor').attr("disabled",true).val("");
					else
						$('#valor').removeAttr("disabled");
				});
				
				$('#agregar_valor').click(function(){
					if($('#valor').val()!="" || $('#tipo_relacion').val()=="is null" || $('#tipo_relacion').val()=="is not null") {
						cont_row++;
						var chk_1=0;
						var chk_2=0;
						var v_chk_1="";
						var v_chk_2="";
						$('#tabla_3').fadeIn(500);
						if($('#chk_otro_filtro').attr('checked')) {
							chk_1=1;
							v_chk_1="Contabilizar";
						}
						if($('#chk_otro_filtro_2').attr('checked')) {
							chk_2=1;
							if(chk_1==1)
								v_chk_2=", Sumar";
							else
								v_chk_2="Sumar";
						}
						if($('#valor').val()=="") {
							$('#valor').val(" ");
							val_com=' '
						}
						else
							val_com=' "'+$('#valor').val()+'" ';
						$('#fil_content_3').append('<tr><td>'+$('[name="otro_filtro"] :selected').text()+' '+$('#tipo_relacion').val()+val_com+'</td><td>'+v_chk_1+v_chk_2+'</td><td><button type="button" class="button red" onclick="eliminar_solicitud(this)"><span class="icon en">u</span>Eliminar</button><input type="hidden" name="val_otros_filtros[]" value="'+$('#otro_filtro').val()+'**'+$('#tipo_relacion').val()+'**'+$('#valor').val()+'**'+chk_1+'**'+chk_2+'***"></td></tr>');
						$('#valor').val("");
					}
					else {
						create("note_error", {
							title: 'No ha escrito ningun valor',
							text: 'Debe escribir un valor para poder generar resultados'
							}, {
							expires: 3000
						});
					}
				});
				
				
			},
			error:function(data){
				alert("No se pudieron cargar los filtros del reporte! Porfavor vuelva a intentarlo"); 
			$('#fil_content_2').empty();
			$('#tabla_3').css("display","none");
			$("#parte1,#parte2").fadeOut(0);
			}
		});
	}
	else {
		$("#parte1,#parte2").fadeOut(0);
	}
});

function eliminar_solicitud(e)
{	
	$(e).fadeOut(500);
    $(e).parent().parent().remove();
	cont_row--;
	if(cont_row==0)
		$('#tabla_3').fadeOut(500);
}

$('#enviar').click(function(){
	if($('#rep_id').val()!="0") {
		var X="";
       	$.each( $( "input[name='val_otros_filtros[]']" ),function(){
       		X=X+$(this).val();
       	})
		var va=$('#formu_reporte').serialize()+"&bandera="+$(this).data('bandera')+"&vf="+X;
		$.ajax({
			async:	true, 
			url:	gSateliteBlue.baseUrl('sistema/reportes/generar_reporte_pantalla'),
			type: "POST",
			dataType:"json",
			data: va,
			success: function(data){
				if(data.bandera) {
					$('#resultado_reporte').empty();
					$('#resultado_reporte').append(data.resultado);
					$('#table_result').dataTable();
				}
				$("#exportar").click(function(event) {
					if($('#rep_id').val()!="0") {
						$("#formu_reporte").attr("action",gSateliteBlue.baseUrl('sistema/reportes/expotar_reporte'));
						$("#formu_reporte").submit();
						$("#formu_reporte").attr("action",url_ant);
					}
					else {
						create("note_error", {
							title: 'No ha seleccionado ningun reporte',
							text: 'Debe seleccionar un reporte para poder generar resultados'
							}, {
							expires: 3000
						});
					}
				});
			},
			error:function(data){
				alert("No se pudieron cargar los resultados de la consulta del reporte! Porfavor vuelva a intentarlo"); 
				$('#resultado_reporte').empty();
			}
		});
	}
	else {
		create("note_error", {
            title: 'No ha seleccionado ningun reporte',
            text: 'Debe seleccionar un reporte para poder generar resultados'
            }, {
            expires: 3000
        });
	}
});