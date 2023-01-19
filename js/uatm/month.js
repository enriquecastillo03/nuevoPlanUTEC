/**
 * Description:
 * Administra JSON request para generacion de planes de pago FRONT-END
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.2 - 2013/07/04 
 * @since:      2013/06/29
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/07/04
 * getDetalleCuota-  Obtiene detalle de cuota y Genera F1-ISAM 
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/07/01
 * + getDetallePlan - Obtiene todas las cuotas correspondientes a un Plan de Pagos
 * por Alan Alvarenga
 * ------------------------------------------------------------------------------------
 * 2013/06/29
 * + doListaCuota - Generar Listado de cuotas correspondientes al plan seleccionado 
 * + getPlanPago - Obtener Planes de pago correspondientes a un inmueble
 * + doListaPlan - Constrye lista de planes de pago
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 */

/**
 * Initialize jQuery
 */
jQuery(document).ready(function($) {
	//Esconder todos los divs innecesarios
	estoy = 'inmueble';

	$('div.properties_list').toggle();	
	
	$('div.plan_list').toggle();	

	$('div.cuota_list').toggle();

	$('div.enterprise_list').toggle();
	
	//Mostrar planes de pago activos para cada inmueble
	$('table.selectproperty').on('click', 'a', function(e){
		inm_id = $(this).attr("data-inm-id");
		e.preventDefault();
		getPlanPago(inm_id);
		$('div.properties_list').toggle(300);	

	});

	//Generar un listado de cuotas pertenecientes al plan seleccionado
	$('table.select_plan').on('click', 'a', function(e){
		e.preventDefault();
		plan_id = $(this).attr("data-plan-id");
		getDetallePlan(plan_id);
		$('div.plan_list').toggle(300);
	});

	//Generar F1-ISAM para cada cuota del plan de pago a plazos
	$('table.select_cuota').on('click', 'a', function(e){
		e.preventDefault();
		cuota_id = $(this).attr("data-cuota-id");
		if(estoy == 'inmueble'){
			getDetalleCuota( cuota_id, inm_id );
		} else {
			getDetalleCuotaEmpresa(cuota_id, cnt_id);
		}
		
	});

	//Regresar a la lista de propiedades
	$('a.inmueble').on('click', function(e){
		e.preventDefault();
		$('div.plan_list').toggle(280);
		$('div.properties_list').toggle(300);
	});

	//Regresar a la lista de planes 
	$('a.estado_plan').on('click', function(e){
		e.preventDefault();
		$('div.cuota_list').toggle(280);
		$('div.plan_list').toggle(300);
	});

	//Mostrar Inmuebles de nuevo
   	$('a.ver_inmuebles').on('click', function (e){
    e.preventDefault();
        $('div.enterprise_list').toggle(400); //Esconder Listado de Empresas
        $('div.properties_list').toggle(400); //Mostrar div Listado de Propiedades
        estoy = 'inmueble';
    });

	//Mostrar Empresas 
    $('a.ver_empresas').on('click', function (e){
        e.preventDefault();
        getUserEmpresa(id);
        $('div.properties_list').toggle(400); //Esconder div Listado de Propiedades
        $('div.enterprise_list').toggle(400); //Mostrar Listado de Empresas
       estoy = 'empresa'; 
    });

    //Obtener consolidado de cuenta por ID de inmueble
    $('table.selectenterprise').on('click', 'a', function (e) {
        e.preventDefault();
        cnt_id = $(this).attr('data-cnt-id');
        
        getPlanPagoEmpresa(cnt_id, 2);
        $('div.enterprise_list').toggle();
    });
});

/**
 * Obtener listado de empresas por id de Contribuyente
 * @param  {integer} id     ID de contribuyente
 */
function getUserEmpresa (id) {
    url = baseUrl('uatm/pagos/get_user_empresa');
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            id: id
        },
        type: 'POST',
        success: function(data){
            if(data.response === true){
                var rows = data.message;
                table = doTablaEmpresa(rows);
                $('table.selectenterprise tbody').html(table);
            }
        }
    });
}

/**
 * Construir listado de empresas en formato HTML
 * @param  {array} rows     Listado de empresas en formato json
 * @return {html}           tabla de empresas en formato HTML
 */
function doTablaEmpresa (rows) {
    $('table.selectenterprise tbody').empty();
    table = "";
    $.each(rows, function(index, element){
        if(element.id !== false){
            table += '<tr>';
            table += '<td class="left">' + element.emp_nombre + '</td>';
            table += '<td class="left">' + element.emp_direccion + '</td>';
            table += '<td class="left">' + element.emp_giros + '</td>';
            table += '<td class="left">' + element.emp_actividad + '</td>';
            table += '<td class="center tbuttons" >';
            table += '<a class="tbutton" href="#display_account" data-cnt-id="' + element.id + '"';
            table += 'data-mora="' + element.det_fecha_old + '">Ver</a></td>';
            table += '</tr>';
        } else {
            table += '<tr>';
            table += '<td class="left"> 0 </td>';
            table += '<td class="left"> Registros Encontrados</td>';
            table += '<td class="left"> </td>';
            table += '<td class="left"> </td>';
            table += '<td class="center tbuttons" >';
            table += '</tr>';
        }
    });

    return table;
}

/**
 * Obtiene detalle de cuota y Genera F1-ISAM 
 * @param  {integer} cuota_id 	ID de Cuota a cancelar
 * @param  {integer} inm_id   	ID de inmueble al que pertenece el plan de pago
 */
function getDetalleCuota (cuota_id, inm_id) {
	url = baseUrl('uatm/cuota/get_detalle_cuota')
	
	$.ajax({
		url: url,
		dataType: 'json',
		data: {
			cuota_id: cuota_id,
			inm_id: inm_id
		},
		type: 'POST',
		success: function(data){
			funoId = data.message.funo_id;
			alertExito(funoId);
		}
	});
}

