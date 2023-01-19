/**
 * Description:
 * Administra JSON request para busqueda de inmuebles y construye respuesta en html
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.1 - 2013/06/13 
 * @since:      2013/06/13
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/06/13
 * + getUserPropiedad - Obtiene propiedades por ID persona
 * + doPropertiesTable - Contruye la tabla de resultados para getUserPropiedad
 * por Alan Alvarenga
 * ------------------------------------------------------------------------------------
 */

/**
 * Obtiene propiedades por ID persona
 * Funcion cacheada de getUserPropiedad con el proposito de acceder de manera global desde el archivo
 * assign.js PATH = {js/busqueda/assign.js} esta funcion es disparada por la funcion checkAccion de assign.js
 * 
 * @param  {String}     url     Url de la funcion get_user_propiedad [BACK-END]
 * @param  {Integer}    value   ID persona
 *
 */
 
function getUserPropiedad(url, value) {

    $.ajax({
        url: url,
        dataType: 'json',
        data: {
            id: value
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {
                rows = data.message;
                table = doPropertiesTable( rows );
                $('table.selectproperty tbody').html(table);
                $('div.properties_list').slideDown('3000');
            }
        }
    });
};

/**
 * Contruye la tabla de resultados para fnGetUserProperties
 * @param  {JSON} rows      All the information to build the table
 * @return {HTML}           Table in HTML format
 */
function doPropertiesTable(rows) {
    //alert(rows)
    $('div.account_status').hide(); //Esconder el div Estado de Cuenta
    $('table.selectproperty tbody').empty();//Clean old results

    table = '';

    $(rows).each(function (index, element) {
        if(element.id !== false){
                
            table += '<tr>';
            table += '<td class="left">' + element.emp_nombre + '</td>';
            table += '<td class="left">' + element.emp_direccion + '</td>';
            table += '<td class="left">' + element.emp_giros + '</td>';
            table += '<td class="left">' + element.emp_actividad + '</td>';
 
            table += '<td class="center tbuttons" ><a class="tbutton" href="#display_account" data-inm-id=' + element.id + '>Ver</a></td>';
            table += '</tr>';
        }else{
            table += '<tr>';
            table += '<td class="left">0 Registros encontrados</td>';
            table += '<td class="left"></td>';
            table += '<td class="left"></td>';
            table += '<td class="left"></td>';

            table += '<td class="center tbuttons" ></td>';
            table += '</tr>';
        }
        

    });

    return table;
}