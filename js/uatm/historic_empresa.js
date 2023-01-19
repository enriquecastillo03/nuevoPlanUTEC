/**
 * Description:
 * Administra JSON request para cargos por lotes FRONT-END
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.4 - 2013/06/19 
 * @since:      2013/06/14
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/06/20
 * + alertWarning -  Alerta al usuario en caso de que no haya seleccionado un rango de fecha
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/19
 * + alertUniqueKey - Construye mensaje de alerta si el constrait unique key devuelve un error 
 * por Alan Alvarenga 
 * -------------------------------------------------------------------------------------
 * 2013/06/17
 * + setCargoCuenta - Insercion por lotes de cargos o abonos a detalles de cuenta corriente 
 *                    calculando el monto de cargo o abono en cada insercion de acuerdo a la
 *                     tasa de cobro correspondiente para la fecha de ingreso
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/15
 * + getDocumento - Obtener documentos por ID de persona
 * + getServicioCuenta - Obtener servicios por ID de cuenta corriente
 * + doServiciosTable - Construye tabla de servicios por cuenta corriente
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/14
 * + getIdServicio - Obtiene los servicios a pagar seleccionados por el usuario y 
 *                   los organiza en un array multidimensional
 * + getNumeroCuentaInm - Obtener numero de Cuenta por inmueble
 * +doMostrarInformacion - Mostrar informacion del propietario de la cuenta corriente
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/07
 * + jQuery.ready - Inicializa todas las funciones de asignacion para esta vista
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 */

/**
 * Initialize jQuery
 */
jQuery(document).ready(function ($) {
    // Stuff to do as soon as the DOM is ready;
    $('div.properties_list').hide(); //Ocultar lista de propiedades
    
    //Ocultar docharge
    $('input[name="dohcharge"]').hide();

    //Seleccionar id de propiedades
    $('table.selectproperty').on('click', 'a', function (e) {
        e.preventDefault();
        inmId = $(this).attr('data-inm-id');
        getNumeroCuentaInm(inmId);

        $('div.properties_list').slideUp(300); //Ocultar Listado de Inmuebles

    });

 


   jsDatePicker();

    //Checkbox Iphone Style
    $('span.checkbox_4 :checkbox').iphoneStyle({
        checkedLabel: 'Pendiente',
        uncheckedLabel: 'Abonado'
    });

    //Seleccionar servicios a cobrar
 $('table.selectservicios').on('click', 'a', function () {
     $this = $(this);

     if ($this.text() == 'Seleccionar') {
         $this.text('Seleccionado'); //Cambiar texto a Pagar
         $this.parent().parent().addClass('dopay'); //Agregar clase dopay al TR que contiene este TD
         $('input[name="dohcharge"]').fadeIn();
     } else {
         $this.text('Seleccionar'); //Cambiar texto a Seleccionar
         $this.parent().parent().removeClass('dopay'); //Remover clase dopay al TR que contiene este TD
     }
 });

     //Crear base notificacion
   // $notifications = $("#notifications").notify();

 //Esconder datos Batch
 $('div.datosbach').fadeOut(-1);

 //Enviar cargos batch
 $('form[name="cargos-bach"]').on('submit', function (e) {
     e.preventDefault(); //Prevenir accion default

     //Obtener fecha inicio rango
     inicio = $('input[name="fechainicio"]').val();

     //Obtener fecha fin rango
     fin = $('input[name="fechafin"]').val();

     if(inicio === "" || fin === ""){
        alertWarning();
     }else{
        //Obtener estado (cargo o abono)
         estado = $('input[name="estado"]').is(':checked') ? 1 : 0;

         //Obtener el id de los servicios seleccionados
         detallesServicio = getIdServicio();

         //Cargar o Abonar a cuenta
         setCargoCuenta(inicio, fin, detallesServicio, estado);   
     }
     
 });


});
//------------------------------------------------- PASO 1 -------------------------------------------------//

/**
 * Obtiene los servicios a pagar seleccionados por el usuario y los organiza en un array multidimensional
 * @return {Object Array}     Arreglo de objectos con los detalles de cuenta corriente por pagar
 */
function getIdServicio() {
    detallesServicio = [];
    $('table.selectservicios tbody tr').map(function () {
        var $row = $(this);
        if ($row.hasClass('dopay') === true) {
            detallesServicio.push({
                'id': $row.find(':nth-child(2)').find('a').attr('data-srv-id')
            });
        }
    }).get();

    return detallesServicio;
}

/**
 * Obtener numero de Cuenta por inmueble
 * @param  {integer} inmId ID del inmueble
 */
function getNumeroCuentaInm(inmId) {
    //debugger; 

    //alert(inmId)
    url = 'historico/get_numero_cuenta_emp';
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            id: inmId
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                info = data.message;
                doMostrarInformacion(info);
                $('div.datosbach').toggle();
            }
        }
    });
}
/**
 * Mostrar informacion del propietario de la cuenta corriente
 * @param  {array} info     Datos completos del propietario
 */
