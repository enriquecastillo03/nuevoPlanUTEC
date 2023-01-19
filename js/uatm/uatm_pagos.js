/**
 * Description:
 * Administra JSON request para asignacion de personas FRONT-END
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.7 - 2013/07/10 
 * @since:      2013/06/07
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/07/08
 * + getPorcentaje -  Obtiene porcentaje de Multa
 * + getTotal   - Calcular Total, Interes y Multa (si procede)
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/07/04
 * + selectAll - Seleccionar todas las mensualidades a cancelar
 * + deSelectAll - Deseleccionar todas las mensualidades a cancelar
 * por Alan Alvarenga
 * ------------------------------------------------------------------------------------
 * 2013/06/13
 * + alertConcepto - Construye mensaje de alerta si el campo concepto esta vacio
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/12
 * + create - Construye una notificacion flotante 
 * + alertExito - Construye mensaje de exito al finalizar la creacion de F1-ISAM y 
 *                Recarga la pagina para generar uno nuevo
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/11
 * + getUserPropiedad - Obtiene propiedades por ID persona desde get_user_propiedad[BACK-END]
 * + doPropertiesTable - Construye tabla de resultados para getUserPropiedad[FRONT-END]
 * + getEstadoCuenta - Obtiene el estado de cuenta corriente por ID de inmueble desde 
 *                     get_estado_cuenta[BACK-END]
 * + doEstadoCuentaTable - Construye tabla de resultados para getEstadoCuenta[FRONT-END]
 * + setPagoCuenta - Obtiene los servicios a pagar seleccionados por el usuario y
 *                   los organiza en un array multidimensional [FRONT-END]
 * + doDetallesPagoTabla - Construye tabla de resultados para setPagoCuenta[FRONT-END]
 * + doFunoIsam - Construye nuevo F1-ISAM
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
    /**
     * Organizar elementos que no deben ser visto desde el inicio
     */
    estoy = 'inmueble';
    $('div.properties_list').toggle(); //Esconder div Listado de Propiedades
    $('div.account_status').hide(); //Esconder div Estado de Cuenta
    $('div.detallefuno').hide(); //Esconder div Detalle F1-ISAM
    $('a.detailsf').hide(); //Esconder hasta que se seleccione un servicio a pagar
    $('div.enterprise_list').toggle(); //Esconder Listado de Empresas

    //Seleccionar propiedades 
    $('table.selectproperty').on('click', 'a', function () {
        $('div.properties_list').slideUp('normal'); //Disolver Listado de Propiedades
        $('div.account_status').toggle(); //Mostrar Estado de Cuenta
        value = $(this).attr('data-inm-id'); //Get id inmueble
        url = 'pagos/get_estado_cuenta'; // URL de funcion Obtener estado de cuenta
        //Obtener estado de cuenta
        getEstadoCuenta(url, value); 
    });

    $('table.selectenterprise').on('click', 'a', function (e) {
        e.preventDefault();
        cnt_id = $(this).attr('data-cnt-id'); //Get id inmueble

        //Obtener estado de cuenta
        getEstadoCuentaEmpresa(cnt_id);

    });

    //Volver a lista de Propiedades
    $('a.volver').on('click', function (e) {
        e.preventDefault(); //Anular accion por default
        $('span.subtotal').html('$00.00');
        $('span.interes').html('$00.00');
        $('span.multa').html('$00.00');
        $('span.total').html('$00.00');
        $('a.select_all').text('Seleccionar Todo');

        //Pertenece a fn namespace de interes.js
        interes.fn.resetVariablesZero();

        if(estoy === 'empresa'){
            $('div.account_status').slideUp(280); //Esconder Estado de Cuenta
            $('table.selectaccount tbody').empty(); //Clean old results
            $('div.enterprise_list').toggle(300); //Mostrar Lista de propiedades
        }else{
            $('div.account_status').slideUp(280); //Esconder Estado de Cuenta
            $('table.selectaccount tbody').empty(); //Clean old results
            $('div.properties_list').toggle(300); //Mostrar Lista de propiedades
        }
       
        
    });

    //Seleccionar servicios a pagar
    $('table.selectaccount').on('click', 'a', function (e) {
        e.preventDefault(); //Anular accion por default
        var $this = $(this),
            subCuota = 0;
        /*
           Partiendo de a.pay Obtener elemento padre (td) -> Obtener elemento padre (tr)
           -> Obtener Sibiling Superior (tr) -> Encontrar Child (a.pay) -> Obtener texto del elemento 
           */
           var validate_text_superior = $this.parent()
                                        .parent()
                                        .prev()
                                        .find('a.pay')
                                        .text();
        /*
           Partiendo de a.pay Obtener elemento padre (td) -> Obtener elemento padre (tr)
           -> Obtener Sibiling Inferior (tr) -> Encontrar Child (a.pay) -> Obtener texto del elemento 
           */
           var validate_text_inferior = $this.parent()
                                        .parent()
                                        .next()
                                        .find('a.pay')
                                        .text();

        //Capturando el monto a pagar de la fila seleccionada
        subCuota = $this.parent()
                    .prev()
                    .prev()
                    .text()
                    .trim();

        //Retirar el signo de dolar y convertir a flotante
        subCuota = parseFloat(subCuota.slice(1, subCuota.length));



        //Validar seleccion de elemento
        if(validate_text_superior === 'Seleccionar'){
            alertSelect('El pago de las mensualidades debe ser consecutivo.');
        }else{
            if ($this.text() === 'Seleccionar') {

                $this.text('Pagar');//Cambiar texto a Pagar
                $this.parent()
                .parent()
                .addClass('dopay'); //Agregar clase dopay al TR que contiene este TD
                
                //interes.fn = namespace perteneciente a interes.js
                console.log('a calcular');
                interes.fn.calcularInteres();
                interes.fn.getSubTotal(subCuota, "sumar");

                if($this.attr('data-mora') == 0 ){
                    $('a.select_all').text('Deseleccionar todo');   
                }

                $('a.detailsf').hide(); //Ocultar Generar F1-ISAM
                $('a.detailsf').toggle(); //Mostrar Generar F1-ISAM

            } else if( validate_text_inferior !== 'Pagar'){

                $this.text('Seleccionar');//Cambiar texto a Seleccionar
                $this.parent().parent().removeClass('dopay'); //Remover clase dopay al TR que contiene este TD

                if(validate_text_superior !== 'Pagar'){
                    $('a.detailsf').toggle(); //Ocultar Generar F1-ISAM
                }

            }else{
                alertSelect('Solo puedes descartar mensualidades de manera consecutiva.');
            }
        }
    });

    //Mostrar vista previa F1-ISAM
    $('a.detailsf').on('click', function (e) {

        e.preventDefault(); //Anular accion por default
        $('div.detallefuno').toggle(280); //Mostrar div detalle F1-ISAM
        detallesPago  = setPagoCuenta(); //Obtener detalles de Pago para F1-ISAM
        $('div.account_status').slideUp(300); //Esconder el div Estado de Cuenta
        tabla         = doDetallesPagoTabla(detallesPago); //Obtener estructura HTML para tabla detalles de pago
        $('table.listadetalle tbody').html(tabla); //Insertar tabla 
        $('input[name ="total"]').val(funoTotal); //Mostrar total de cargos (toFixed(int) aproxima decimales)
        
        if (detallesPago[0].det_fecha === detallesPago[detallesPago.length-1].det_fecha) {

            periodo = detallesPago[0].det_fecha;

        } else {

            periodo = detallesPago[0].det_fecha + 'a ' + detallesPago[detallesPago.length-1].det_fecha;

        }

        var patron = 'Pagar';
        var np =" ";

        nuevo_periodo  = periodo.replace(patron,np);
        
        nuevo_periodo = nuevo_periodo.replace(patron,np);

        imprimir_concepto1 = 'Cancelacion de tasas municipales para el periodo correspondiente a : ' + nuevo_periodo ;

        $('textarea[name="concepto"]').val(imprimir_concepto1);
        
        //Contruir y Mostrar nombre y Direccion de contribuyente
        $('input[name="contribuyente"]').val($('input[name="mask_name"]').val());
        setFunoInputDisabled();
    });

    //Crear F1-ISAM
    $('#dofuno').on('click', function (e){

        e.preventDefault(); //Anular accion por default
        url = 'pagos/do_funo_isam'; //Url funcion do_funo_isam [BACK-END]

        if ($('textarea[name="concepto"]').val() !== "" || $('textarea[name="concepto"]').val().length > 10) {
            doFunoIsam();//Crear F1-ISAM
        } else {
            alertConcepto();//Validar campo concepto a la antigua
            return false;
        }
    });

    //Crear base notificacion
    $notifications = $("#notifications").notify();

    //Seleccionar o Deseleccionar todas las cuotas
    $('a.select_all').on('click', function (e) {
        e.preventDefault();
        
        elements = $('table.selectaccount tbody a');

        texto = $(this).text();

        if ( texto === 'Seleccionar Todo') {

            selectAll(elements);
            
        } else {

            deSelectAll(elements);
        }
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

  
});
//----------------------------------------- Opcional ------------------------------------------------------//
//

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
            table += '<a class="tbutton" href="#display_account" data-cnt-id="' + element.id;
            table += '" data-mora="' + element.det_fecha_old + '">Ver</a></td>';
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

