/**
 * Description:
 * Administra JSON request para asignacion de personas FRONT-END
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.1 - 2013/06/05 
 * @since:      2013/06/05
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/06/21
 * - FIXED - Separacion de personas y contribuyentes en el modal de busqueda utilizando
 *            una bandera de busqueda enviada a traves de un atributo personalizado 
 *            para seleccionar la funcion la cual devolvera la informacion de la busqueda
 *            agregue data-mode="contribuyente" para buscar en con_contribuyente en a.loadmodal 
 *            de la ventana de busqueda 
 * por Alan Alvarenga
 * ------------------------------------------------------------------------------------
 * 2013/06/13
 * + doAestheticAction - Muestra y/o oculta elementos previamente cargados en la pagina
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/11
 * + checkAction - CALLBACK de acciones a tomar al finalizar un evento especifico
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/05
 * + jQuery.ready - Inicializa todas las funciones de asignacion para esta vista
 * + getAsignarPersona - Obtiene informacion de persona get_persona[BACK-END] y 
 *                       asigna su id a un campo escondido para despues realizar 
 *                       busquedas especificas por id de persona
 * + doAsignarTabla - Construye tabla de resultados para getAsignarPersona[FRONT-END]
 * by Alan Alvarenga
 * -------------------------------------------------------------------------------------
 */

/**
 * Initialize jQuery
 */
jQuery(document).ready(function ($) {
    // Stuff to do as soon as the DOM is ready;

    $('#searchbox').submit(function (e) {
        e.preventDefault();
        $(this).find('a.button').focus();
    });

    //Usar clase loadmodal para especificar de que boton se debe mostrar el modal
    $('a.loadmodal').on('click', function () {

        //Ocultar/ Mostrar elementos previos
        doAestheticAction();

        //Obtener Parent Node, obtener Sibling Node despues obtener el primer input
        input = $(this).parent().prev().find('input');

        //Este input contiene todas las palabras clave que se utilizar como parametros 
        //para realizar la busqueda
        value = input.val();

        //Obtener el atributo name del input, se utilizara para establecer el nombre completo 
        //del usuario seleccionado
        target0_input = input.attr('name');

        //Obtener el atributo name del input, se utilizara para establecer el id de la persona
        //seleccionada
        target1_input = input.next().attr('name');

        //Obtener el atributo name del input, se utillizarra para establecer la direccion de la 
        //persona en el F1-ISAM
        target2_input = input.next().next().attr('name');

        //Obtener modo de busqueda
        flag = $(this).attr('data-mode');
        
        //Donde esta localizado nuestro controller 
        if(flag === "contribuyente"){
            //buscar en UATM
            url = baseUrl('busqueda/buscar/get_contribuyente');    
        }else{
            //buscar en REF
            url = baseUrl('busqueda/buscar/get_persona');
        }

        //Magic ;)
        getAsignarPersona(url, value);

        //FIX two clicks load on FancyBox
        fancyWindow = $(this).attr('href');

        //$('table.selectaccount tbody').empty(); Mejorar comportamiento de ESTADO DE CUENTA al abrir otra ventana
        
        //Fancybox Modal
        $.fancybox({
            'href': fancyWindow,
                'speedIn': 250,
                'speedOut': 250,
                'transitionIn': 'fade',
                'transitionOut': 'fade'
        });



    });

    //Use event delegate
    $('table.selectmodal').on('click', 'a', function (e) {

        e.preventDefault();

        //Set values.
        $('input[name="' + target0_input + '"]').attr('value', $(this).attr('data-fullname'));
        $('input[name="' + target1_input + '"]').attr('value', $(this).attr('data-id'));
        $('input[name="' + target2_input + '"]').attr('value', $(this).attr('data-address'));

        $.fancybox.close(); //close modal

        $('table tbody').empty(); //Limpiar resultados si existen

        checkAccion(); //Revisar acciones a tomar
    });

});

/**
 * Obtiene informacion de persona get_persona[BACK-END] y asigna su id
 * a un campo escondido para despues realizar busquedas especificas 
 * por id de persona
 * @param  {String} URL   Controller URL
 * @param  {String} value Keywords to search
 */
function getAsignarPersona(url, value) {
    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            search: value
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                rows = data.message;
                table = doAsignarTabla(rows);
                $('table.selectmodal tbody').html(table);
            }
        }
    });
}

/**
 * Construye tabla de resultados para getAsignarPersona[FRONT-END]
 * @param  {JSON} rows      All the information to build the table
 * @return {HTML}           Table in HTML format
 */
function doAsignarTabla(rows) {

    $('table.selectmodal tbody').empty(); //Limpiar resultados si existen

    table = '';
    
    $(rows).each(function (index, element) {
        
        if(element.id !== false){
            table += '<tr>';
            table += '<td class="left">' + element.name + '</td>';
            table += '<td class="center"><div title="' + element.address + '">Ver informacion</div></td>';
            table += '<td class="center">';
            table += '<a class="assign tbutton" href="#" data-address="'+element.address+'" data-id=' + element.id + ' data-fullname=\'';
            table += element.name + '\'>Seleccionar</a></td>';
            table += '</tr>';    
        }else{
            table += '<tr>';
            table += '<td class="left">0 Resultados Encontrados</td>';
            table += '<td class="left"></td>';
            table += '<td class="center">';
            table += '</tr>';
        }
        
    });

    return table;
}

/**
 * CALLBACK de acciones a tomar al finalizar un evento especifico
 */
function checkAccion() {

    if($.isFunction(window.getApellidoMenor)){
        getApellidoMenor();
    }
    /*
        UATM ACTIONS
     */
    else if ($.isFunction(window.getUserPropiedad)) {
        //This input belongs to /application/views/uatm/pagos/index.php
        id = $('input[name=\'id_name\']').attr('value');


        getUserPropiedad('pagos/get_user_empresa', id); //funcion ubicada en /js/properties.js
    } else if ($.isFunction(window.estado.ajax.getPlanesPago)) {
        console.log("Adquiriendo Planes de Pago del Usuario: ");
        estado.ajax.getPlanesPago();
    }

    /*
        REF ACTIONS
     */
    


}

/**
 * Muestra y/o oculta elementos previamente cargados en la pagina
 */
function doAestheticAction(){
    //Limpiar y esconder tabla de propiedades por usuario
    if($('table.selectproperty')){
        $('table.selectproperty tbody').empty();
        $('div.properties_list').hide();
    }

    if($('table.selectenterprise')){
        $('table.selectenterprise tbody').empty();
        $('div.enterprise_list').hide();
    }

    if($('table.selectaccount')){
        $('table.selectaccount tbody').empty();
        $('div.account_status').hide();
    }
    //Esconder detalles de F1-ISAM 
    if($('div.detallefuno')){
        $('div.detallefuno').hide();
    }

    //Esconder detalles datos Batch
    if($('div.datosbach')){
        $('div.datosbach').hide();
        $('table.selectservicios tbody').empty();
    }
}