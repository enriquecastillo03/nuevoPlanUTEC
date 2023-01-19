

<?php $feriados=$this->db->query("SELECT * FROM feriados ")->result_array();?>




<script type="text/javascript">
$(document).ready(function() {

$("#fecha_inicio").datepicker({dateFormat: 'yy/mm/dd'});
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
           foreach($feriados as $fer){
            echo "{";
           	?>

         llave:"<?php echo $fer['id'];?>",
            id:"<?php echo $fer['id'];?>",
        title: "<?php echo $fer['descripcion'];?>",
        start: "<?php echo $fer['fecha_inicio'];?>",
        end: "<?php echo $fer['fecha_inicio'];?>",
        className: 'red',
        info:"",

        editable: true,

        

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
       

        	var base="<?php echo base_url(); ?>"+"sistema/feriados/edit_feriado";
        
        	var dias= dayDelta;
   $.ajax({
  type: 'POST',
  url: base,
  data: {id:id,dias:dias},
  success: function(data){
    create("note_success", {title:'Dia editado',text:'Gracias.'},{ expires: 5000});
  }
});

        }

    }
	});

$("#add_feriado").submit(function(e){
e.preventDefault();

   $.ajax({
  type: 'POST',
  url: "<?php echo base_url(); ?>"+"sistema/feriados/add_feriado",
  data: $("#add_feriado").serialize(),

  success: function(data){
      create("note_success", {title:'Dia feriado agregado',text:'Gracias.'},{ expires: 5000});
      setTimeout(function(){ location.reload() },1000);

  }
});


});


});
</script>

</head>
<body>

			<div class="box">
         <h4>Los dias que se agregen aca, seran excluidos del ciclo en cuyo rango de fechas de inicio y fin, se encuentren. </h4>
      
        <form id="add_feriado" action"<?php base_url().'sistema/feriados/add_feriado';?>">
        <table>
          <tr>
            <td>
              <small>Fecha de inicio</small>
        <input type="text" name="fecha_inicio" id="fecha_inicio">
				  </td>
          <td>
            <small>Descripcion</small>
            <input type="text" name="descripcion" id="descripcion">
          </td>
            <td>
            <small>Send</small>
            <input type="submit" value="Agregar feriado">
          </td>
        </tr>
      </table>
    </form>
        <div class="head">
					<h2>
						<span class="icon en">P</span>
						<span class="title">Calendar</span>
					</h2>
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