//----------------------------------------- PASO 2 -------------------------------------------------------//


/**
 * Obtiene el estado de cuenta corriente por ID de inmueble 
 * @param  {String} url     Funcion get_estado_cuenta [BACK-END]
 * @param  {Integer} value   ID de inmueble
 */
 function getEstadoCuenta(url, value) {
    //Generar Solvencia en Estado de cuenta, obtener el id de cuenta corriente
    var idSolvencia = {
        id: value
    };
    //interes.ajax namespace pertenece a interes.js
    interes.ajax.getCuentaId(idSolvencia);
    
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            inm_id: value
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                rows = data.message;
                table = doEstadoCuentaTable(rows);
                $('table.selectaccount tbody').html(table);
            }
        }
    });
}

/**
 * Obtener el estado de cuenta de una empresa
 * @param  {[type]} cnt_id [description]
 * @return {[type]}        [description]
 */
function getEstadoCuentaEmpresa (cnt_id) {
    var url = baseUrl('uatm/pagos/get_estado_cuenta_empresa'),
        cuenta = {
            id: cnt_id
        };
        interes.ajax.getEstadoPlanPago(cuenta);

    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            cnt_id: cnt_id
        },
        type: 'POST',
        success: function(data){
            if(data.response === true){
                rows = data.message;
                table = doEstadoCuentaTable(rows);
                $('table.selectaccount tbody').html(table);
                $('div.account_status').toggle(400);
                $('div.enterprise_list').toggle(400);
            }
        }
    });
}

