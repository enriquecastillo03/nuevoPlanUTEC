jQuery(document).ready(function($) {

$('#verificar_matrimonio').click(function()
{
	contra = $('#id_contrayente1').val();
    $.ajax({
			 type: 'POST',
		     url: gSateliteBlue.baseUrl('ref/partidas/obtener_matrimonios'),
			 data: {contra: contra},
		      success: function(data){
			   $.fancybox(data)
			  }
			});
});


$( ".choice_matrimonio" ).live({
  click: function(e) {
  	e.preventDefault();
  	mat_id= $(this).attr('matri');
  	$('#matrimonio_id').val(mat_id);
    $('#campos_basicos').fadeIn(300);
    
    $.fancybox.close();

  }

});
});
