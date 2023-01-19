/**
 * Description:
 * Utilidades basicas para desarrollo del proyecto - [FRONT-END]
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.1 - 2013/06/18 
 * @since:      2013/06/18
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/06/18
 * + baseUrl - Obtiene base Url del sistema y retorna una url completa si recibe parametros
 * por Alan Alvarenga
 * -------------------------------------------------------------------------------------
 */

/**
 * Obtiene base Url del sistema
 * @param  {string} path 	direccion del archivo al que desea accesar
 * @return {string}      	Base URL de la aplicacion mas direccion del archivo
 */
function baseUrl (path) {

	//Set default value, si no es definido
	path = typeof path !== 'undefined' ? path : '';

	/**
	 *  PolyFill para obtener Origen de la Url
	 * ------------------------------------------------------------------------
	 * 	window.location.origin existe desde:
	 * 	Firefox > 21
	 * 	Chrome > 27
	 * 	Safari > 6
	 * 	Internet Explorer > 10
	 * ------------------------------------------------------------------------
	 */
	
	//Si origen no existe establecerlo
	if (!window.location.origin){
		window.location.origin = window.location.protocol+'//'+window.location.host;	
	}
 	
 	//Origen de la URL
 	host = window.location.origin;

 	//Directorio Base Completo
 	pathname = window.location.pathname;

 	//Numero de Plecas
 	slash = 0;
 	//Directorio del proyecto
 	base = '';

 	for (var i = 0; i < pathname.length; i++) {
 		
 		if(pathname[i] == '/'){
 			slash++;
 		}

 		//Agregar a directorio todos los caracteres que esten dentro de la primer pleca
 		if(slash < 2){
 			base += pathname[i];
 		}
 	}

 	//Consolidar Base URL
 	basePath = host + base;

 	//Magic ;)
 	return basePath + '/' + path;
}

$('#boton_print').fancybox();

function jsDatePicker() {
        $("input[name*='fecha']").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            nextText: "Siguiente",
            prevText: "Anterior"
        });

         $("input[id*='datepicker']").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            nextText: "Siguiente",
            prevText: "Anterior"
        });
    }