/**
 * Description:
 * Administra JSON request para generacion de planes de pago FRONT-END
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.5 - 2013/07/11 
 * @since:      2013/06/27
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/07/11
 * + getUserEmpresa - Obtener listado de empresas por id de Contribuyente
 * + doTablaEmpresa - Construir listado de empresas en formato HTML
 * por Alan Alvarenga
 * ------------------------------------------------------------------------------------
 * 2013/06/29
 * + doPlanPago - Crea nuevo plan de pago a plazos 
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/28
 * + setIdInmueble - Establecer el ID del inmueble
 * + doPlanPagoPrevia - Obtiene datos para construir la vista previa de plan de pagos a plazo
 * + doPreviaPlan - Construye el listado de cuotas a cancelar en el plan de pagos
 * por Alan Alvarenga
 * ------------------------------------------------------------------------------------
 * 2013/06/27
 * + getConsolidadoCuenta - Obtiene consolidacion de deuda (mora, multa, intereses, meses morosos)
 * + setDatosCuenta - Construye los datos a mostrar en consolidado de cuenta [FRONT-END]
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 */

/**
 * Initialize jQuery
 */
jQuery(document).ready(function ($) {
    //Inicializar variable de ubicacion
    estoy = 'inmueble';

    //Ocultar listado de inmuebles
    $('div.properties_list').toggle();
    $('div.cuenta_morosa').toggle();
    $('div.previa_pagos').toggle();
    $('div.enterprise_list').toggle(); //Esconder Listado de Empresas

    //Obtener consolidado de cuenta por ID de inmueble
    $('table.selectproperty').on('click', 'a', function () {
        inm_id = $(this).attr("data-inm-id");
        getConsolidadoCuenta(inm_id);
        setIdInmueble(inm_id);
    });

    //Validar 
    $('input[name="nmeses"]').on('keyup', function(){
        if( parseInt($(this).val()) > 12 ){
            alertError();
        }
    });

    //Obtener vista previa de plan de pagos a plazo
    $('form[name="captura_plan"]').on('submit', function (e) {
        e.preventDefault();
        datosPlan = $(this).serialize();

        if ($('input[name="nmeses"]').val() !== "") {
            doPlanPagoPrevia(datosPlan);
        } else {
            alertWarning();
        }

    });

    //Volver a Consolidado de Cuenta Morosa
    $('a.plan').on('click', function (e) {
        e.preventDefault();
        $('div.previa_pagos').toggle(100);
        $('div.cuenta_morosa').toggle(150);

    });

    //Volver a lista de Inmubeles
    $('a.lista').on('click', function (e) {
        e.preventDefault();
        $('div.cuenta_morosa').toggle(250);
        $('div.properties_list').toggle(180);
    });


    //Generar plan de pagos
    $('a.generar').on('click', function (e) {
        e.preventDefault();
        doPlanPago(window.rowsData, window.cuentaId, window.total);
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
        cnt_id = $(this).attr("data-cnt-id");
        getConsolidadoCuentaEmpresa(cnt_id);
        setIdCuenta(cnt_id);
    });

    $('a.imprimir').on('click', function(e){
        e.preventDefault();
        //Recoger todos los datos de la tabla
        datosTabla = $('table.select_previa_pagos').html()
                        .replace(/(\r\n|\n|\r)/gm," ")
                        .replace(/\s+/g," ")
                        .trim();

        
        pdfUrl = baseUrl('uatm/plan/do_pdf_plan_pago_plazo/?tabla='+ datosTabla) ;
        window.location.assign(pdfUrl);
    });
     
});
/**
 * Obtiene consolidacion de deuda (mora, multa, intereses, meses morosos)
 * por ID de Empresa
 * @param  {integer} inm_id ID de inmueble
 * @return {boolean}        Success
 */
function getConsolidadoCuentaEmpresa(cnt_id) {
    url = baseUrl('uatm/plan/get_consolidado_cuenta_empresa');
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            id: cnt_id
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                datos = data.message;
                setDatosCuenta(datos);
            }
        }

    });
    return true;
}



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
 * Obtiene consolidacion de deuda (mora, multa, intereses, meses morosos)
 * por ID de Inmueble
 * @param  {integer} inm_id ID de inmueble
 * @return {boolean}        Success
 */
function getConsolidadoCuenta(inm_id) {
    //Inicializacion de variables locales
    var url = baseUrl('uatm/plan/get_consolidado_cuenta'),
        value = {
            id: inm_id
        };
    //Obtener id de cuenta por id de inmueble    
    verificar.ajax.getCuentaId(value);
    
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            id: inm_id
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                datos = data.message;
                setDatosCuenta(datos);
            }
        }

    });
    return true;
}

/**
 * Construye los datos a mostrar en consolidado de cuenta [FRONT-END]
 * @param {array} datos Obtenidos desde get_consolidado_cuenta[BACK-END]
 */
