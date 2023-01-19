   <?php if($this->session->flashdata('trf_json')==true):
$datos_cuenta=$this->session->flashdata('trf_json');
//ver($datos_cuenta);

   ?>
<script type="text/javascript">
$(document).ready(function(){
          function create( template, vars, opts ){
	 	return $notifications.notify("create", template, vars, opts);
	    }

	     $(".note .close").click(function() {
		$(this).closest(".note").animate({"opacity": 0}, 300, function() {
			$(this).slideUp(150);
		});
	});
         
        
$notifications = $("#notifications").notify();

					create("note_success", {
						title:'Nueva tarifa agregada.',
						text:'' }, {
						expires: 6000
					});
 });
					</script>
<?php endif;?>
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/datatable.css"/>
	<div class="box">
				<div class="head">
Tarifas		
				</div>
						<ul class="quick_buttons">

				<li>
					<a  id="agregar_tari" onclick="agregar_tarifa();" href="#">
						<span class="icon en">j</span>
						<span class="title">Agregar tarifa</span>
					</a>
				</li>
				
			</ul>
				<div id="form_nueva_tarifa" style="display:none;"><form id="awesome_form" action="">
					<table>
				
						<tr>
                <td>Servicio<br>
                 <?php $servicios= $this->db->query("select * from srv_servicio where srv_tiempo_corte=30")->result_array();?>
                	<select style="height:46px;" id="srv_servicios" name="srv_servicios">
                       <?php foreach($servicios as $serv):?>
                		<option value="<?php echo $serv['srv_id'];?>"><?php echo $serv['srv_nombre'];?></option>
                	<?php endforeach; ?>
                	</selec>
                 <td>Precio<br><input type="text" name="precio" id="precio_tarifa"></td>
               
                 <td>
                  Desde<br>
                 <input type="text" name="desde" id="datepicker">
                 </td>
             <!--
            <td>Hasta<br>
                 <input type="text" name="hasta" id="datepicker2">
                 </td>
          -->
             	<td>
             		Descripcion<br>
                 <input type="text" name="descripcion" id="nombre_tarifa">
                
				</td>
				   	<td>
             		Uso<br>
                <?php $usos = $this->db->query('select * from uso_uso')->result_array(); ?>
                <select id='uso_us' name='uso_us' style='height: 46px;'>
                	<?php foreach ($usos  as $key_uso) { ?>
                		<option value='<?php echo $key_uso["uso_id"]; ?>'><?php  echo $key_uso['uso_descripcion']; ?></option>
                	<?php }?>
                </select>
               
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
										<span class="title"> Nombre de servicio</span>
									</span>
								</th>
								<th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Desde</span>
									</span>
								</th>
								<th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Hasta</span>
									</span>
								</th>
                                  <th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Uso</span>
									</span>
								</th>

									<th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Estado</span>
									</span>
								</th>
								<th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Precio de tarifa</span>
									</span>
								</th>

							</tr>
						</thead>
					<tbody>

	
			     <?php //echo $valor;
			       $datos_tarifas= $this->db->query("SELECT * FROM srv_servicio srv
INNER JOIN trf_tarifa trf ON trf.`trf_id_srv`= srv.`srv_id`
INNER JOIN uso_uso uso ON uso.`uso_id` = trf.trf_id_uso
WHERE srv.`srv_tiempo_corte`= 30
					")->result_array();

			      //ver($datos_tarifas);
			       foreach($datos_tarifas as $dat_f):
			     ?>
			 <tr>
								<td class="center"><?php echo $dat_f['srv_nombre']; ?></td>
								<td class="center"><?php echo $dat_f['trf_desde']; ?></td>
								<td class="center"><?php echo $dat_f['trf_hasta']; ?></td>
								<td class="center"><?php echo $dat_f['uso_descripcion']; ?></td>
								<?php if($dat_f['trf_estado']==0):?>
								<td class="center">Inactiva</td>
								<?php endif;?>
									<?php if($dat_f['trf_estado']==1):?>
								<td class="center">Activa</td>
								<?php endif;?>
								<td class="center"><strong><?php echo $dat_f['trf_precio']; ?></strong></td>
							</tr>


                  <?php endforeach; ?>
					</tbody>
					</table>
				</div>
			</div>
		</div>

<script>


$(document).ready(function(){
         
          jsDatePicker();            
        });

$("#agregar_tari").click(function(event){
event.preventDefault();
});
function agregar_tarifa(){

$("#form_nueva_tarifa").fadeIn(300);
}

$('#awesome_form').submit(function(event) {
  event.preventDefault();
 var base="<?php echo base_url();?>";
 var controlador= "uatm/opcional/submit_tarifa_especifica";
  $.ajax({
  type: 'POST',
  dataType:"json",
  url: base+controlador,
  data: $('#awesome_form').serialize(),
  success: function(data){
if(data.valor==1){
window.location.href="";
}else{

$(".results").html(data.key);
$(".results").fadeIn(300);	
}

    }
});
  //return false;
});

</script>

<script type="text/javascript">
$(document).ready(function() {

	//DataTables
	
	//Table 1
	$('#table').dataTable();
	
	//Table 2
	$('#table_2').dataTable({
		"sPaginationType": "full_numbers"
	});

	//Table 3
	$.fn.dataTableExt.afnSortData['dom-checkbox'] = function  ( oSettings, iColumn )
	{
		var aData = [];
		$( 'td:eq('+iColumn+') input', oSettings.oApi._fnGetTrNodes(oSettings) ).each( function () {
			aData.push( this.checked==true ? "1" : "0" );
		} );
	return aData;
	};
	$('#table_3').dataTable( {
		"sPaginationType": "full_numbers",
		"aoColumns": [
			null,
			null,
			null,
			{ "sSortDataType": "dom-checkbox" }
		]
	} );



});
</script>