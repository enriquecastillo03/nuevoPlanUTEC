/**
 * Description:
 * Genera combinaciones de apellidos y direccion de docimicilio 
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.1 - 2013/06/20 
 * @since:      2013/06/20
 * @package:    Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/06/20
 * + jQuery.ready - Inicializa todas las funciones de asignacion para esta vista
 * + getApellioMenor - Captura apellidos de la madre y el padre y genera un apellido sugerido 
 * 						al mismo tiempo asigna la direccion de domicilio
 * por Alan Alvarenga
 * * -----------------------------------------------------------------------------------
 */
jQuery(document).ready(function($) {
	// Stuff to do as soon as the DOM is ready
	$('input[name="padre"]').on('keyup', function (e){
		//Si keyCode es backspace
		if(e.keyCode === 8){
			inputPadre = $('input[name="padre"]'); 
			inputPadre.val("");
			inputPadre.next().val("");
			inputPadre.next().next().val("");
			$('input[name="apellido1"]').val(arrApellidosMadre[0]);
			$('input[name="apellido2"]').val(arrApellidosMadre[1]);
		}
	} );

	$('input[name="madre"]').on('keyup', function (e){
		//Si keyCode es backspace
		if(e.keyCode === 8){
			inputMadre = $('input[name="madre"]'); 
			inputMadre.val("");
			inputMadre.next().val("");
			inputMadre.next().next().val("");
			$('input[name="apellido1"]').val("");
			$('input[name="apellido2"]').val("");
		}
	} );

});
function getApellidoMenor () {
	apellidosMadre = $('input[name="madre"]').val();
	apellidosPadre = $('input[name="padre"]').val();

	//Al introducir madre
	if(apellidosMadre !== ""){
		//extraer apellidos y nombre de la madre
		arrApellidosMadre = apellidosMadre.split(' ');
		//establecer apellidos maternos 
		$('input[name="apellido1"]').val(arrApellidosMadre[0]);
		$('input[name="apellido2"]').val(arrApellidosMadre[1]);
		$('input[name="direccion"]').val($('input[name="mask_madre_address"]').val());
	}

	//Al introducir Padre
	if(apellidosPadre !== "" && apellidosMadre !== ""){
		//extraer apellidos y nombre del padre
		arrApellidosPadre = apellidosPadre.split(' ');
		//establecer apellidos maternos y paternos
		$('input[name="apellido1"]').val(arrApellidosPadre[0]);
		$('input[name="apellido2"]').val(arrApellidosMadre[0]);
	}
		
}