<script type="text/javascript">
	$(document).ready(function(){
		 $("a.tbutton").click(function(event){
	        event.preventDefault();
	        lnk = this;
	        user = $(lnk).attr('href');
	        $.ajax({
	            type: "POST",
	            url: "<?= base_url() ?>uatm/control_arbitrio/agregar_arbitrio",
	            data: {
	                valor: user
	            }
	        }).done(function(html){
	            $.fancybox(html);
	        });
		}); 
	});
</script>
<table class="static_table highlight contenedor">		
	<thead>
		<tr>
			<th class="center" style="width:3%;">
				<span class="icon en"></span>
				<span class="title">NOMBRE DE LA EMPRESA</span>
			</th>
			<th class="center" style="width:3%;">
				<span class="icon en"></span>
				<span class="title">MATRICULA</span>
			</th>
			<th class="center" style="width:3%;">
				<span class="icon en"></span>
				<span class="title">ACCIÓN</span>
			</th>
		</tr>
	</thead>
	<tbody id="contenedor" class="contenedor">
		<?php
            foreach ($empresas as $emp) {  	
            
			?>
			<tr><td><?php echo $emp['nem_nombre'];?></td><td><?php echo $emp['emp_matricula'];?></td><td><a class="tbutton" href="<?php echo $emp['emp_id'];?>">Asociar Arbitrio</a></td></tr>
		<?php } ?>
	</tbody>
</table>