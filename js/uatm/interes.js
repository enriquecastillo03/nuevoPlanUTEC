/*
    Inicializacion de variables 
*/

var interesAplicado = 0.00,
    montoAdeudado = 0.00,
    numeroMeses = 0,
    subTotal = 0,
    funoMulta = 0,
    funoInteres = 0,
    funoTotal = 0;
var interes = {
    /*
        Namespace: Funciones para calculo de interes
     */
    fn: {
        /**
         * Obtiene y calcula el monto de interes a aplicar para el monto adeudado
         */
        calcularInteres: function () {
            var tipo = {
                tipo: 0
            };
            //Verifica el tipo de interes que se debe obtener 
            if (estoy === 'inmueble') {
                //1 = inmueble
                tipo.tipo = 1;
            } else {
                //2 = empresa
                tipo.tipo = 1;
            }
            //Get porcentaje de interes a aplicar 
            interes.ajax.getPorcentajeInteres(tipo);
        },
        /**
         * Muestra el total de interes a cancelar
         */
        setInteresTotal: function (interesAplicado) {
            //Inicializar Variables de Interes Total
            var interesTotal = 0, multaTotal = 0;
            //Calcular interes total
            interesTotal = interes.fn.calcularInteresSimple(interesAplicado);
            //alert(interesTotal)
            //Mostrar en pantalla
            $("span.interes").text("$" + interesTotal);
            //Calcular multa
            multaTotal = interes.fn.calcularMulta();
            //Mostrar en pantalla
            $("span.multa").text("$" + multaTotal);
            //calcular monto total a cancelar
            interes.fn.getTotal();
        },
         /**
         * Calcular monto de interes simple
         */
        calcularInteresSimple: function (interesAplicado) {
       
            //Utilizar formula de interes Simple
            var resultado = (montoAdeudado * (numeroMeses * (numeroMeses + 1)) / 2 * (interesAplicado / 12)) / 100 * numeroMeses;
            console.log(resultado);
            //Redondear resultado a 2 decimales
            funoInteres = resultado;
            return parseFloat(resultado).toFixed(2);
        },
        /**
         * Calcula monto total de multa
         * @return number Total de multa
         */
        calcularMulta: function () {
            //Inicializacion de variables locales
            var d = new Date();

            var month = d.getMonth()+1;
            var day = d.getDate();

            var output = d.getFullYear() + '-' +
                ((''+month).length<2 ? '0' : '') + month + '-' +
                ((''+day).length<2 ? '0' : '') + day;

                var multaTotal = 0;

            $.ajax({
                      type: 'POST',
                      url: baseUrl('uatm/recuperacion_mora/check_in_range'),
                      data: {output:output},
                      async: true,
                      success: function(data){
                          if(data==1){
                           
                            factorUnoATresMeses = 0,
                            factorMasDeTresMeses = 0,
                            limiteMulta = 0;
           
                          }else{
                            
                            factorUnoATresMeses = 0.05,
                            factorMasDeTresMeses = 0.1,
                            limiteMulta = 2.86;
                          }
                      }
                    });

         
          
               
               // debugger;
            //Verificar tiempo adeudado para aplicar factor de cargo
            if (numeroMeses >= 1 && numeroMeses <= 3) {
                //De 1 - 3 Meses factor 5%
                multaTotal = montoAdeudado * factorUnoATresMeses;
                //La multa no puede ser menos a 2.86
                multaTotal = (multaTotal < limiteMulta) ? limiteMulta : multaTotal;
            } else if (numeroMeses > 3) {
                //Mayor a 3 meses 10%
                multaTotal = montoAdeudado * factorMasDeTresMeses;
            }
            funoMulta = parseFloat(multaTotal).toFixed(2);
            //Redondear a 2 decimales
            return funoMulta;
        },
        /**
         * Calcular subtotal a pagar
         * @return number subtotal a cancelar
         */
        getSubTotal: function (subCuota, accion) {

            //Verificar accion a realizar
            if (accion === "sumar") {
                subTotal = subTotal + subCuota;
            } else {
                subTotal = subTotal - subCuota;
            }
             // interes.fn.getTotal();
            //Redondear a 2 decimales
            return $("span.subtotal").text("$" + parseFloat(subTotal).toFixed(2));
          
        },
        /**
         * Calcula Total a Pagar
         * @return number Total a cancelar en caja
         */
        getTotal: function () {
            //Inicializando variables locales
            var interes = 0,
                multa = 0,
                subtotal = 0,
                total = 0;
            //Obtener montos de interes, multa y subtotal
            interes = $("span.interes").html();
            interes = parseFloat(interes.slice(1, interes.length));
            multa = $("span.multa").html();
            multa = parseFloat(multa.slice(1, multa.length));
            subtotal = $("span.subtotal").html();
            subtotal = parseFloat(subtotal.slice(1, subtotal.length));
            //Redondear a 2 decimales

            total = parseFloat(interes + multa + subtotal).toFixed(2);
            //Fix
            funoTotal = total;
            //Mostrar en pantalla
            return $("span.total").text(total);
        },
        deshabilitarPago: function (activo) {
            //Verificar si posee algun plan activo
            if (activo === 1) {
                //Deshabilitar eventos y cursor, cambiar el texto 
                $("table a.pay").css({
                    pointerEvents: 'none',
                    cursor: 'default'
                }).html('Deshabilitado');
                //Mostrar alerta de plan activo
                interes.fn.alertaDeshabilitado();
                //Deshabilitar el boton seleccionar todo
                $("a.select_all").hide();
            } else {
                //Mostrar boton seleccionar todo,luego de haber seleccionado una 
                //cuenta con plan de pago activo
                $("a.select_all").show();
            }
        },
        resetVariablesZero: function () {
            interesAplicado = 0.00;
            montoAdeudado = 0.00;
            numeroMeses = 0;
        },
        alertaDeshabilitado: function () {
            create("note_warning", {
                title: 'Advertencia',
                text: 'Este usuario posee un plan de pago activo'
            }, {
                expires: 2000
            });
        }
    },
    /*
        Namespace: Llamadas AJAX
     */
    ajax: {
        /**
         * Get porcentaje de interes a aplicar al monto adeudado
         * @param  integer tipo 1 = inmueble, 2 = empresa
         */
        getPorcentajeInteres: function (tipo) {
            //ubicacion de la funcion
            var url = baseUrl('uatm/pagos/get_porcentaje_interes_vigente');

            $.ajax({
                url: url,
                dataType: 'json',
                data: tipo,
                type: 'GET',
                success: function (data) {
                    //Verificar la respuesta del servidor
                    if (data.response === true) {
                        //reset interes a aplicar
                        interesAplicado = 0;
                        //asignar porcentaje de interes a aplicar
                       //alert(data.interes)
                        interes.fn.setInteresTotal(data.interes);
                    }
                }
            });
        },
        getCuentaId: function (value) {
            //Ubicacion de la funcion obtener id de cuenta corriente por id de inmueble
            var url = baseUrl('uatm/pagos/get_cuenta_id');

            $.ajax({
                url: url,
                dataType: 'json',
                data: value,
                type: 'GET',
                success: function (data) {
                    //Obtener id de cuenta corriente y establecer la ruta de generacion de solvencia
                    var id = data.message[0].cnt_numero,
                        solvencia = baseUrl('uatm/pagos/get_solvencia'),
                        cuenta = {
                            id: id
                        };
                    //Actualizar url de generacion
                    $("a.solvencia").prop("href", solvencia + "?cnt_id=" + id);
                    //Verificar, posee algun plan pago pendiente?
                    interes.ajax.getEstadoPlanPago(cuenta);
                }
            });
        },
        getEstadoPlanPago: function (value) {
            //Ubicacion de la funcion
            var url = baseUrl('uatm/pagos/check_plan');
            $.ajax({
                url: url,
                dataType: 'json',
                data: value,
                type: 'GET',
                success: function (data) {
                    if (data.response === true) {
                        var activo = data.activo;
                        interes.fn.deshabilitarPago(activo);
                    }
                }
            });
        }
    }
};