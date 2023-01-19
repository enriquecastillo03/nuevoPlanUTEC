<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/style.css"/>
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/colors.css"/>
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/jquery-ui.1.10.3.css" />
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/modals.css"/>
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/datatable.css"/>

<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/fullcalendar.css"/>
<link rel="stylesheet" type='text/css' href="<?php echo base_url();?>stylesheets/notifications.css"/>

<!--Scripts-->
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.library.1.7.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery-ui.1.10.3.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.ui.library.custom.1.8.21.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/application.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.datatables.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.notify.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.fullcalendar.js"></script>



<?php $remediciones=$this->db->query("SELECT * FROM rem_remedicion AS rem
INNER JOIN fut_futi fut ON fut.`fut_id`= rem.`rem_id_fut`
INNER JOIN inm_inmueble inm ON inm.`inm_id`= fut.`FUT_ID_INM`
INNER JOIN tis_tipo_solicitud tis ON tis.`tis_id`= fut.`fut_id_tis`
where tis.tis_id=4
")->result_array();?>

<?php $desmembracion=$this->db->query("SELECT * FROM des_desmembracion AS des
INNER JOIN fut_futi fut ON fut.`fut_id`= des.`fut_id`
INNER JOIN inm_inmueble inm ON inm.`inm_id`= fut.`FUT_ID_INM`
INNER JOIN tis_tipo_solicitud tis ON tis.`tis_id`= fut.`fut_id_tis`
where tis.tis_id=5
")->result_array();

$recalificacion=$this->db->query("SELECT * FROM rec_recalificacion AS rec
INNER JOIN fut_futi fut ON fut.`fut_id`= rec.`rec_id_fut`
INNER JOIN inm_inmueble inm ON inm.`inm_id`= fut.`FUT_ID_INM`
INNER JOIN tis_tipo_solicitud tis ON tis.`tis_id`= fut.`fut_id_tis`
where tis.tis_id=14
")->result_array();


//Buscar id_tis= 6 que corresponde a los cierres de centa de inmueble, en este caso se alimntara del campo fut_fecha solo es para este caso y
//para el cierre del negocio
$sql="SELECT * FROM fut_futi AS futi
INNER JOIN inm_inmueble inm ON inm.`inm_id`= futi.`fut_id_inm`
INNER JOIN tis_tipo_solicitud tis ON tis.`tis_id`= futi.`fut_id_tis`
WHERE  futi.`fut_id_tis`= 6";
$cierre_cuenta_inmueble= $this->db->query($sql)->result_array();

$sql_cierre_negocio= "SELECT * FROM fut_fute AS fute
INNER JOIN emp_empresa emp ON fute.`fut_id_emp`= emp.emp_id
INNER JOIN tis_tipo_solicitud tis ON tis.`tis_id`= fute.`fut_id_tis`
INNER JOIN nem_nombre_empresa nem ON nem.`nem_id_emp`= emp.`emp_id`
INNER JOIN dir_direccion dir ON dir.`dir_id_emp`= emp.`emp_id`
WHERE  fute.`fut_id_tis`= 12
AND nem.`nem_tipo_id`=1
AND nem.`nem_estado`=1
AND dir.`dir_estado`=1";
$cierre_negocio= $this->db->query($sql_cierre_negocio)->result_array();

?>


<script type="text/javascript">
$(document).ready(function() {

	//FullCalendar
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();	
	$('#calendar').fullCalendar({
	eventClick: function(event, element) {
     //alert(event.start+" "+event.end);
		//alert(event.info);
		  	var id=event.llave;
        var id2=event.id;
        var tis= event.tis;

        	var base="<?php echo base_url().$modulo.'/calendar/';?>";
        	if(tis==6 || tis==12){
             var controller= "info_evento_fute";
             alert
           }else{

             var controller= "info_evento";
           }
         
        	var direccion = base+controller;

		   $.ajax({
  type: 'POST',
  url: direccion,
  data: {id:id, id2:id2, tis:tis},
  success: function(data){
    $.fancybox("<div>"+data+"</div>");
  }
});

    },	
		header: {
			left: '',
			center: 'prev,title,next',
			right: 'today,month,basicWeek,basicDay'
		},
	

		events: [
			<?php 
           foreach($remediciones as $rem){
            echo "{";
           	?>
            <?php if($rem['rem_estado']==1):?>
            llave:"<?php echo $rem['fut_id'];?>",
            id:"<?php echo $rem['rem_id'];?>",
            tis:0,
        title: "<?php echo $rem['inm_direccion'].'-'.$rem['tis_nombre'];?>",
        start: "<?php echo $rem['rem_fecha'];?>",
        end: "<?php echo $rem['rem_fecha'];?>",
        className: 'green',
        info:"<?php echo $rem['rem_dir_escucha'];?>",

         editable: false,
        <?php endif; ?>
        <?php if($rem['rem_estado']==0):?>
         llave:"<?php echo $rem['fut_id'];?>",
            id:"<?php echo $rem['rem_id'];?>",
        title: "<?php echo $rem['inm_direccion'].'-'.$rem['tis_nombre'];?>",
        start: "<?php echo $rem['rem_fecha'];?>",
        end: "<?php echo $rem['rem_fecha'];?>",
        className: 'red',
        info:"<?php echo $rem['rem_dir_escucha'];?>",

        editable: true,
        <?php endif; ?>
        

          <?php 
echo "},";
      }?>
            
                  <?php //ok aqui va la cierre
           foreach($cierre_cuenta_inmueble as $cierre_cuenta){
            echo "{";
            ?>
            <?php if($cierre_cuenta['fut_id_ess']==2):?>
            llave:"<?php echo $cierre_cuenta['fut_id'];?>",
            id:"<?php echo $cierre_cuenta['fut_id'];?>",
            tis:6,
        title: "<?php echo $cierre_cuenta['inm_direccion'].'-'.$cierre_cuenta['tis_nombre'];?>",
        start: "<?php echo $cierre_cuenta['fut_fecha'];?>",
        end: "<?php echo $cierre_cuenta['fut_fecha'];?>",
        className: 'green',
        info:"<?php echo $cierre_cuenta['fut_observacion'];?>",

         editable: false,
        <?php endif; ?>
        <?php if($cierre_cuenta['fut_id_ess']==1):?>
           llave:"<?php echo $cierre_cuenta['fut_id'];?>",
            id:"<?php echo $cierre_cuenta['fut_id'];?>",
            tis:6,
        title: "<?php echo $cierre_cuenta['inm_direccion'].'-'.$cierre_cuenta['tis_nombre'];?>",
        start: "<?php echo $cierre_cuenta['fut_fecha'];?>",
        end: "<?php echo $cierre_cuenta['fut_fecha'];?>",
        className: 'red',
        info:"<?php echo $cierre_cuenta['fut_observacion'];?>",

        editable: true,
        <?php endif; ?>
        

          <?php 
echo "},";
      }?>


      <?php //ok aqui va la cierre
           foreach($cierre_negocio as $cierre_negocio){
            echo "{";
            ?>
            <?php if($cierre_negocio['fut_id_ess']==2):?>
            llave:"<?php echo $cierre_negocio['fut_id'];?>",
            id:"<?php echo $cierre_negocio['fut_id'];?>",
            tis:12,
        title: "<?php echo $cierre_negocio['nem_nombre'].'-'.$cierre_negocio['tis_nombre'];?>",
        start: "<?php echo $cierre_negocio['fut_fecha'];?>",
        end: "<?php echo $cierre_negocio['fut_fecha'];?>",
        className: 'green',
        info:"<?php echo $cierre_negocio['fut_observacion'];?>",

         editable: false,
        <?php endif; ?>
        <?php if($cierre_negocio['fut_id_ess']==1):?>
           llave:"<?php echo $cierre_negocio['fut_id'];?>",
            id:"<?php echo $cierre_negocio['fut_id'];?>",
              tis:12,
        title: "<?php echo $cierre_negocio['nem_nombre'].'-'.$cierre_negocio['tis_nombre'];?>",
        start: "<?php echo $cierre_negocio['fut_fecha'];?>",
        end: "<?php echo $cierre_negocio['fut_fecha'];?>",
        className: 'red',
        info:"<?php echo $cierre_negocio['dir_ubicacion'];?>",

        editable: true,
        <?php endif; ?>
        

          <?php 
echo "},";
      }?>


<?php
                 foreach($recalificacion as $rec){
            echo "{";
            ?>
            <?php if($rec['rec_estado']==1):?>
            llave:"<?php echo $rem['fut_id'];?>",
            id:"<?php echo $rec['rec_id'];?>",
              tis:0,
        title: "<?php echo $rec['inm_direccion'].'-'.$rec['tis_nombre'];?>",
        start: "<?php echo $rec['rec_fecha'];?>",
        end: "<?php echo $rec['rec_fecha'];?>",
        className: 'green',
        info:"<?php echo $rec['rec_dir_escucha'];?>",

         editable: false,
        <?php endif; ?>
        <?php if($rec['rec_estado']==0):?>
         llave:"<?php echo $rec['fut_id'];?>",
            id:"<?php echo $rec['rec_id'];?>",
              tis:0,
        title: "<?php echo $rec['inm_direccion'].'-'.$rec['tis_nombre'];?>",
        start: "<?php echo $rec['rec_fecha'];?>",
        end: "<?php echo $rec['rec_fecha'];?>",
        className: 'red',
        info:"<?php echo $rec['rec_dir_escucha'];?>",

        editable: true,
        <?php endif; ?>
        

          <?php 
echo "},";
      }?>

<?php 
           foreach($desmembracion as $des){
            echo "{";
            ?>
            <?php if($des['des_estado']==1):?>
           llave:"<?php echo $des['fut_id'];?>",
            id:"<?php echo $des['des_id'];?>",
             tis:0,
        title: "<?php echo $des['inm_direccion'].'-'.$des['tis_nombre'];?>",
        start: "<?php echo $des['des_fecha'];?>",
        end: "<?php echo $des['des_fecha'];?>",
        className: 'green',
        info:"<?php echo $des['fut_observacion'];?>",

         editable: false,
        <?php endif; ?>
        <?php if($des['des_estado']==0):?>
       llave:"<?php echo $des['fut_id'];?>",
             id:"<?php echo $des['des_id'];?>",
              tis:0,
        title: "<?php echo $des['inm_direccion'].'-'.$des['tis_nombre'];?>",
        start: "<?php echo $des['des_fecha'];?>",
        end: "<?php echo $des['des_fecha'];?>",
        className: 'red',
        info:"<?php echo $des['fut_observacion'];?>",

        editable: true,
        <?php endif; ?>
        

          <?php 
echo "},";
      }?>
			
		],

    
    eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {

       /* alert(
            event.title + " Se movio el evento " +
            dayDelta + " days and " +
            minuteDelta + " minutes."
        );

       */

        if (!confirm("Estas seguro de cambiar esta fecha?")) {
            revertFunc();
        }
        else{
        	var id2  =event.id;
          var id =event.llave;
          var tis= event.tis;

        	var base="<?php echo base_url().$modulo.'/calendar/';?>";
        	var controller= "actualiza_remedicion";
        	var direccion = base+controller;
        	var dias= dayDelta;
   $.ajax({
  type: 'POST',
  url: direccion,
  data: {id:id,id2:id2,dias:dias,tis:tis},
  success: function(data){
    //$.fancybox("<div style='background-color:white; width:400px; height:400px;'>"+data+"</div>");
  }
});

        }

    }
	});
});
</script>

</head>
<body>

			<div class="box">
				<div class="head">
					<h2>
						<span class="icon en">P</span>
						<span  class="title">Calendar</span>
					</h2>
				</div>
        <div id="remediciones" style="color:white; width:200px; hight:90px; background-color:#7de36d;">
        Inspeccion realizada
        </div>
         <div id="DESMEMBRACIONES" style="color:white; width:200px; hight:90px; background-color:#fc566c;">
        Inspeccion pendiente
        </div>
         
				
        <div class="content full">

					<div id='calendar'></div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>

</html>