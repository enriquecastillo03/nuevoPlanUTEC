/**
 * Description:
 * Administra JSON request para cierres mensuales a cuentas corrientes FRONT-END
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.1 - 2013/06/21 
 * @since:      2013/06/21
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/06/22
 * + setCargoEmpresa -  Realizar cargos por servicio a cuenta corriente de empresas
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/21
 * + setCargoInmueble - Realizar cargos por servicio a cuenta corriente de inmuebles
 * + doCargoMensual -  Realizar cargo mensual a cuentas corrientes de empresas e inmuebles
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 */

/**
 * Initialize jQuery
 */
jQuery(document).ready(function ($) {
    // Stuff to do as soon as the DOM is ready;

    //Checkbox Iphone Style
    $('span.checkbox_4 :checkbox').iphoneStyle({
        checkedLabel: 'Inmuebles',
        uncheckedLabel: 'Empresas'
    });

    //Event listener on click anchor 
    $('form a').on('click', function (e) {
        e.preventDefault();

        //Obtener tipo de cargo 1 = Inmueble, 2 = Empresa
        tipo = $('input[name="tipo"]').is(':checked') ? 1 : 0;

        //Realizar cargo mensual
        doCargoMensual(tipo);
    });

    $('div.omega').toggle();
});

/**
 * Realizar cargo mensual a cuentas corrientes de empresas e inmuebles
 * @param  {integer} tipo    Tipo de cuenta corriente
 */
function doCargoMensual(tipo) {
    /*
        1 - Inmueble
        2 - Empresa
     */
    if (tipo === 1) {
        setCargoInmueble();
    } else {
        setCargoEmpresa();
    }
}

/**
 * Realizar cargos por servicio a cuenta corriente de inmuebles
 * do_cargo_mensual_inmueble [BACK-END]
 */
function setCargoInmueble() {
    url = baseUrl('uatm/cierre/set_cargo_inmueble');
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                message = data.message;
                //Generar respuesta HTML 
                doRespuestaCargo(message);
            }
        }
    });
}

/**
 * Realizar cargos por servicio a cuenta corriente de empresas
 * do_cargo_mensual_empresa [BACK-END]
 */
function setCargoEmpresa() {
    url = baseUrl('uatm/cierre/set_cargo_empresa');
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                message = data.message;
                doRespuestaCargo(message);
            }
        }
    });
}

/**
 * Construye respuesta HTML para la generacion de cargos a cuenta 
 * corriente de inmuebles y empresas
 * @param  {JSON} message   Cantidad de cuentas y cargos afectados y/o realizados
 */
function doRespuestaCargo(message) {

    //Ocultar div de respuesta
    $('div.omega').hide();

    //Limpiar antiguos resultados si existen
    $('div.informacion').empty();

    //Sacar la cantidad de cuentas y cargos 
    $.each(message, function (index, element) {
        arrCuentasCargos = element.car_respuesta;
    });

    //Generar la Fecha de cargo
    newDate = new Date();
    anyo = newDate.getFullYear();
    mes = newDate.getMonth() + 1;
    dia = newDate.getDate();
    //Ultimo dia del corriente mes
    lastDay = new Date(newDate.getFullYear(), newDate.getMonth() + 1, 0);

    //Small aesthetic fix
    if (mes < 10) mes = "0" + mes;
    if (dia < 10) dia = "0" + dia;

    //Construir respuesta HTML
    if (arrCuentasCargos !== false) {
        cuentasCargos = arrCuentasCargos.split(':');
        informacion = '';
        informacion += '<h5>Fecha de Cargo</h5>';
        informacion += '<p>' + lastDay.getDate() + '-' + mes + '-' + anyo + '</p>';
        informacion += '<h5>Cuenta(s) Afectada(s)</h5>';
        informacion += '<p>' + cuentasCargos[0] + '</p>';
        informacion += '<h5>Cargo(s) Realizado(s)</h5>';
        informacion += '<p>' + cuentasCargos[1] + '</p>';
        alertExito();

    } else {
        informacion = '';
        informacion += '<h5>Fecha de Cargo</h5>';
        informacion += '<p>' + lastDay.getDate() + '-' + mes + '-' + anyo + '</p>';
        informacion += '<h5>Cuenta(s) Afectada(s)</h5>';
        informacion += '<p> Cero </p>';
        informacion += '<h5>Cargo(s) Realizado(s)</h5>';
        informacion += '<p> Cero </p>';
        alertAdvertencia();
    }

    //Mostrar respuesta
    $('div.informacion').html(informacion);
    $('div.omega').toggle(200);

}

function alertExito () {
    create("note_success", {
           title:'Operacion Completada',
           text:'Cargos mensuales a cuenta corriente realizados exitosamente.'
           }, { expires: false
    });

}

function alertAdvertencia () {
    create("note_warning", {
           title:'Advertencia',
           text:'El cierre cuenta mensual ya fue realizado en esta fecha.'
           }, { expires: false
    });

}