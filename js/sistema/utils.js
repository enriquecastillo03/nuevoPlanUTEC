/**
 * Descripcion: Utilidades Basicas de javaScript implementada en la plantilla azul, con el 
 *              objetivo de agilizar el desarrollo de las aplicaciones web.
 * @author:     Alan Alvarenga.
 * @version:    0.3 2013-07-19
 * @since:      2013-07-19
 * @package:    Grupo Satelite Blue (plantilla azul)
 * @type {Object}
 * =================================================================================================
 * Nomenclatura:
 * + AGREGADO
 * - ELIMINADO
 * * ACTUALIZADO
 * -------------------------------------------------------------------------------------------------
 * Bitacora:
 * 2013-07-19
 * + baseUrl    -   genera la URL base de el proyecto.
 * + jsSelect   -   convierte todos las etiquetas de tipo select a select2 
 * + jsDatePicker   - convierte todos los input de tipo text que posean en el name la palabra fecha
 * por Alan Alvarenga.
 * -------------------------------------------------------------------------------------------------
 */
window.gSateliteBlue = {
    baseUrl: function (path) {
        //Set default value, si no es definido
        path = typeof path !== 'undefined' ? path : '';

        /**
         *  PolyFill para obtener Origen de la Url
         * ------------------------------------------------------------------------
         *  window.location.origin existe desde:
         *  Firefox > 21
         *  Chrome > 27
         *  Safari > 6
         *  Internet Explorer > 10
         * ------------------------------------------------------------------------
         */

        //Si origen no existe establecerlo
        if (!window.location.origin) {
            window.location.origin = window.location.protocol + '//' + window.location.host;
        }

        //Origen de la URL
        var host = window.location.origin;

        //Directorio Base Completo
        var pathname = window.location.pathname;

        //Numero de Plecas
        var slash = 0;

        //Directorio del proyecto
        var base = '';

        for (var i = 0; i < pathname.length; i++) {

            if (pathname[i] === '/') {
                slash++;
            }

            //Agregar a directorio todos los caracteres que esten dentro de la primer pleca
            if (slash < 2) {
                base += pathname[i];
            }
        }

        //Consolidar Base URL
        var basePath = host + base;

        //Magic ;)
        return basePath + '/' + path;
    },
    jsSelect: function () {
        //Obtiene todos las etiquetas de tipo select.
        var $select = $("select");
        //A traves de la propiedad prototype, adjudica a cada select la funcion select2
        return $select.select2();
    },
    jsDatePicker: function () {
        $("input[name*='fecha']").datepicker({
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            nextText: "Siguiente",
            prevText: "Anterior"
        }).click(function() {
            $('button.ui-datepicker-current')
            .removeClass('ui-priority-secondary')
            .addClass('ui-priority-primary');
        });
        $('button.ui-datepicker-current').live('click', function() {
            $.datepicker._curInst.input.datepicker('setDate', new Date()).datepicker('hide').blur();
        });
    },
    jsSelectFilter: function (options) {
        var url = gSateliteBlue.baseUrl('utils/ajax/call/get_dropdown_filter');
        $.ajax({
            url: url,
            dataType: 'json',
            data: options,
            type: 'GET',
            success: function (data) {

                var rows = data.message;
                var $selector = $('[name="' + options.insert + '"]');

                gSateliteBlue.fn.doList($selector, rows);
            }
        });
    },
    fn: {
        doList: function ($selector, rows) {
            //Limpiar opciones antiguas
            $selector.empty();

            //iniciarlizar listado de opciones
            var optionList = '';

            //Generar listado de opciones
            $.each(rows, function (index, element) {
                optionList += '<option value="' + element.id + '">' + element.value + '</option>';
            });

            return $selector.append(optionList);
        }
    }
};