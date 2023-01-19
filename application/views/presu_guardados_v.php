
<?php
$facultad = $this->db->query("SELECT * FROM facultad ")->result_array();

foreach ($facultad as $value) {

	?>
<div>
<h2>Facultad de: <?php echo $value["nombre"]; ?></h2>
 <?php
//hay q obtener las carreras en base a la facultad
 $sql= "SELECT car.`nombre` AS carrera, car.`id` AS carrera_id FROM facultad AS facu
INNER JOIN `facu_x_carre` fxc ON fxc.`id_facu` = facu.`id`
INNER JOIN carreras car ON car.`id` = fxc.`id_carre`
WHERE fxc.`id_facu` = ".$value["id"];
$carreras_x_facu = $this->db->query($sql)->result_array();
foreach ($carreras_x_facu as $key) { ?>
 <div class="container">
			<ul class="quick_buttons">
				<?php
                  $sql_presupuestos = "SELECT * FROM presupuestos WHERE id_facultad=".$value["id"];
				  $presu_data = $this->db->query($sql_presupuestos)->result_array();
				  foreach ($presu_data as $key_presu ) {?>
				  <li>

					<a href="#" class="presupuestos" idpresu="<?php echo $key_presu['id']; ?>">
						<span class="icon en">1</span></a>
						<span class="title"><?php echo $key_presu["nombre"]; ?><br>
                         <?php
                        if($key_presu["estado"] == 0){
                        	
                        	echo "Estado: <label style='color:red;'>No oficial</label>";
                        }else{

                        	echo "Estado: <label style='color:green;'>Oficial</label>";
                        }
                        ?>
                        <br>
						<a href="Validar">Validar</a>&nbsp;&nbsp;&nbsp;
							<a href="<?php echo base_url().'presupuestos/presu_guardados/delete_presu/'.$key_presu['id']; ?>">Eliminar</a>
                          <br>
						</span>
					

				</li>

				  <?php }
				?> 
				
				
			</ul>
		</div>


<?php }


 ?>
</div>	
<br>
	
<?php }
?>

<script>
$( document ).ready(function() {

  $(".presupuestos").click(function(e){
    e.preventDefault();
     presupuesto = $(this).attr("idpresu");
    $.ajax({
		  type: 'POST',
		  url: '<?php echo base_url();?>'+"presupuestos/presu_guardados/presu_view",
		  data: {presupuesto:presupuesto},
		  success: function(data){
		    $.fancybox(data); 
		  }
		});

  })


  
});
</script>