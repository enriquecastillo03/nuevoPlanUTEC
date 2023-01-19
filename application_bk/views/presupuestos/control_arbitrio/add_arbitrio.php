<link rel="stylesheet" type='text/css' href="<?php echo base_url(); ?>assets/validate/validate.css"/>
<style type="text/css">
.container
    {
    width: auto;
    margin: 0 0;
    }
.box    
    {
    margin: 0 !important;
    }
</style>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/validate/jquery.validate.min.js"></script>
<script type="text/javascript">
var band=0;
$('#tipo_arbitrio').change(function(){
    var valor="tipo_arbitrio="+$(this).val();
    $.ajax(
    {
        async:  true, 
        url:    '<?php echo base_url('uatm/control_arbitrio/buscar_arbitrio'); ?>',
        type: "POST",
        dataType:"json",
        data: valor,
        success: function(data){
            if(data.response === true){
                var rows = data.message;
                $("select[name='arbitrio']").empty();
                $("select[name='arbitrio']").append("<option value='0'>[Selecione...]</option>");
                $.each(rows, function(index, element){
                    $("select[name='arbitrio']").append("<option value='" + element.arb_id_srv + "'>" + element.arb_nombre + "</option>");
                }); 
            }
        },
        error:function(data){
            alert("No se pudo cargar los arbitrios! Porfavor vuelva a regargar la pagina");
        }
    });
});
$("#agregar").click(function () {
    if(document.getElementById("tipo_arbitrio").value == "0" || document.getElementById("arbitrio").value == "0"){
        create("note_error", {
            title: 'Error en el envio',
            text: 'Debe completar correctamente toda la informacion del detalle de pedido'
            }, {
            expires: 3000
        });
    }
    else { 
        band++;
        var $tipo           = $('[name="arbitrio"] :selected');

        var $listado        = $('table tbody.contenedor2');
        var tableResult;

        var valor="arb_id_srv="+$("select[name='arbitrio']").val();
        var trf_precio=0;
        $.ajax(
        {
            async:  true, 
            url:    '<?php echo base_url('uatm/control_arbitrio/buscar_tarifa');?>',
            type: "POST",
            dataType:"json",
            data: valor,
            success: function(data){
                if(data.response === true){
                    var rows = data.message;
                    $.each(rows, function(index, element){
                        trf_precio=element.trf_precio;
                        tableResult += '<tr>';
                        tableResult += '<td class="center">' + $tipo.text() + '</td>';
                        tableResult += '<td class="center">' + trf_precio + '</td>';
                        tableResult += '<td class="center tbuttons">';
                        tableResult += '<a class="tbutton" onclick="eliminar_arbitrio(this)">Eliminar</a>';
                        tableResult += '<input type="hidden" name="arbitrio[]" id="arbitrio[]" value="' + document.getElementById("arbitrio").value + '">';
                        tableResult += '</td></tr>';

                        document.getElementById("tipo_arbitrio").value = "0";
                        document.getElementById("arbitrio").value = "0";
                        return $listado.append(tableResult);
                    }); 
                }
            },
            error:function(data){
                alert("No se pudo cargar las tarifas! Porfavor vuelva a regargar la pagina");
            }
        });

        /*tableResult += '<tr>';
        tableResult += '<td class="center">' + $tipo.text() + '</td>';
        tableResult += '<td class="center">' + trf_precio + '</td>';
        tableResult += '<td class="center tbuttons">';
        tableResult += '<a class="tbutton" onclick="eliminar_arbitrio(this)">Eliminar</a>';
        tableResult += '<input type="hidden" name="arbitrio[]" id="arbitrio[]" value="' + document.getElementById("arbitrio").value + '">';
        tableResult += '</td></tr>';

        document.getElementById("tipo_arbitrio").value = "0";
        document.getElementById("arbitrio").value = "0";
        return $listado.append(tableResult);*/
    }
});

function guardar_tarifa(valor){

}

function eliminar_arbitrio(e)
{
    band--;
    var $listado = $('table tbody.contenedor2');
    $(e).parent().parent().remove();
}

$(document).ready(function() {
    $("#tipo_arbitrio,#arbitrio").select2();
    $("#add_arbitrio").validate({
        ignore: null,
        ignore: 'input[type="hidden"]',
        rules: {
            tipo_arbitrio:{required:true},
            arbitrio:{required:true}
            },
        messages:{
            tipo_arbitrio: "Tipo Arbitrio Obligario.",
            arbitrio: "Arbitrio Obligario."
            },
        submitHandler: function(form){
            if(band==0){
                create("note_error", {
                    title: 'Error en el envio',
                    text: 'Debe agregar por lo menos un arbitrio en el formulario'
                    }, {
                    expires: 3000
                });
            }
            else {
                var X="";
                $.each( $( "input[name='arbitrio[]']" ),function(){
                    X=X+$(this).val()+"++++";
                });
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>uatm/control_arbitrio/agregar_arbitrio",
                    data: {
                        drq: X,
                        emp_id: <?php echo $emp_id;?>
                    }
                }).done(function(html){
                    $("#add_arbitrio").remove();
                    $("#message").show();
                    $("#message").html(html);
                });
            }
        }
    });
});
</script>
<div class="container" style="width:900px;">
	<div class="box" style="position:relative;top:-25px;">
	<div class="head">
		<h2>
		<span class="icon en">&</span>
		<span class="title">Asociar Arbitrio</span>
		</h2>
	</div>
	<div class="content">
    <div id="message" style="display: none;"></div>
	<form id="add_arbitrio" autocomplete="off" style="height:500px;">
        <select type="text" name="tipo_arbitrio" id="tipo_arbitrio">
            <option value="0">[Seleccione...]</option>
                <?php
                    foreach ($tipo_arbitrio as $tar) {    
                    
                    ?>
                    <option value="<?php echo $tar['tar_id']?>"><?php echo $tar['tar_nombre'];?></option>
                <?php } ?>
        </select>
        <small>Tipo Arbitrio</small> 
        <select type="text" name="arbitrio" id="arbitrio">
            <option value="0">[Seleccione...]</option>
        </select>
        <small>Nombre Arbitrio</small> 
        <a id="agregar" class="button green small" style="position: relative;top: 2px;height: 16px;"><span class="icon en">j</span><strong>Agregar</strong></a>
        <p>
        <div style="overflow: auto; height:230px;">
            <table class="static_table highlight contenedor">       
                <thead>
                    <tr>
                        <th class="center" style="width:5%;">
                            <span class="icon en"></span>
                            <span class="title">ARBITRIO</span>
                        </th>
                        <th class="center" style="width:5%;">
                            <span class="icon en"></span>
                            <span class="title">TARIFA</span>
                        </th>
                        <th class="center" style="width:3%;">
                            <span class="icon en"></span>
                            <span class="title">ACCIÓN</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="contenedor2" class="contenedor2">
                </tbody>
            </table>
        </div>
        </p>
        <button type="submit" class="button dodger" id="enviar"><span class="icon en">W</span>Guardar</button>
        <input type="hidden" name="emp_id" value="<?php echo $emp_id; ?>" />
	</form>
	</div>
	</div>
</div>