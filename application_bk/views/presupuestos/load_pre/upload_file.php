 <style>
 table tr td{
  border: 1px solid;
  text-align: center; 
}
</style>
<script type="text/javascript">
$( document ).ready(function() {

  $("#facultad").select2(); 
  $("#carreras").select2(); 
  $("#anio").select2(); 
  $("#ciclo").select2(); 



  $("#facultad").change(function(){
    facu = $("#facultad").val(); 
    $.ajax({
      type: 'POST',
      url: '<?php echo base_url(); ?>'+"presupuestos/load_pre/obtener_carreras",
      data: {facu:facu},
      success: function(data){
       $("#carreras").html(data) 
     }
   });


  }); 



});

</script>

<form action="<?php echo base_urL().'presupuestos/load_pre/guardar_presupuesto_master'; ?>" method="POST" >
  <label>
    Nombre o descripcion del presupuesto. 
  </label>
  <input type="text" name="nombre_presupuesto">
  <label>Seleccione la facultad </label>
  <select name="facultad" id="facultad">
    <option value="0">Seleccione la facultad</option>
    <?php
    foreach ($facultad as $key ) { ?>
    <option value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]; ?></option>
    <?php }
    ?>
  </select>
  <label>Seleccione la carrera</label>
  <select id="carreras" name="carreras" >

  </select>

  <label>Seleccione ciclo</label>
  <select name="ciclo" id="ciclo">
    <?php
     $ciclos= $this->db->query("select * from ciclos")->result_array();
    foreach ($ciclos as $ciclo) { ?>

    <option value="<?php echo $ciclo['id'];  ?>"><?php echo $ciclo["anio"]."-0".$ciclo["numero"]; ?></option>}
    <?php }
    ?>

   
   
  </select> 




  <?php 
  echo '<table border=1>' . "\n";


  $i = 0; 
  foreach ($objWorksheet as $iIndice=>$objCelda)
  {
    if($i >= 7){
      echo '<tr>' . "\n";
      echo '<td>' . $objCelda["A"]. '</td>' . "\n";
      echo '<td>' . $objCelda["B"]. '</td>' . "\n";
      echo '<td>' . $objCelda["C"]. '</td>' . "\n";
      echo '<td>' . $objCelda["D"]. '</td>' . "\n";
      echo '<td>' . $objCelda["E"]. '</td>' . "\n";
      
      echo '<td>' . $objCelda["F"]. '</td>' . "\n";
     
      echo '<td>' . $objCelda["G"]. '</td>' . "\n";
      
      echo '<td>' . $objCelda["H"]. '</td>' . "\n";
     
      echo '<td>' . $objCelda["I"]. '</td>' . "\n"; 
     
      echo '<td>' . $objCelda["J"]. '</td>' . "\n";
    
      echo '<td>' . $objCelda["K"]. '</td>' . "\n";
      
      echo '<td>' . $objCelda["L"]. '</td>' . "\n";
     
      echo '<td>' . $objCelda["M"]. '</td>' . "\n";
     
      echo '<td>' . $objCelda["N"]. '</td>' . "\n";
     
      echo '<td>' . $objCelda["O"]. '</td>' . "\n";
    
      echo '<td>' . $objCelda["P"]. '</td>' . "\n";
      
     if($i >= 8){
      $arreglo_a[] = $objCelda["A"];
      $arreglo_b[] = $objCelda["B"];
      $arreglo_c[] = $objCelda["C"];
      $arreglo_d[] = $objCelda["D"];
      $arreglo_e[] = $objCelda["E"];
      $arreglo_f[] = $objCelda["F"];
      $arreglo_g[] = $objCelda["G"];
      $arreglo_h[] = $objCelda["H"];
      $arreglo_i[] = $objCelda["I"];
      $arreglo_j[] = $objCelda["J"];
      $arreglo_k[] = $objCelda["K"];
      $arreglo_l[] = $objCelda["L"];
      $arreglo_m[] = $objCelda["M"];
      $arreglo_n[] = $objCelda["N"];
      $arreglo_o[] = $objCelda["O"];
      $arreglo_p[] = $objCelda["P"];
    }

      echo '</tr>' . "\n";
    }

    $i++;
  }

  echo '</table>' . "\n";