/**
 * Contruye la tabla de resultados para getEstadoCuenta
 * @param  {JSON} rows      All the information to build the table
 * @return {HTML}           Table in HTML format
 */
 function doEstadoCuentaTable(rows) {
    $('table.selectaccount tbody').empty(); //Clean old results
    table = "";
    //montoAdeudado perteneciente a interes.js
    montoAdeudado = 0;
    //Ancla de generacion de solvencia
    var solvencia = $("a.solvencia ");

    $(rows).each(function (index, element) {
        if( element.det_fecha !== false){
            //Esconder boton de solvencia
            solvencia.hide();
            table += '<tr>';
            table += '<td class="left">' + element.det_fecha + '</td>';
            table += '<td class="left" >' + element.det_servicio + '</td>';
            table += '<td class="left" > $' + element.det_monto + '</td>';

            if(element.det_fecha_old >= 2){
                table += '<td class="left" > Mora </td>';
                //montoAdeudado perteneciente a interes.js
                montoAdeudado = montoAdeudado + element.det_monto;
                //numeroMeses perteneciente a interes.js
                numeroMeses = numeroMeses + 1;
            }else{
                if (element.det_fecha_old < 0) {
                    table += '<td class="left" > Futuro </td>';    
                } else {
                    table += '<td class="left" > Corriente </td>';        
                }
                
            }
            
            table += '<td class="center tbuttons" >';
            table += '<a class="pay tbutton" href="#display_account" data-det-id="' + element.id + '"';
            table += 'data-mora="' + element.det_fecha_old + '"">Seleccionar</a>';
            table += '</td>';
            table += '</tr>';
            
        }else{
            //Mostrar boton de solvencia
            solvencia.show();
            table += '<tr>';
            table += '<td class="center">0</td>';
            table += '<td class="left" > Felicidades su cuenta est&aacute; al d&iacute;a</td>';
            table += '<td class="left" >$00.00</td>';
            table += '<td class="left" >Corriente</td>';
            table += '<td class="center tbuttons" ></td>';
            table += '</tr>';
        }

    });

    return table;
}

//----------------------------------------- PASO 3 -------------------------------------------------------//

/**
 * Obtiene los servicios a pagar seleccionados por el usuario y los organiza en un array multidimensional
 * @return {Object Array}     Arreglo de objectos con los detalles de cuenta corriente por pagar
 */
 function setPagoCuenta() {
    var detallesPago = [];
    $('table.selectaccount tbody tr').map(function () {
        var $row = $(this);
        if ($row.hasClass('dopay') === true) {
            detallesPago.push({
                'det_fecha': $row.find(':nth-child(1)').text(),
                'det_descripcion': $row.find(':nth-child(2)').text(),
                'det_monto': $row.find(':nth-child(3)').text(),
                'det_id': $row.find(':nth-child(5)').find('a').attr('data-det-id')
            });
        }
    }).get();
    return detallesPago;
}

/**
 * Construye la tabla de detalles de pago para F1-ISAM
 * @param  {Object Array} rows  Arreglo con todos los datos seleccionados a pagar
 * @return {String}             Estructura de tabla HTML con los elemento seleccionados
 */
 function doDetallesPagoTabla(rows) {
    $('table.listadetalle tbody').empty(); //Limpiar antiguos resultados si existen
    tabla = '';
    totalCargo = 0;

    $.each(rows, function (index, element) {
        tabla += '<tr>';
        tabla += '<td class="left">' + element.det_descripcion + '</td>';
        tabla += '<td class="left">' + element.det_monto + '</td>';
        tabla += '</tr>';

        //Convertir a float para sumarlo en el total que se mostrara en F1-ISAM y subtotal en F1-ISAM DB
        totalCargo = totalCargo + parseFloat(element.det_monto.substring(2, element.det_monto.length));
    });
    return tabla;
}

