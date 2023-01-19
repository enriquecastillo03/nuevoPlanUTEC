$(document).ready(function() {

$('input[type="text"]').keypress(function(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
    }
});	

  $(".agregar_persona_iframe").click(function(){

	  event.preventDefault(); 

	  url= $(this).attr('href');
        
	 
	      $.fancybox({
	  	                      width  : 800,
				    height : 1000,
				    type   :'iframe',
	  	            href: url,
					openEffect  : 'none',
					closeEffect : 'none',

					prevEffect : 'none',
					nextEffect : 'none',

					closeBtn  : true,

					helpers : {
						title : {
							type : 'inside'
						},
						buttons	: {}
					},

					afterLoad : function() {
						this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
					}
				});

	});		

});