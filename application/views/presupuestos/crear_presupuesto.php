<?php
if(isset($presupuesto_guardado)){
?>
<script language="javascript">
$(document).ready(function() {

      create("note_success", {title:'Presupuesto guardado',text:'Gracias, puedes consultarlo en el menu de la izquierda.'},{ expires: 5000});

});
</script>
<?php } ?>

<center>
	<h3>Creacion de presupuestos</h3>

<form action="<?php echo base_url(); ?>presupuestos/load_pre/exportar_excel" method="post" target="_blank" id="FormularioExportacion">
<p class="botonExcel" >Clic aca para Descargar plantilla </p>
<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
</form>
<?php
$usuarios= $this->db->query("select * from users")->result_array();


?>

</table>

<h4>Escoja un excel con el formato entregado por el Ing. Callejas.</h4>

   <img src="<?php echo base_url().'media/imagenes/index/logo_utec.jpg'?>" style=" display: block;margin-left: auto;margin-right: auto">
      <br><br>
       <?=form_open_multipart(base_url().'presupuestos/load_pre/upload_file')?>
        <?=form_upload('file')?>
        <br>
        <?=form_submit('submit', 'Upload')?>
        <?=form_close()?>

</center>


<script language="javascript">
$(document).ready(function() {
     $(".botonExcel").click(function(event) {
     $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
     $("#FormularioExportacion").submit();
});
});
</script>