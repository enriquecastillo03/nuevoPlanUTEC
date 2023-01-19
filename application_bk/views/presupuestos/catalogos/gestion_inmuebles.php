  
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/datatable.css"/>
	<style>
	.editable{
		display:none;
	}
	.th_falso{
		display:none;
	}
	</style>
			<?php if($this->session->flashdata('inmueble_update')==true):

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
								title:'Inmueble actualizado correctamente',
								text:'' }, {
								expires: false
							});
		 });
							</script>
		<?php endif;?>
	<div class="box">
	 <?php $inmuebles= $this->db->query("SELECT *, CONCAT(con_apellido1,' ', con_apellido2,' ', con_nombre1,' ',con_nombre2) AS propietario FROM con_contribuyente con
										INNER JOIN ixc_inmueblexcontribuyente ixc ON ixc.`ixc_id_con`= con.`con_id`
										INNER JOIN inm_inmueble inm ON inm.`inm_id`= ixc.`ixc_id_inm`")->result_array();

   //echo $this->db->last_query();
										?>
				<div class="head">
				Gestion de inmuebles.	
				</div>
		
				
				<div class="content full">

				<table id="table" class="data_table">
						<thead>
							<tr>
								<th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title"> Propietario</span>
									</span>
								</th>
								<th class="center" style="width: 60%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Direccion del inmueble</span>
									</span>
								</th>
								<th class="center" style="width: 60%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Descripcion del inmueble</span>
									</span>
								</th>
									<th class="center" style="width: 5%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Frente</span>
									</span>
								</th>
								<th class="center" style="width: 5%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Ancho</span>
									</span>
								</th>
                               <th class="center" style="width: 5%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Cantidad de pisos</span>
									</span>
								</th>
								   <th class="center" style="width: 25%;">
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Acciones</span>
									</span>
								</th>
								<!-- estos th solo los puse porque el js datatable busca la misma cantidad de cabeceras que de tds, y como estoy usando
							     tds ocultos revienta el js...-->
								<th class="th_falso">
								</th>
								<th class="th_falso">
								</th>
								<th class="th_falso">
								</th>

							</tr>
						</thead>
					<tbody>

	
					     <?php //echo $valor;
                            $i=1;
					       foreach($inmuebles as $dat_f):
					        
					     ?>
			 				<tr>
								<td class="center"><?php echo $dat_f['propietario']; ?></td>
								<td id="direccion<?php echo $i; ?>" class="center"><?php echo $dat_f['inm_direccion']; ?></td>
								<!--Campo editable, td contendra input falso...-->
								<td id="direccion_edit<?php echo $i; ?>" class="center editable"><input id="input_direccion<?php echo $i; ?>" type="text" value="<?php echo $dat_f['inm_direccion']; ?>"></td>
								<td id="descripcion<?php echo $i; ?>" class="center"><?php echo $dat_f['inm_descripcion']; ?></td>
								<td id="descripcion_edit<?php echo $i; ?>" class="center editable"><input id="input_descripcion<?php echo $i; ?>" type="text" value="<?php echo $dat_f['inm_descripcion']; ?>"></td>
								<td class="center"><?php echo $dat_f['inm_frente']; ?></td>
								<td class="center"><?php echo $dat_f['inm_ancho_calle']; ?></td>
								<td class="center"><?php echo $dat_f['inm_niveles']; ?></td>
								<td id="guardar<?php echo $i; ?>" class="center tbuttons ">
									<a  custom="<?php echo $i; ?>" class="tbutton" href="#">Edit</a>
								</td>
								<td  id="guardar_edit<?php echo $i; ?>" class="editable" class="center tbuttons ">
									<a  custom="<?php echo $i; ?>" class="tbutton guardar_edit" href="#">Guardar</a>
								</td>
								<input type="hidden" id="id_inmueble<?php echo $i;?>" value="<?php echo $dat_f['inm_id'];?>"  >
							</tr>


                  <?php 
                   $i++;
                  endforeach; ?>
							</tbody>
							</table>
						</div>
					</div>
					</div>

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

$(".tbutton").click(function(event){
  id_fila= $(this).attr("custom");
 // alert(id_fila);
//ocultar el td actual y mostrar el que contiene el input
direccion_edit= $("#direccion_edit"+id_fila).html();
$("#direccion"+id_fila).html(direccion_edit);
//descripcion
descripcion_edit= $("#descripcion_edit"+id_fila).html();
$("#descripcion"+id_fila).html(descripcion_edit);

//Botones
guardar_edit= $("#guardar_edit"+id_fila).html();
$("#guardar"+id_fila).html(guardar_edit);
});


$(".guardar_edit").live({
  click: function(event) {
event.preventDefault();
id_registro= $(this).attr("custom");
direccion= $("#input_direccion"+id_registro).val();
descripcion= $("#input_descripcion"+id_registro).val();
id_inmueble= $("#id_inmueble"+id_registro).val();

		$.ajax({
		  type: 'POST',
		  url: '<?php echo base_url().$modulo."/catalogos/actualizar_datos_inmueble";?>',
		  data: {direccion:direccion,descripcion:descripcion, id_inmueble:id_inmueble},
		  success: function(data){
		   window.location.href="";
		  }
		});

  }
});


</script>