$contenido_celda_a =  serialize($arreglo_a); //pasarla
$contenido_celda_b =  serialize($arreglo_b); //pasarla
$contenido_celda_c =  serialize($arreglo_c); //pasarla
$contenido_celda_d =  serialize($arreglo_d); //pasarla
$contenido_celda_e =  serialize($arreglo_e); //pasarla
$contenido_celda_f =  serialize($arreglo_f); //pasarla
$contenido_celda_g =  serialize($arreglo_g); //pasarla
$contenido_celda_h =  serialize($arreglo_h); //pasarla
$contenido_celda_i =  serialize($arreglo_i); //pasarla
$contenido_celda_j =  serialize($arreglo_j); //pasarla
$contenido_celda_k =  serialize($arreglo_k); //pasarla
$contenido_celda_l =  serialize($arreglo_l); //pasarla
$contenido_celda_m =  serialize($arreglo_m); //pasarla
$contenido_celda_n =  serialize($arreglo_n); //pasarla
$contenido_celda_o =  serialize($arreglo_o); //pasarla
$contenido_celda_p =  serialize($arreglo_p); //pasarla

//$variable = unserialize(stripslashes($array ); // recuperarla
?>
<input type="hidden" value='<?php echo $contenido_celda_a; ?>' name="contenido_arreglo_a" id="contenido_arreglo_a">
<input type="hidden" value='<?php echo $contenido_celda_b; ?>' name="contenido_arreglo_b" id="contenido_arreglo_b">
<input type="hidden" value='<?php echo $contenido_celda_c; ?>' name="contenido_arreglo_c" id="contenido_arreglo_c">
<input type="hidden" value='<?php echo $contenido_celda_d; ?>' name="contenido_arreglo_d" id="contenido_arreglo_d">
<input type="hidden" value='<?php echo $contenido_celda_e; ?>' name="contenido_arreglo_e" id="contenido_arreglo_e">
<input type="hidden" value='<?php echo $contenido_celda_f; ?>' name="contenido_arreglo_f" id="contenido_arreglo_f">
<input type="hidden" value='<?php echo $contenido_celda_g; ?>' name="contenido_arreglo_g" id="contenido_arreglo_g">
<input type="hidden" value='<?php echo $contenido_celda_h; ?>' name="contenido_arreglo_h" id="contenido_arreglo_h">
<input type="hidden" value='<?php echo $contenido_celda_i; ?>' name="contenido_arreglo_i" id="contenido_arreglo_i">
<input type="hidden" value='<?php echo $contenido_celda_j; ?>' name="contenido_arreglo_j" id="contenido_arreglo_j">
<input type="hidden" value='<?php echo $contenido_celda_k; ?>' name="contenido_arreglo_k" id="contenido_arreglo_k">
<input type="hidden" value='<?php echo $contenido_celda_l; ?>' name="contenido_arreglo_l" id="contenido_arreglo_l">
<input type="hidden" value='<?php echo $contenido_celda_m; ?>' name="contenido_arreglo_m" id="contenido_arreglo_m">
<input type="hidden" value='<?php echo $contenido_celda_n; ?>' name="contenido_arreglo_n" id="contenido_arreglo_n">
<input type="hidden" value='<?php echo $contenido_celda_o; ?>' name="contenido_arreglo_o" id="contenido_arreglo_o">
<input type="hidden" value='<?php echo $contenido_celda_p; ?>' name="contenido_arreglo_p" id="contenido_arreglo_p">

<input type="submit" value="Guardar">
</form>
