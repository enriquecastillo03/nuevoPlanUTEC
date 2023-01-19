$(function(){
	
	var call_fancybox = function(){
		$('.image-thumbnail').fancybox({
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'speedIn'		:	600, 
			'speedOut'		:	200, 
			'overlayShow'	:	false
		});		
	};
	
	call_fancybox();
	
	$('.delete-row').live('click', function(){
		var delete_url = $(this).attr('href');
		
		if( confirm( message_alert_delete ) )
		{
			$.ajax({
				url: delete_url,
				dataType: 'json',
				success: function(data)
				{					
					if(data.success)
					{
						$.ajax({
							url: ajax_list_info_url,
							dataType: 'json',
							success: function(data)
							{
							$('#ajax_list').html(data);
							}
						});
						
						
						if ($('#report-success').is(":empty")) {
							$('#report-success').html( data.success_message ).slideDown('slow');
						} else {
							$('#report-success').html( data.success_message ).fadeOut('fast').fadeIn('slow').fadeOut('fast').fadeIn('slow');
						}
						$('#report-error').html('').slideUp('fast');
					}
					else
					{
						$('#report-error').html( data.error_message ).slideUp('fast').slideDown('slow');						
						$('#report-success').html('').slideUp('fast');						
						
					}
				}
			});
		}
		
		return false;
	});
	
	$('.export-anchor').click(function(){
		var export_url = $(this).attr('data-url');
		
		var form_input_html = '';
		$.each($('#filtering_form').serializeArray(), function(i, field) {
		    form_input_html = form_input_html + '<input type="hidden" name="'+field.name+'" value="'+field.value+'">';
		});
		
		var form_on_demand = $("<form/>").attr("id","export_form").attr("method","post").attr("target","_blank")
								.attr("action",export_url).html(form_input_html);
		
		$('#hidden-operations').html(form_on_demand);
		
		$('#export_form').submit();
	});
	
	$('.print-anchor').click(function(){
		var print_url = $(this).attr('data-url');
		
		var form_input_html = '';
		$.each($('#filtering_form').serializeArray(), function(i, field) {
		    form_input_html = form_input_html + '<input type="hidden" name="'+field.name+'" value="'+field.value+'">';
		});
		
		var form_on_demand = $("<form/>").attr("id","print_form").attr("method","post").attr("action",print_url).html(form_input_html);
		
		$('#hidden-operations').html(form_on_demand);
		
		var _this_button = $(this);
		
		$('#print_form').ajaxSubmit({
			beforeSend: function(){
				_this_button.find('.fbutton').addClass('loading');
				_this_button.find('.fbutton>div').css('opacity','0.4');
			},
			complete: function(){
				_this_button.find('.fbutton').removeClass('loading');
				_this_button.find('.fbutton>div').css('opacity','1');
			},
			success: function(html_data){
				$("<div/>").html(html_data).printElement();
			}
		});
	});	
});