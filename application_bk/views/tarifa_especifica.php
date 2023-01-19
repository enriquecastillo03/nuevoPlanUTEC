<div id="modal_window_4" class="modal white"><div class="content_modal"><h4>Resultados de la busqueda</h4>
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/datatable.css"/>
	<div class="box">
				<div class="head">
				
						<?php $srv_nombre=$this->db->query("select SRV_NOMBRE from srv_servicio where SRV_ID=".$valor)->row_array();
						?>
						<h4>Tarifas relacionadas al servicio:<?php echo $srv_nombre['SRV_NOMBRE'];?></h4>
						
					<!--<a onclick="agregar_tarifa();" href="">Agregar tarifa</a>
				-->
				</div>
				<div id="form_nueva_tarifa" style="display:none;"><form id="awesome_form" action="">
					<table>
						<tr>
                 <td>Precio<br><input type="text" name="precio" id="precio_tarifa"></td>
               
                 <td>
                  Desde<br>
                 <input type="text" name="desde" id="datepicker">
                 </td>
             
            <td>Hasta<br>
                 <input type="text" name="hasta" id="datepicker2">
                 </td>
          
             	<td>
             		Descripcion<br>
                 <input type="text" name="descripcion" id="nombre_tarifa">
                 <input type="hidden" name="valor" value="<?php echo $valor;?>">
				</td>
			</tr>
			<tr>
				<td ><input type="submit" value="Crear tarifa"></td>

			</tr>
			<tr><td colspan="4"><div class="results" style="color:red; display:none;"></div></td></tr>
		</table>
				</form></div>
				<div class="content full">

	<table id="table" class="data_table">
						<thead>
							<tr>
								<th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title"> Precio</span>
									</span>
								</th>
								<th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Desde 1</span>
									</span>
								</th>
								<th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Hasta 1</span>
									</span>
								</th>
								<th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Descripcion 1</span>
									</span>
								</th>

							</tr>
						</thead>
					<tbody>

	
			     <?php //echo $valor;
			       $datos_tarifas= $this->db->query("select * from trf_tarifa where TRF_ID_SRV=".$valor)->result_array();

			      //ver($datos_tarifas);
			       foreach($datos_tarifas as $dat_f):
			     ?>
			 <tr>
								<td class="center"><?php echo $dat_f['trf_precio']; ?></td>
								<td class="center"><?php echo $dat_f['trf_desde']; ?></td>
								<td class="center"><?php echo $dat_f['trf_hasta']; ?></td>
								<td class="center"><strong><?php echo $dat_f['trf_descripcion']; ?></strong></td>
							</tr>


                  <?php endforeach; ?>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>

/*
$(document).ready(function(){
         
           jsDatePicker();
        });
*/
/*function agregar_tarifa(){
	event.preventDefault();
$("#form_nueva_tarifa").fadeIn(300);
}

$('#awesome_form').submit(function() {
  event.preventDefault();
 var base="<?php echo base_url();?>";
 var controlador= "uatm/opcional/submit_tarifa_especifica";
  $.ajax({
  type: 'POST',
  url: base+controlador,
  data: $('#awesome_form').serialize(),
  success: function(data){
if(data==1){
$.fancybox("Tarifa agregada al servicio.");	
}else{

$(".results").html(data);
$(".results").fadeIn(300);	
}

    }
});
  //return false;
});
*/

</script>