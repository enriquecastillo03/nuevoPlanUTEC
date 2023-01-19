/**
 * Description:
 * Administra JSON request de busqueda de personas FRONT-END
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.1 - 2013/06/03 
 * @since:      2013/06/03
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/06/04
 * + getDocumento - Peticion AJAX a get_documento[BACK-END], carga modal con resultados
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/06/03
 * + getPersona - Peticion AJAX a get_persona[BACK-END], administra listado de resultados 
 * + doTable - Construye la tabla completa de resultados obtenidos para busqueda de personas
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 */

/**
 * Envia parametros de busqueda y lista de resultados obtenidos via AJAX
 * @param  {JSON}   value   Cabecera enviada
 * @param  {String} URL     URL de la funcion get_persona[BACK-END]
 */
function getPersona(value, url) {
    
    $.ajax({
        url: json_url,
        dataType: 'json',
        data: value,
        type: 'POST',
        success: function (data) {
            if (data.response === true) {

                rows = data.message; //Almacenar respues de AJAX - get_persona[BACK-END]
                table = doTable(rows); //Contruir tabla
                $('#indice').html(table); //Mostrar tabla en FRONT-END
            }
        }
    });
}

/**
 * Construye la tabla completa de resultados obtenidos para busqueda de personas
 * @param  {JSON}   rows    Total filas encontradas en BD
 * @return {String}         String con tabla de resultados en formato HTML
 */
function doTable(rows) {

    $('table.personresult tbody').empty(); //Limpiar antiguos resultados si existen

    table = ''; //Base table
    
    if( $('div[role="totalholder"]') ){
        $('span.subtotal').html('$00.00');
        $('span.interes').html('$00.00');
        $('span.multa').html('$00.00');
        $('span.total').html('$00.00');
    }
    
    
    //Contruir tabla
    $(rows).each(function (index, element) {
        if(element.id !== true){
            table += '<tr>';
            table += '<td class="left">' + element.name + '</td>';
            table += '<td class="left">' + element.address + '</td>';
            table += '<td class="center" >';
            table += '<a class="button small mint rounded show_documents" ';
            table += 'href="#person_documents" data-id=' + element.id;
            table += '><span class="icon en">M</span><strong>Ver</strong></a>';
            table += '</td>';
            table += '</tr>';    
        }else{
            table += '<tr>';
            table += '<td class="left"> 0 </td>';
            table += '<td class="left">Registros Encontrados</td>';
            table += '<td class="center" >';
            table += '</td>';
            table += '</tr>';
        }
        
    });

    return table;
}

/**
 * Peticion AJAX a get_documento[BACK-END], construye y carga modal con resultados
 * @param  {integer} id     User ID
 */
function getDocumento(id) {
    $.ajax({
        url: json_url,
        dataType: 'json',
        data: {
            id: id
        },
        type: 'POST',
        success: function (data) {
            if (data.response === true) {

                rows = data.message; //Almacenar respues de AJAX - get_documento[BACK-END]
                doc = $('table.documents tbody'); //Selector
                doc.empty(); //Limpiar resultados viejos si existen
                table = ''; //Tabla Base
                $(rows).each(function (index, element) {
                    if(element.id !== false){
                        table += '<tr>';
                        table += '<td class="left">' + element.doc_type + '</td>';
                        table += '<td class="left">' + element.doc_number + '</td>';
                        table += '</tr>';
                    }else{
                        table += '<tr>';
                        table += '<td class="left"> No Posee Documentos</td>';
                        table += '<td class="left"></td>';
                        table += '</tr>';
                    }
                    
                });

                doc.html(table); //Mostrar tabla en FRONT-END

            }
        }
    });

}

