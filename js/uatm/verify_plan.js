var verificar = {
    fn: {
        deshabilitarVistaPrevia: function (activo) {
            //inicializacion de variables
            var vistaPrevia = $("input[type='submit']"),
                numeroMeses = $("input[name='nmeses']"),
                labelMeses = $("label[for='nmeses']");

            //Verificar estado de plan
            if (activo === 1) {
                vistaPrevia.hide();
                numeroMeses.hide();
                labelMeses.hide();
                verificar.fn.mostrarAlerta();
            } else {
                vistaPrevia.show();
                numeroMeses.show();
                labelMeses.show();
            }
        },
        mostrarAlerta: function () {
           create("note_warning", {
                title: 'Advertencia',
                text: 'Ya existe un plan de pago activo para esta cuenta'
            }, {
                expires: false
            });
        }
    },
    ajax: {
        getCuentaId: function (value) {
            //Ubicacion de la funcion obtener id de cuenta corriente por id de inmueble
            var url = baseUrl('uatm/pagos/get_cuenta_id');

            $.ajax({
                url: url,
                dataType: 'json',
                data: value,
                type: 'GET',
                success: function (data) {
                    //Establecer el id de cuenta por id de inmueble
                    var id = data.message[0].cnt_numero,
                        cuenta = {
                            id: id
                        };
                    //Verificar, posee algun plan pago pendiente?
                    verificar.ajax.getEstadoPlanPago(cuenta);
                }
            });
        },
        getEstadoPlanPago: function (value) {
            var url = baseUrl('uatm/pagos/check_plan');
            $.ajax({
                url: url,
                dataType: 'json',
                data: value,
                type: 'GET',
                success: function (data) {
                    if (data.response === true) {
                        var activo = data.activo;
                        verificar.fn.deshabilitarVistaPrevia(activo);
                    }
                }
            });
        }
    }
};