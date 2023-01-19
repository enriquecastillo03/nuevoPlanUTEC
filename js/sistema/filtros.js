$(document).ready(function() {
	window.gSateliteBlue.jsSelect();
	window.gSateliteBlue.jsDatePicker();

	$("[name='filtros_revision_tabla[]']").change(function(){
		var at=$(this).data('cava');
		if($(this).val()!="0"){
			var va="tab="+$(this).val();
			var tab=$(this).val();
			var rep=$(this).data('repo');
			$('#tex_'+at).removeAttr("disabled");

			var prob_nombre=$(this).val().split('_');
			if(prob_nombre[1]) {					
				if(prob_nombre[2])
					$('#tex_'+at).val(prob_nombre[1].charAt(0).toUpperCase()+prob_nombre[1].slice(1)+" "+prob_nombre[2].charAt(0).toUpperCase()+prob_nombre[2].slice(1));
				else
					$('#tex_'+at).val(prob_nombre[1].charAt(0).toUpperCase()+prob_nombre[1].slice(1));
			}
			else
				$('#tex_'+at).val(prob_nombre[0].charAt(0).toUpperCase()+prob_nombre[0].slice(1));

			if($('#tex_'+at).data('unico')!=0) {
				$('#tex_'+at).val($('#tex_'+at).data('unico'));
			}

			$.ajax({
				async:	true, 
				url:	gSateliteBlue.baseUrl('sistema/reportes/buscar_campos'),
				type: "POST",
				dataType:"json",
				data: va,
				success: function(data){
					if(data.response === true){
						var rows = data.message;
						$("#"+at).empty();
						$.each(rows, function(index, element){
							$("#"+at).append("<option value='" + rep + " " + tab + " " + at + " " + element.campo_nombre + "'>" + element.campo_nombre + "</option>");
						});	
						$("#"+at).change();
					}
				},
				error:function(data){
					alert("No se pudo cargar los campos de la Tabla ! Porfavor vuelva a intentarlo"); 
					$('#'+at).empty();
				}
			});
		}
		else {		
			$('#'+at).empty();
			$('#'+at).change();
			$('#tex_'+at).attr("disabled", "disabled");
			$('#tex_'+at).val("");
		}
	});
});