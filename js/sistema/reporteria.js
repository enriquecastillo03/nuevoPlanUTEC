var band=0;
var band2=0;
var band3=1;
var ant;
var select_val="";
var from_val="";
var cual=1;
var patt1= /^([a-z|A-Z|0-9|_]*)$/i;
var patt1_2= /^([0-9]*)$/i;
var patt2=/^(ADD|ALL|ALTER|ANALYZE|AND|AS|ASC|ASENSITIVE|BEFORE|BETWEEN|BIGINT|BINARY|BLOB|BOTH|BY|CALL|CASCADE|CASE|CHANGE|CHAR|CHARACTER|CHECK|COLLATE|COLUMN|CONDITION|CONSTRAINT|CONTINUE|CONVERT|CREATE|CROSS|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|CURRENT_USER|CURSOR|DATABASE|DATABASES|DAY_HOUR|DAY_MICROSECOND|DAY_MINUTE|DAY_SECOND|DEC|DECIMAL|DECLARE|DEFAULT|DELAYED|DELETE|DESC|DESCRIBE|DETERMINISTIC|DISTINCT|DISTINCTROW|DIV|DOUBLE|DROP|DUAL|EACH|ELSE|ELSEIF|ENCLOSED|ESCAPED|EXISTS|EXIT|EXPLAIN|FALSE|FETCH|FLOAT|FLOAT4|FLOAT8|FOR|FORCE|FOREIGN|FROM|FULLTEXT|GRANT|GROUP|HAVING|HIGH_PRIORITY|HOUR_MICROSECOND|HOUR_MINUTE|HOUR_SECOND|IF|IGNORE|IN|INDEX|INFILE|INNER|INOUT|INSENSITIVE|INSERT|INT|INT1|INT2|INT3|INT4|INT8|INTEGER|INTERVAL|INTO|IS|ITERATE|JOIN|KEY|KEYS|KILL|LEADING|LEAVE|LEFT|LIKE|LIMIT|LINES|LOAD|LOCALTIME|LOCALTIMESTAMP|LOCK|LONG|LONGBLOB|LONGTEXT|LOOP|LOW_PRIORITY|MATCH|MEDIUMBLOB|MEDIUMINT|MEDIUMTEXT|MIDDLEINT|MINUTE_MICROSECOND|MINUTE_SECOND|MOD|MODIFIES|NATURAL|NOT|NO_WRITE_TO_BINLOG|NULL|NUMERIC|ON|OPTIMIZE|OPTION|OPTIONALLY|OR|ORDER|OUT|OUTER|OUTFILE|PRECISION|PRIMARY|PROCEDURE|PURGE|READ|READS|REAL|REFERENCES|REGEXP|RELEASE|RENAME|REPEAT|REPLACE|REQUIRE|RESTRICT|RETURN|REVOKE|RIGHT|RLIKE|SCHEMA|SCHEMAS|SECOND_MICROSECOND|SELECT|SENSITIVE|SEPARATOR|SET|SHOW|SMALLINT|SONAME|SPATIAL|SPECIFIC|SQL|SQLEXCEPTION|SQLSTATE|SQLWARNING|SQL_BIG_RESULT|SQL_CALC_FOUND_ROWS|SQL_SMALL_RESULT|SSL|STARTING|STRAIGHT_JOIN|TABLE|TERMINATED|THEN|TINYBLOB|TINYINT|TINYTEXT|TO|TRAILING|TRIGGER|TRUE|UNDO|UNION|UNIQUE|UNLOCK|UNSIGNED|UPDATE|USAGE|USE|USING|UTC_DATE|UTC_TIME|UTC_TIMESTAMP|VALUES|VARBINARY|VARCHAR|VARCHARACTER|VARYING|WHEN|WHERE|WHILE|WITH|WRITE|XOR|YEAR_MONTH|ZEROFILL|add|all|alter|analyze|and|as|asc|asensitive|before|between|bigint|binary|blob|both|by|call|cascade|case|change|char|character|check|collate|column|condition|constraint|continue|convert|create|cross|current_date|current_time|current_timestamp|current_user|cursor|database|databases|day_hour|day_microsecond|day_minute|day_second|dec|decimal|declare|default|delayed|delete|desc|describe|deterministic|distinct|distinctrow|div|double|drop|dual|each|else|elseif|enclosed|escaped|exists|exit|explain|false|fetch|float|float4|float8|for|force|foreign|from|fulltext|grant|group|having|high_priority|hour_microsecond|hour_minute|hour_second|if|ignore|in|index|infile|inner|inout|insensitive|insert|int|int1|int2|int3|int4|int8|integer|interval|into|is|iterate|join|key|keys|kill|leading|leave|left|like|limit|lines|load|localtime|localtimestamp|lock|long|longblob|longtext|loop|low_priority|match|mediumblob|mediumint|mediumtext|middleint|minute_microsecond|minute_second|mod|modifies|natural|not|no_write_to_binlog|null|numeric|on|optimize|option|optionally|or|order|out|outer|outfile|precision|primary|procedure|purge|read|reads|real|references|regexp|release|rename|repeat|replace|require|restrict|return|revoke|right|rlike|schema|schemas|second_microsecond|select|sensitive|separator|set|show|smallint|soname|spatial|specific|sql|sqlexception|sqlstate|sqlwarning|sql_big_result|sql_calc_found_rows|sql_small_result|ssl|starting|straight_join|table|terminated|then|tinyblob|tinyint|tinytext|to|trailing|trigger|true|undo|union|unique|unlock|unsigned|update|usage|use|using|utc_date|utc_time|utc_timestamp|values|varbinary|varchar|varcharacter|varying|when|where|while|with|write|xor|year_month|zerofill|ASENSITIVE|CALL|CONDITION|CONTINUE|CURSOR|DECLARE|DETERMINISTIC|EACH|ELSEIF|EXIT|FETCH|INOUT|INSENSITIVE|ITERATE|LEAVE|LOOP|MODIFIES|OUT|READS|RELEASE|REPEAT|RETURN|SCHEMA|SCHEMAS|SENSITIVE|SPECIFIC|SQL|SQLEXCEPTION|SQLSTATE|SQLWARNING|TRIGGER|UNDO|WHILE|asensitive|call|condition|continue|cursor|declare|deterministic|each|elseif|exit|fetch|inout|insensitive|iterate|leave|loop|modifies|out|reads|release|repeat|return|schema|schemas|sensitive|specific|sql|sqlexception|sqlstate|sqlwarning|trigger|undo|while)$/;
crear_from();