/**
 * Obtiene detalle de cuota y Genera F1-ISAM 
 * @param  {integer} cuota_id 	ID de Cuota a cancelar
 * @param  {integer} inm_id   	ID de inmueble al que pertenece el plan de pago
 */
function getDetalleCuotaEmpresa (cuota_id, cnt_id) {
	url = baseUrl('uatm/cuota/get_detalle_cuota')
	
	$.ajax({
		url: url,
		dataType: 'json',
		data: {
			cuota_id: cuota_id,
			cnt_id: cnt_id,
			mode: 2
		},
		type: 'POST',
		success: function(data){
			funoId = data.message.funo_id;
			alertExito(funoId);
		}
	});
}

/**
 * Obtiene todas las cuotas correspondientes a un Plan de Pagos
 * @param  {integer} plan_id 	ID de Plan
 */
function getDetallePlan (plan_id) {
	url = baseUrl('uatm/cuota/get_detalle_plan');
	$.ajax({
		url: url,
		dataType: 'json',
		data: {
			plan_id: plan_id
		},
		type: 'POST',
		success: function(data){
			if(data.response === true){
				rows = data.message;
				table = doListaCuota(rows);
				
				$('table.select_cuota tbody').html(table);
				$('div.cuota_list').toggle();
			}
		}
	});
}

/**
 * Generar Listado de cuotas correspondientes al plan seleccionado 
 * @param  {array} rows  	Listado de cuotas a cancelar
 * @return {html}      		Listado de cuotas a cancelar en formato html
 */
function doListaCuota (rows) {
	$('div.cuota_list').hide();
	$('table.select_cuota tbody').empty();
	table = '';

    $.each(rows, function (index, element) {
        if( element.pla_id != false){
            table += '<tr>';
            table += '<td class="center">' + element.cxp_numero + '</td>';
            table += '<td class="left" >  $' + element.cxp_monto + '</td>';
            table += '<td class="left" >' + element.cxp_estado + '</td>';

            if( element.cxp_estado === 'Pendiente'){
            	table += '<td class="center tbuttons" ><a class="pay tbutton" href="#funo" data-cuota-id=' + element.cxp_id + '>Generar Recibo</a></td>';	
            }else{
            	table += '<td class="center tbuttons" ><a class="tbutton" style=" pointer-events: none; cursor: default;" href="#detalles" >Generado</a></td>';
            }
            
            table += '</tr>';
			
        }else{
            table += '<tr>';
            table += '<td class="center">0</td>';
            table += '<td class="left" >Cero planes encontrados</td>';
            table += '<td class="left" >$00.00</td>';
            table += '<td class="center tbuttons" ></td>';
            table += '</tr>';
        }
    });

    return table;
}

/**
 * Obtener Planes de pago correspondientes a un inmueble
 * @param  {integer} inm_id  	ID del inmueble
 */
function getPlanPago (inm_id) {
	url = baseUrl('uatm/cuota/get_plan_pago');
	$.ajax({
		url: url,
		dataType: 'json',
		data: {
			inm_id: inm_id
		},
		type: 'POST',
		success: function(data){
			if(data.response === true){
				rows = data.message;
				planes = doListaPlan(rows);
				$('table.select_plan tbody').html(planes);
				$('div.plan_list').toggle();	
			}
		}
	});
}

/**
 * Obtener Planes de pago correspondientes a un inmueble
 * @param  {integer} inm_id  	ID del inmueble
 */
function getPlanPagoEmpresa (cnt_id, mode) {
	url = baseUrl('uatm/cuota/get_plan_pago');
	$.ajax({
		url: url,
		dataType: 'json',
		data: {
			cnt_id: cnt_id,
			mode: mode
		},
		type: 'POST',
		success: function(data){
			if(data.response === true){
				rows = data.message;
				planes = doListaPlan(rows);
				$('table.select_plan tbody').html(planes);
				$('div.plan_list').toggle();	
			}
		}
	});
}

/**
 * Constrye lista de planes de pago
 * @param  {array} rows  	Listado de planes de pago historco por inmueble
 * @return {html}          	Listado de planes de pago en formato html
 */
function doListaPlan (rows) {
	$('div.plan_list').hide();	
	 $('table.select_plan tbody').empty(); //Clean old results

    table = '';

    $(rows).each(function (index, element) {
        if( element.pla_id != false){
            table += '<tr>';
            table += '<td class="center">' + element.pla_numero + '</td>';
            table += '<td class="left" >  $' + element.pla_monto + '</td>';
            table += '<td class="left" >' + element.pla_fecha + '</td>';
            table += '<td class="center tbuttons" ><a class="pay tbutton" href="#detalles" data-plan-id=' + element.pla_id + '>Seleccionar</a></td>';
            table += '</tr>';
			
        }else{
            table += '<tr>';
            table += '<td class="center">0</td>';
            table += '<td class="left" >Cero planes encontrados</td>';
            table += '<td class="left" >$00.00</td>';
            table += '<td class="center tbuttons" ></td>';
            table += '</tr>';
        }
    });

    return table;
}

/**
 * Construye mensaje de exito al finalizar la creacion de F1-ISAM y Recarga la pagina para generar uno nuevo
 */
function alertExito() {
    //funId es Generado en doFunoIsam event success
    create("note_success", {
        title: 'Operacion Completada',
        text: 'F1-ISAM Creado exitosamente, Su ID de Recibo es: ' + funoId
    }, {
        expires: false,
        close: function () {
            location.reload(true);
            return false;
        }
    });
}