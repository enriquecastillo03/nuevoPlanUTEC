<link rel="stylesheet" type='text/css' href="<?=base_url()?>assets/validate/validate.css"/>
<script type="text/javascript" src="<?=base_url()?>assets/validate/jquery.validate.min.js"></script>
<script type="text/javascript">
<!--
$(document).ready(function() {
    var btn_click;
    $(".button").click(function(event){
        btn_click = this;
        $("#frm").submit();
    });
    $("#frm").validate({
        ignore: null,
        ignore: 'input[type="hidden"]',
        rules: {
            obs:{required:true}
        },
        messages:{
            obs:     "Obligario."
        },
        submitHandler: function(form){
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . $url; ?>",
                data: {
                    registro: $("#registro").val(),
                    obs: $("#obs").val(),
                    aceptado: ($(btn_click).html() == "<?php echo $aceptar; ?>")?"yes":"no",
                    ejecutado: "yes"
                }
            }).done(function(html){
                $.fancybox.close();
                if(html ==  "Ok"){
                    create("note_success", {
                        title:'Se envio notificación',
                        text:'Se ha cambiado el estado del cheque.<br />'
                        },
                        { expires: 5000}
                    );
    
                }else{
                    create("note_error", {
                        title:'No se pudo enviar Notificación',
                        text:'Por favor verifique con el administrador de Sistema.<br />' + html 
                        },
                        { expires: 5000}
                    );
                }
            });
        }
    });
});
-->
</script>
<div class="container" style="margin: 0px; width: 400px;">
	<div class="box" style="margin: 0px;">
	<div class="head">
		<h2>
		<span class="icon en">&</span>
		<span class="title"><?php echo $titulo; ?></span>
		</h2>
	</div>
	<div class="content">
    <div id="message" style="display: none;"></div>
	<form id="frm" autocomplete="off">
        <p><textarea id="obs" name="obs" cols="30" rows="30" wrap="virtual" maxlength="100"></textarea></p>
        <p><a class="button rounded tropical"><?php echo $aceptar; ?></a></p>
        <?php if($rechazar){ ?><p><a class="button rounded red"><?php echo $rechazar; ?></a></p><?php } ?>
        <input type="hidden" id="registro" name="registro" value="<?php echo $registro; ?>" />
	</form>
	</div>
	</div>
</div>