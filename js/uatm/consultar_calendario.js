$(document).ready(function() {
  $("#fancybox-close").css("display","none");
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
      window.url_base= basePath + '/' + path;
  
  //
 $("#consultar_agenda").click(function(event){
  //alert(url_base);
    event.preventDefault();

          $.fancybox({
                   width  : 800,
                    height : 1000,
                    type   :'iframe',
                    href: url_base+'uatm/calendar/calendario_consulta_iframe',
                    openEffect  : 'none',
                    closeEffect : 'none',

                    prevEffect : 'none',
                    nextEffect : 'none',

                    closeBtn  : true,

                    helpers : {
                        title : {
                            type : 'inside'
                        },
                        buttons : {}
                    },

                    afterLoad : function() {
                        this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
                    }
                });
         
 });
 
 

$("#ticket").live({
  click: function(event) {
   event.preventDefault();
     caso = $(this).attr("caso");
      prefijo = $(this).attr("prefijo");
      window.caso_id= prefijo+caso; 
   $.ajax({
      type: 'POST',
      url: url_base+"uatm/futi/generar_ticket",
      success: function(data){

        $.fancybox({
                   width  : 0,
                    height : 0,
                    type   :'iframe',
                    href: url_base+"uatm/futi/generar_ticket/"+caso_id,
                    openEffect  : 'none',
                    closeEffect : 'none',

                    prevEffect : 'none',
                    nextEffect : 'none',

                    closeBtn  : false,
                    showCloseButton: false,
                    
                    helpers : {
                        title : {
                            type : 'inside'
                        },
                        buttons : {}
                    },

                    afterLoad : function() {
                        this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
                    }
                });
          }
        });
      }
    });
  
 
});