$(document).ready(function()
	{
	window.gSateliteBlue.jsSelect();
	window.gSateliteBlue.jsDatePicker();
	});

$('#manual').click(function(){
	var dis;
	if($('#distinct').attr('checked'))
		dis=$('#distinct').val()+" ";
	else
		dis="";
	var val= "SELECT "+dis+removeTags( $('#select_td_1').html().trim() )+" FROM "+removeTags( $('#from_td_1').html().trim() );
	$('#manual').empty();
	$("#borrar").click();
	if(cual==1) {
		$('#manual').append('<span class="icon en">b</span>Asistente');
		$('#query_manual').fadeIn(400);
		$('#datagried').fadeOut(400);
		$('#query_manual').val(removeTags2(val));
		cual=0;
	}
	else {
		$('#manual').append('<span class="icon en">&</span>Manual&nbsp;&nbsp;&nbsp;&nbsp;');
		$('#query_manual').fadeOut(400);
		$('#datagried').fadeIn(400);
		cual=1;
		$("#distinct").prop( "checked", false );
	}
});

$('#alias2').keyup(function(){
	if(patt1.test($('#alias2').val()) && $('#alias2').val()!="") {
		if(!patt1_2.test($('#alias2').val())) {
			if(patt2.test($('#alias2').val())) {
				band3=0;
			}
			else {
				band3=1;
			}
		}
		else{
				band3=0;
			}
	}
	else
		if($('#alias2').val()!="")
			band3=0;
	$('#tablas').change();
});

