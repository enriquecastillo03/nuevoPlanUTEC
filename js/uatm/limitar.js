(function($){
    $.fn.limitar = function(options) {
      defaults = {
        limite: 200,
        id_counter: false,
        clase_alert: false
     }
   var options = $.extend(defaults,  options);
   return this.each(function() {
    var caracteres = options.limite;
    if(options.id_counter != false)
    {
        $("#"+options.id_counter).html("Te quedan <strong>"+ caracteres +"</strong> caracteres.");
    }
    $(this).keyup(function(){
        if($(this).val().length > caracteres){
        $(this).val($(this).val().substr(0, caracteres));
        }
        if(options.id_counter != false)
        {
            var quedan =  caracteres - $(this).val().length;
            $("#"+options.id_counter).html("Te quedan <strong>"+ quedan +"</strong> caracteres");
            if(quedan <= 10)
            {
                $("#"+options.id_counter).addClass(options.clase_alert);
            }
            else
            {
                $("#"+options.id_counter).removeClass(options.clase_alert);
            }
        }
    });
});
};
})(jQuery);