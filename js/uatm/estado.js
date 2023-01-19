/**
 * Variable Global con funciones requeridas para el 
 * funcionamiento de estado de plan de pagos
 * @type {Object}
 */
var estado = {
    /**
     * Namespace: funciones de estado de planes de pago
     * @type {Object}
     */
    fn: {
        /**
         * Crear tabla de planes de pago
         * @param  {listado} listado lista de planes de pagos
         * @return jQuery Tabla de planes de pago
         */
        setTablePlanesPago: function (listado) {
            var tabla = "",
                estado = "";
            //Recorrer listado
            $.each(listado, function (index, plan) {
                //Verificar estado del plan
                if (plan.estado === "1") {
                    estado = "Activo";
                } else if (plan.estado === "0") {
                    estado = "Completado";
                } else {
                    estado = "Incompleto";
                }
                //Construir tabla 
                tabla += '<tr data-ln="' + index + '">';
                tabla += '<td class="left">' + plan.numero + '</td>';
                tabla += '<td class="left"> $' + plan.monto + ' / ' + estado + ' </td>';
                tabla += '<td class="left">' + plan.fecha_convenio + '</td>';
                tabla += '<td class="left">' + plan.dependiente + '</td>';
                tabla += '<td class="center tbuttons">';
                tabla += '<a class="tbutton editar" href="#editar" data-plan-id="' + plan.id + '">Editar</a></td>';
                tabla += '</tr>';
            });
            //Mostrar tabla en navegador
            return $("table.plan_seleccion tbody").empty().html(tabla);
        },
        /**
         * Ocutar o Mostrar tabla planes de pago
         * @return {jQuery} Show or Hide 
         */
        mostrarOcultarPlanes: function () {
            return $("div.plan_listado").toggle(400);
        },
        /**
         * Cargar Modal para editar el estado del Plan de Pago
         */
        loadModalEditar: function () {
            event.preventDefault();
            //Inicializar variables
            var $this = $(this),
                href = $this.attr("href"),
                id = $this.attr("data-plan-id"),
                value = {
                    id: id
                };
            //Actualizar valor input id plan
            $("form input[name='id_plan']").val(id);
            //Obtener detalles del plan de pagos
            estado.ajax.getDetallesPlan(value);
            //Fancybox Modal
            $.fancybox({
                'href': href,
                'speedIn': 250,
                'speedOut': 250,
                'transitionIn': 'fade',
                'transitionOut': 'fade'
            });
        },
        actualizarOpcionesEstado: function (estado) {
            var estados = ['Completado', 'Activo', 'Incompleto'],
                opciones = "";
            $.each(estados, function (index, opcion) {
                var selected = "";
                estado = estado - 1;
                if ( index === estado) {
                    opciones += '<option value="' + index + '" selected="selected">' + opcion + '</option>';
                } else {
                    opciones += '<option value="' + index + '">' + opcion + '</option>';
                }
            });

            return $("form select[name='estado']").empty().append(opciones);
        },
        actualizarEstado: function () {
            event.preventDefault();
            var value = $("form[name='editar_plan']").serialize();
            estado.ajax.setEstadoPlan(value);
        }
    },
    /**
     * Namesapce: Llamadas AJAX
     * @type {Object}
     */
    ajax: {
        /**
         * Obtiene listado de planes de pago
         */
        getPlanesPago: function () {
            //Inicializando variables locales 
            var url = baseUrl("uatm/estado/listar_planes_pago"),
                id_contribuyente = $("input[name='id_name']").attr('value'),
                value = {
                    id: id_contribuyente
                };
            //Mensajes de Consola
            console.log("ID: " + id_contribuyente);
            console.log("Desde la URL: " + url);
            //Llamada AJAX
            $.ajax({
                url: url,
                dataType: "json",
                data: value,
                type: "GET",
                success: function (data) {
                    if (data.response === true) {
                        //Esconder el div de la tabla planes
                        $("div.plan_listado").hide(200);
                        console.warn("Procesando Tabla de Respuesta");
                        var listado = data.message;
                        //Construir la tabla
                        estado.fn.setTablePlanesPago(listado);
                        //Mostrar tabla
                        estado.fn.mostrarOcultarPlanes();
                    }
                }
            });
        },
        getDetallesPlan: function (value) {
            //Ubicacion de la funcion
            var url = baseUrl('uatm/estado/detalles_plan');
            //Obtener detalles
            $.ajax({
                url: url,
                dataType: "json",
                data: value,
                type: "GET",
                success: function (data) {
                    estado.fn.actualizarOpcionesEstado(data.estado)
                }
            });
        },
        setEstadoPlan: function (value) {
            var url = baseUrl('uatm/estado/actualizar_plan');
            $.ajax({
                url: url,
                dataType: "json",
                data: value,
                type: "POST",
                success: function (data) {
                    if (data.response === true) {
                        $.fancybox.close();
                        estado.ajax.getPlanesPago();
                    }
                }
             });
        }
    },
    onReady: function () {
        estado.fn.mostrarOcultarPlanes();
        $("table").on("click", "a.editar", estado.fn.loadModalEditar);
        $("form button[type='submit']").on('click', estado.fn.actualizarEstado);
        $("select").select2();
    },
    init: function () {
        console.warn("DOM inicializado correctamente");
        estado.onReady();
    }
};

jQuery(document).ready(function ($) {
    estado.init();
});