//----------------------------------------- PASO 4 -------------------------------------------------------//
/**
 * Hacer Nuevo F1-ISAM
 * @return {Boolean} TRUE para creacion exitosa, FALSE para fallo en la creacion
 */
 function doFunoIsam() {
    var contribuyente = $('input[name="contribuyente"]').val();
    var concepto = $('textarea[name="concepto"]').val();
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            fun_detalle: detallesPago,
            fun_contribuyente: contribuyente,
            fun_subtotal: subTotal,
            fun_multa: funoMulta,
            fun_interes: funoInteres,
            fun_total: funoTotal,
            fun_concepto: concepto
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                funoResult = data.message;
                $.each(funoResult, function(index, element){
                    funId = element.fun_id; //Obtener el ID del recibo F1-ISAM
                });
                alertExito(); 
            }
        }
    });
}

function setFunoInputDisabled(){
    //$('input[name="contribuyente"]').prop('disabled', true);
    $('input[name="municipalidad"]').prop('disabled', true);
    $('input[name="total"]').prop('disabled', true);
    $('textarea[name="concepto"]').prop('disabled',true);
}

//----------------------------------------- Notificaciones ----------------------------------------------//

 /**
  * Construye una notificacion flotante 
  * @param  {String} template   Plantilla HTML a utilizar, ubicada en PATH{view/uatm/pagos/index.php}
  * @param  {Object} vars       Titulo, texto, icono, etc
  * @param  {Object} opts       Parametros opcionales
  * @return {jQuery Object}     Notificacion flotante usando el plugin jQuery.notify.js
  */
  function create( template, vars, opts ){
    return $notifications.notify("create", template, vars, opts);
}

/**
 * Construye mensaje de exito al finalizar la creacion de F1-ISAM y Recarga la pagina para generar uno nuevo
 */
 function alertExito() {
    //funId es Generado en doFunoIsam event success
    create("note_success", {
        title: 'Operacion Completada',
        text: 'F1-ISAM Creado exitosamente, Su ID de Recibo es: '+funId
    }, {
        expires: false,
        close: function () {
            location.reload(true);
            return false;
        }
    });
}

/**
 * Construye mensaje de alerta si el campo concepto esta vacio
 */
 function alertConcepto() {
    create("note_warning", {
        title: 'Advertencia',
        text: 'Por favor, debes introducir un concepto valido para generar el recibo.'
    }, {
        expires: false,
        close: function () {
            $('textarea[name="concepto"]').focus();
        }
    });
}

/**
 * Construye mensaje de alerta si el campo concepto esta vacio
 */
 function alertSelect(mensaje) {
    create("note_warning", {
        title: 'Advertencia',
        text: mensaje
    }, {
        expires: false
    });
}

//-------------------------------------------- Funcionalidades ---------------------------------------------//

/**
 * Seleccionar todas las mensualidades a cancelar
 * @param  {array} elements     Listado de botones seleccionar en la tabla cuotas
 */
 function selectAll (elements) {
    $('a.select_all').text('Deseleccionar todo');
   
    subTotal = 0;
    $.each(elements, function (index, element) {
        $(element).text('Pagar').parent().parent().addClass('dopay');
        
        //Capturando el monto a pagar de la fila seleccionada
        subCuota = $(element).parent().prev().prev().text().trim();
        //Retirar el signo de dolar y convertir a flotante
        subCuota = parseFloat(subCuota.slice(1, subCuota.length));

        interes.fn.getSubTotal(subCuota, "sumar");
        interes.fn.calcularInteres();
        $('a.detailsf').hide();
        $('a.detailsf').toggle();
    });
    
}

/**
 * Deseleccionar todas las mensualidades a cancelar
 * @param  {array} elements     Listado de botones seleccionar en la tabla cuotas
 */
 function deSelectAll (elements) {
    $('a.select_all').text('Seleccionar Todo');

    //Remover clase dopay (para evitar ser cobrado)
    $.each(elements, function (index, element) {
        $(element).text('Seleccionar').parent().parent().removeClass('dopay');
        //Capturando el monto a pagar de la fila seleccionada
        subCuota = $(element).parent().prev().prev().text().trim();
        //Retirar el signo de dolar y convertir a flotante
        subCuota = parseFloat(subCuota.slice(1, subCuota.length));
        interes.fn.getSubTotal(subCuota, "restar");
        $('span.multa').html('$00.00');
        $('span.interes').html('$00.00');
        $('span.total').html('$00.00');
        $('a.detailsf').hide();
    });
    
}