function doMostrarInformacion(info) {

    //Set nombre completo
    $('p.nombrecompleto').text($('input[name="mask_name"]').val());
    //Set numero de cuenta 
    $('p.numerocuenta').text(info[0].cnt_numero).attr('data-cuenta-id', info[0].id);
    id = $('input[name="id_name"]').val();
      //alert(id)
    //Obtener documentos por persona
    getDocumento(id);

    //Obtener servicios por cuenta corriente
    getServiciosCuenta(info[0].id);

}

//------------------------------------------------- PASO 2 -------------------------------------------------//
/**
 * Obtener documentos por ID de persona
 * @param  {integer} id     User ID
 */
function getDocumento(id) {
    url = baseUrl('busqueda/buscar/get_documento_contribuyente');
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            id: id
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                documentos = data.message;
                $.each(documentos, function (index, element) {
                    switch (element.doc_type) {
                        case 'DUI':
                            //Mostrar numero de documento unico
                            $('p.dui').text(element.doc_number);
                            break;
                        case 'NIT':
                            //Mostrar numero de identificacion tributaria
                            $('p.nit').text(element.doc_number);
                            break;
                        default:
                            $('p.dui').text('0 Registros encontrados');
                            $('p.nit').text('0 Registros encontrados');
                    }
                });
            } else {
                documentos = false;
            }
        }
    });

}

/**
 * Obtener servicios por ID de cuenta corriente
 * @param  {integer} id ID de inmueble
 */
function getServiciosCuenta(id) {
 url = baseUrl('uatm/historico/get_servicios_cuenta_empresa');

$.ajax({
        url: url,
        dataType: 'json',
        data: {
            id: id
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                rows = data.message;

                //Obtener tabla de servicios
                table = doServiciosTable(rows);

                //Limpiar resultados viejos si existen
                $('table.selectproperty tbody').empty();

                //Mostrar datos
                $('table.selectservicios tbody').html(table);

            }
        }
    });
}

/**
 * Construye tabla de servicios por cuenta corriente
 * @param  {array} rows     Array de servicios por cuenta corriente
 */
function doServiciosTable(rows) {
    $('table.selectservicios tbody').empty();
    table = '';
    $.each(rows, function (index, element) {
        if(element.id !== false){
            table += '<tr>';
            table += '<td class="left">' + element.inm_srv_nombre + '</td>';
            table += '<td class="center tbuttons" ><a class="pay tbutton" href="#select" data-srv-id=' + element.id + '>Seleccionar</a></td>';
            table += '</tr>';    
        }else{
            table += '<tr>';
            table += '<td class="left">Lo sentimos, esta cuenta no posee ningún servicio asociado</td>';
            table += '<td class="center tbuttons" ></td>';
            table += '</tr>';
        }
        
    });

    return table;
}

//------------------------------------------------- PASO 2 -------------------------------------------------//

/**
 * Insercion por lotes de cargos o abonos a detalles de cuenta corriente 
 * calculando el monto de cargo o abono en cada insercion de acuerdo a la
 * tasa de cobro correspondiente para la fecha de ingreso
 * @param {date} inicio            Inicio rango de fecha
 * @param {date} fin               Fin rango de fecha
 * @param {array} detallesServicio ID servicios
 */
function setCargoCuenta (inicio, fin, detallesServicio) {

     url = baseUrl('uatm/historico/set_cargo_cuenta');
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            inicio: inicio,
            fin: fin,
            detalles: detallesServicio, 
            estado: estado
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
               if(data.message[0].success === true){
                    alertExito();
               }else{
                    alertUniqueKey();
               }
             }
        }
    });
}

//----------------------------------------- Notificaciones ----------------------------------------------//
 
 /**
  * Construye una notificacion flotante 
  * @param  {String} template   Plantilla HTML a utilizar, ubicada en PATH{view/uatm/histico/index.php}
  * @param  {Object} vars       Titulo, texto, icono, etc
  * @param  {Object} opts       Parametros opcionales
  * @return {jQuery Object}     Notificacion flotante usando el plugin jQuery.notify.js
  */
function create( template, vars, opts ){
    return $notifications.notify("create", template, vars, opts);
}

/**
 * Construye mensaje de exito al finalizar la insercion Batch de cargos o abonos
 */
function alertExito() {
    //funId es Generado en doFunoIsam event success
    create("note_success", {
        title: 'Operacion Completada',
        text: 'Inserci&oacute;n por Lotes Completada!'
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
        text: 'Debes seleccionar un rango de fecha valido.'
    }, {
        expires: false,
        close: function(){
            if(inicio === ""){
                $('input[name="fechainicio"]').focus();
            }else{
                $('input[name="fechafin"]').focus();
            }
        }
    });
}
/**
 * Construye mensaje de alerta si el constrait unique key devuelve un error 
 * (se repiten id de servicio por cuenta y fecha - NO SE PUEDE CARGAR DOS VECES EL MISMO SERVICIO A LA 
 * MISMA CUENTA)
 */
function alertUniqueKey() {
    create("note_error", {
        title: 'Error',
        text: 'Verifica los servicios y rango de fechas seleccionado.'
    }, {
        expires: false
    });
}