$('#introducir2').click(function(){
	if(band3==1) {
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
			if($('#alias').val()!="")			
				$('#select_td_1').append("<strong style='color:blue;'> AS </strong>"+'"'+$('#alias').val()+'"');	
			select_val=select_val+$('[name="funcion"] :selected').val()+"****"+$('[name="campos"] :selected').val()+"++++";
			$('#funcion,#campos').val("0");
			$('#funcion,#campos').change();
			$('#alias').val("");
		}
		if($('#relacion').val()!="0" || $('#tablas').val()!="0" || $('#tabla1').val()!="0" || $('#campo1').val()!="0" || $('#tabla2').val()!="0" || $('#campo2').val()!="0")
			$('#introducir').click();
	}
	else {
		if($('#campos').val()!="0") {
			create("note_error", {
                title: 'Error en el alias',
                text: 'El alias que ha escrito es una palabra o frase no válida'
                }, {
                expires: 3000
            });
		}
	}
});

$('#introducir').click(function(){
	if(($('#relacion').val()=="0" && $('#tablas').val()!="0" && $('#tabla1').val()=="0" && $('#campo1').val()=="0" && $('#tabla2').val()=="0" && $('#campo2').val()=="0" && band3!=0) || ($('#relacion').val()!="0" && $('#tablas').val()!="0" && $('#tabla1').val()!="0" && $('#campo1').val()!="0" && $('#tabla2').val()!="0" && $('#campo2').val()!="0" && band3!=0)){
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

		if($('#alias2').val()!=""&& band3==1)			
			$('#from_td_1').append("<strong style='color:blue;'> AS </strong>"+$('#alias2').val());
		else
			$('#from_td_1').append("<strong style='color:blue;'> AS </strong>"+$('[name="tablas"] :selected').text());

		if($('[name="relacion"] :selected').val()!="0")
			$('#from_td_1').append(" <strong style='color:blue;'>ON</strong> "+$('[name="tabla1"] :selected').text()+"."+$('[name="campo1"] :selected').text()+"="+$('[name="tabla2"] :selected').text()+"."+$('[name="campo2"] :selected').text()+" ");

		$('#tabla1,#tabla2').empty();

		if(band!=0)
			$('#tabla1,#tabla2').append(band);
		else
			$("#tabla1,#tabla2").append("<option value='0'>[Selecione...]</option>");

		var mostrar;
		if($('#alias2').val()!="" && band3==1)
			mostrar=$("#alias2").val();
		else
			mostrar=$('[name="tablas"] :selected').text();

		$('#tabla1,#tabla2').append("<option value='"+$('[name="tablas"] :selected').text()+"'>"+mostrar+"</option>");

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
		$('#alias2').val("");

	}
	else {
		if(band3) {
    		create("note_error", {
                title: 'Error en creado del query',
                text: 'Debe ingresar todos datos requeridos para agregar una tabla al query'
                }, {
                expires: 3000
            }); 
    	}
		else {
			create("note_error", {
               	title: 'Error en el alias',
                text: 'El alias que ha escrito es una palabra o frase no válida'
                }, {
                expires: 3000
            });
		}
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
		var mostrar;
		if($('#alias2').val()!="" && band3==1)
			mostrar=$("#alias2").val();
		else
			mostrar=$("#tablas").val();
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
						$("#campos").append("<option value='" + element.campo_nombre + "'>" + mostrar + "." + element.campo_nombre + "</option>");
					});	
				}
			},
			error:function(data){
				alert("No se pudo cargar los campos! Porfavor vuelva a intentarlo"); 
			}
		});
	}

	if(from_val.length>0){
		var mostrar;
		if($('#alias2').val()!="" && band3==1)
			mostrar=$("#alias2").val();
		else
			mostrar=$('[name="tablas"] :selected').text();
		if(band==0) {	
			band=$('#tabla1,#tabla2').html();		
			if($(this).val()!="0") {
				$('#tabla1,#tabla2').append("<option value='"+$('[name="tablas"] :selected').text()+"'>"+mostrar+"</option>");
			}
		}
		else {
			$('#tabla1,#tabla2').empty();
			$('#tabla1,#tabla2').append(band);				
			if($(this).val()!="0") {
				$('#tabla1,#tabla2').append("<option value='"+$('[name="tablas"] :selected').text()+"'>"+mostrar+"</option>");
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
						//$("#campos").append("<option value='" + element.campo_nombre + "'>" + $("#tabla1").text() + "." + element.campo_nombre + "</option>");
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
						//$("#campos").append("<option value='" + element.campo_nombre + "'>" + $("#tabla2").text() + "." + element.campo_nombre + "</option>");
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


$("#borrar").click(function(){
	band=0;
	band2=0;
	ant=0;
	select_val="";
	from_val="";
	$('#query_manual').val("");
	$("#relacion,#tabla1,#tabla2,#campo1,#campo2,#campos,#select_td_1,#from_td_1").empty();
	$("#relacion,#tabla1,#tabla2,#campo1,#campo2,#campos").append("<option value='0'>[Selecione...]</option>");
	$("#select_td_1").append("*");
	$("#relacion,#tabla1,#tabla2,#campo1,#campo2,#campos,#tablas,#funcion").val(0).change();
});

$('#probar').click(function(){
	if(cual==1)
		var valor="sel_html="+removeTags($('#select_td_1').html().trim())+"&fro_html="+removeTags($('#from_td_1').html().trim());
	else
		var valor="sel_fro_html="+$('#query_manual').val();
	if(from_val!="" || cual==0 ) {
		$.ajax({
			async:	true, 
			url:	gSateliteBlue.baseUrl('sistema/reportes/probar_query_reporte'),
			type: "POST",
			dataType:"json",
			data: valor,
			success: function(data){
        		if(data.resultado){
            		create("note_success", {
						title: 'Query Correcto',
						text: 'El Query ingresado funciona correctamente'
						}, {
						expires: 1500
					});
				}
				else {
					create("note_error", {
                        title: 'Query Incorrecto',
                        text: 'El query ingresado no es funcional'
	                    }, {
                        expires: 3000
                    });
				}
			},
			error:function(data){
	    		create("note_error", {
	                title: 'Error en la solicitud de comprobación',
	                text: 'Ocurrió un error al momento de generar la comprobación'
	                }, {
	                expires: 3000
	            });
			}
		});
	}
	else {
		create("note_error", {
            title: 'Error en la solicitud de comprobación',
            text: 'Debe ingresar al menos una tabla en el query'
            }, {
            expires: 3000
        });
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

function removeTags2(string)
{
		return string.replace("\n", ' ');
}

$("#enviar").click(function () {
	if((from_val=="" && $('#query_manual').val()=="") || $('#rxr_id_rol').val()=="0" || $('#rep_nombre').val()==""){
		create("note_error", {
			title: 'Error en el guardado',
			text: 'Debe completar toda la informacion en el formulario'
			}, {
			expires: 3000
		});
	}
	else {	
		if(cual==1) {
			if($('#distinct').attr('checked'))
				ff=1;
			else
				ff=0;
			var valor="sel="+select_val+"&fro="+from_val+"&sel_html="+removeTags($('#select_td_1').html().trim())+"&fro_html="+removeTags($('#from_td_1').html().trim())+"&rxr_id_rol="+$('#rxr_id_rol').val()+"&rep_nombre="+$('#rep_nombre').val()+"&dis="+ff;
		}
		else
			var valor="sel_fro_html="+$('#query_manual').val()+"&rxr_id_rol="+$('#rxr_id_rol').val()+"&rep_nombre="+$('#rep_nombre').val();
		$.ajax({
			async:	true, 
			url:	gSateliteBlue.baseUrl('sistema/reportes/guardar_query_reporte'),
			type: "POST",
			dataType:"json",
			data: valor,
			success: function(data){
            	if(data.guardado){ 
            		if(data.resultado){
	            		create("note_success", {
							title: 'Query-Reporte realizado satisfactoriamente',
							text: 'La solicitud fue guardada exitosamente'
							}, {
							expires: 1500
						});
					setTimeout(window.location.href=gSateliteBlue.baseUrl('sistema/reportes/filtros/'+data.rxr_id_rep),1000);
					}
					else {
						create("note_error", {
	                        title: 'Error en el query guardado',
	                        text: 'El query guardado no es funcional'
		                    }, {
	                        expires: 3000
	                    });
					}
            	}
            	else {
            		create("note_error", {
                        title: 'Error en el registro de solicitud de guardado',
                        text: 'Ocurrió un error al momento de generar la solicitud'
	                    }, {
                        expires: 3000
                    });
            	}
			},
			error:function(data){
	    		create("note_error", {
	                title: 'Error en el registro de solicitud de guardado',
	                text: 'Ocurrió un error al momento de generar la solicitud'
	                }, {
	                expires: 3000
	            });
			}
		});
	}
});