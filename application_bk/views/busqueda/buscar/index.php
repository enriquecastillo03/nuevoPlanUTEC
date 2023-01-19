<!-- Begin Search Container -->
<div class="box">
    <div class="head">
        <h2>
            <span class="icon en">i</span>
            <span class="title">B&uacute;squeda de Personas</span>
        </h2>
    </div>
    <div class="content">
        <form name="person_index" action="" >
            <p>
                <label for="search">T&eacute;rminos de b&uacute;squeda</label>
                <input type="text" class="tip" name="search" id="search" autofocus placeholder="Apellidos, Nombres, A&ntilde;o de Nacimiento, etc">
            </p>
            <p>
                <input type="submit" value="Buscar" class ="small button mint">
            </p>
        </form> 

    </div>
</div>
<!-- End Search Container -->

<!-- Begin Result Container -->
<div class="box">
    <div class="head">
        <h2>
            <span class="icon en">i</span>
            <span class="title">Resultado de B&uacute;squeda</span>
        </h2>
    </div>
    <div class="content">
        <table id="indice" class="static_table highlight personresult">
            <thead>
                
                
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>
<!-- End Result Container -->

<!-- Begin Documents Modal Container -->
<div id="modals">
    <div id="person_documents" class="modal white small">
        <div class="content">
            <p class="text_dark">
                <div class="box">
                    <div class="head">
                        <h4>
                            <span class="title">Documentos</span>
                        </h4>
                        
                    </div>
                </div>
                <div class="">
                    <table class="documents static_table hightlight selectmodal">
                        <thead class="clean">
                            <tr>
                                <th class="center" style="width:15%;">
                                    <span class="title">N&uacute;mero</span>
                                </th>
                                <th class="center" style="width:10%;">
                                    <span class="title">Tipo de Documento</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </p>
        </div>
    </div>
</div>
<!-- End Documents Modal Container -->    

<!-- JavaScript -->
<script src="<?php echo base_url()?>js/busqueda/person.js"></script>
<script>
	jQuery(document).ready(function($) {
		// Stuff to do as soon as the DOM is ready;
     
       $( 'form' ).on( 'submit', function( e ){
            
            e.preventDefault(); //Prevent Default Action
            value       = $(this).serialize(); //Params
            json_url    = '<?php echo base_url('busqueda/buscar/get_persona');?>'; //Url 
            getPersona( value, json_url ); //Magic ;)

       });

       $('table').on('click','a', function(){
            
            json_url    = '<?php echo site_url('busqueda/buscar/get_documento');?>'; //Url
            id = $(this).attr('data-id');  //Params
            getDocumento( id ); //Magic! ;)

            //FIX two clicks load on FancyBox
            fancyWindow = $(this).attr('href');

            //Fancybox Modal
            $.fancybox({
                'href'          : fancyWindow,
                'speedIn'       : 250,
                'speedOut'      : 250,
                'transitionIn'  :'fade',
                'transitionOut' :'fade'
            });
           
       
       });


    });
	
</script>