function setDatosCuenta(datos) {
    //Copia cache
    cuentaMorosa = $('div.cuenta_morosa');
    if(estoy === 'inmueble'){
        //Ocultar lista de propiedades
        $('div.properties_list').toggle(250);    
    } else {
        $('div.enterprise_list').toggle(250);    
    }
    
    //Ocultar 
    cuentaMorosa.hide();

    //Construir UI dependiendo de el valor enviado
    if (datos[0].mor_meses !== false) {
        //Si el boton esta deshabilitado
        $("input").removeAttr('disabled');
        //Numero de meses pendientes
        $('p.adeudado').text(datos[0].mor_meses);
        //Cantidad en mora $$
        $('p.mora').text('$' + datos[0].mor_mora);
        //Multa a pagar
        $('p.multa').text('$' + parseFloat(datos[0].mor_multa).toFixed(2));
        //Intereses a pagar
        $('p.intereses').text('$' + parseFloat(datos[0].mor_interes).toFixed(2));

        //Establecer campos escondidos con los mismos valores
        $('input[name="meses"]').val(datos[0].mor_meses);
        $('input[name="mora"]').val(datos[0].mor_mora);
        $('input[name="multa"]').val(parseFloat(datos[0].mor_multa).toFixed(2));
        $('input[name="interes"]').val(parseFloat(datos[0].mor_interes).toFixed(2));
    } else {
        $('p.adeudado').text('Cero');
        $('input[type="submit"]').prop("disabled", true);
        $('p.mora').text('$ 0.00');
        $('p.multa').text('$ 0.00');
        $('p.intereses').text('$ 0.00');
    }

    return cuentaMorosa.toggle(1000);

}

/**
 * Establecer el ID del inmueble
 * @param {integer} inm_id ID del inmueble
 */
function setIdInmueble(inm_id) {

    return $('input[name="inm_id"]').val(inm_id);

}

/**
 * Establecer el ID del inmueble
 * @param {integer} inm_id ID del inmueble
 */
function setIdCuenta(cnt_id) {

    return $('input[name="cnt_id"]').val(cnt_id);

}


/**
 * Obtiene datos para construir la vista previa de plan de pagos a plazo
 * y dispara la funcion constructura de la vista en [FRONT-END]
 * @param  {array} datosPlan Obtenido desde do_plan_pago_previa [BACK-END]
 * @return {boolean}           Exito
 */
function doPlanPagoPrevia(datosPlan) {
    url = baseUrl('uatm/plan/do_plan_pago_previa');

    $.ajax({
        url: url,
        dataType: 'JSON',
        data: datosPlan,
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                rows = data.message;
                table = doPreviaPlan(rows);
                $('table.select_previa_pagos tbody').html(table);
                $('div.previa_pagos').toggle(100);    

                
                window.cuentaId = data.cuenta;
            }
        }
    });

    return true;

}

/**
 * Construye el listado de cuotas a cancelar en el plan de pagos
 * @param  {[type]} rows [description]
 * @return {[type]}      [description]
 */
function doPreviaPlan (rows) {
    window.rowsData = rows;
    cuentaMorosa.hide(250);
    $('div.previa_pagos').hide();
    $('table.select_previa_pagos tbody').empty();
    prima = parseFloat($('p.multa').text().substring(1,$('p.multa').text().length)) + parseFloat($('p.intereses').text().substring(1,$('p.intereses').text().length));
    table = '';
    table += '<tr class="impar">';
    table += '<td class="center">Prima</td>';
    table += '<td class="center">Pendiente</td>';
    table += '<td class="left">' + prima.toFixed(2) + '</td>';
    table += '</tr>';
    
    montoTotal = 0.00;

    total = 0.00;
    var estilo = 0;
    var css = "";
    
    $.each(rows, function(index, element){
        
        $.each(element, function(i, e){
            monto = parseFloat(e.monto)
            montoTotal = montoTotal+monto;
            window.total = total + monto;
        });
        if(estilo === 0){
            css = 'par';
            estilo = 1;
        }else{
            css = 'impar';
            estilo = 0;
        }

        table += '<tr class="' + css + '">';
        table += '<td class="center">' + index +'</td>';
        table += '<td class="center">Pendiente</td>';
        table += '<td class="left">' + parseFloat(montoTotal).toFixed(2) +'</td>';
        table += '</tr>';
        montoTotal = 0;
    });

    window.total = total + prima;
    return table;
}

/**
 * Crea nuevo plan de pago a plazos 
 * @param  {[type]} rowsData [description]
 * @param  {[type]} inmId    [description]
 * @param  {[type]} total    [description]
 * @return {[type]}          [description]
 */
function doPlanPago (rowsData, inmId, total) {
    url = baseUrl('uatm/plan/do_plan_pago');
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            consolidado: rowsData,
            inm_id: inmId,
            total: total
        },
        type: 'POST',
        success: function (data){
            if(data.response === true){
                plan = data.message;
                idPlan = plan[0].plan_id;
                alertExito(idPlan);
            }
        }
    });
    return true;
}

/**
 * Construye mensaje de exito al finalizar la generacion de plan de pago a plazos
 */
function alertExito(idPlan) {
    
    create("note_success", {
        title: 'Operacion Completada',
        text: 'Plan de Pago a Plazos Generado Existosamente ID de Plan: ' + idPlan
    }, {
        expires: false,
        close: function () {
            location.reload(true);
            return false;
        }
    });
}

/**
 * Alerta al usuario en caso de que no haya seleccionado un rango de fecha 
 */
function alertWarning() {
    create("note_warning", {
        title: 'Advertencia',
        text: 'Debes completar el campo N&uacute;mero de Meses.'
    }, {
        expires: false,
        close: function(){
            $('input[name="nmeses"]').focus();
        }
    });
}

/**
 * Alerta al usuario en caso de que no haya seleccionado un rango de fecha 
 */
function alertError() {
    create("note_error", {
        title: 'Error',
        text: 'El plazo de pago no debe superar los 12 meses'
    }, {
        expires: false,
        close: function(){
            $('input[name="nmeses"]').val("").focus();
        }
    });
}