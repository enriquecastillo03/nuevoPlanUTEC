<form name="formu_filtro" id="formu_filtro" method="post" action="<?php echo base_url();?>sistema/reportes/guardar_filtros">
	<div class="col_12 alpha">
		<div class="box">
			<div class="head">
				<h2>
					<span class="icon ws">R</span>
					<span class="title">RELACIONES ENTRE TABLAS</span>
				</h2>
			</div>
			<div class="content">
				<?php
					$valor_cambio=1;
					error_reporting(0);
					$query=explode("FROM",$reporte['rep_consulta']);
					$query="select * from ".$query[1];

					$resultado = mysql_query($query);
					$campos    = mysql_num_fields($resultado);
					$filas     = mysql_num_rows($resultado);
					$tabla     = mysql_field_table($resultado, 0);

					for ($i=0; $i < $campos; $i++) {
					    $nombre   	= mysql_field_name($resultado, $i);
					    $tip	  	= mysql_field_type($resultado, $i);
					    $banderas 	= mysql_field_flags($resultado, $i);
					    $tipo=explode(" ",  $banderas);
					    if($tipo[0]=="multiple_key") {
					    	$fields[]=$nombre;
					    	$valor_cambio=0;
					    }

					    if($tip=="int" && $tipo[0]!="multiple_key") {
					    	if(!isset($tipo[1])) {
					    		$other_fields[]=$nombre;
					    		$valor_cambio=0;
					    	}
					    }
					}
					$data_tabla=$this->db->query("SHOW TABLES FROM alcaldia");

					foreach($data_tabla->result() as $row) {
						for($i=0;$i<COUNT($fields);$i++) {
							$pre_tab=substr($row->Tables_in_alcaldia,0,3);
							$suf_cam=substr($fields[$i], -3,3);
							if($pre_tab==$suf_cam) {
								$tables[$i][]=$row->Tables_in_alcaldia;

						        $query="select * from ".$row->Tables_in_alcaldia;
						        $data_campos=$this->db->query($query);

						        foreach($data_campos->list_fields() as $row)
						            $campos_tabla[$i][]=$row;
							}
						}
					}
					echo '<table class="static_table highlight">';
					echo '	<thead>
								<tr>
									<th class="center" width="175">
										<span class="icon en"></span>
										<span class="title">CAMPO</span>
									</th>
									<th class="center" width="200">
										<span class="icon en"></span>
										<span class="title">ALIAS DEL CAMPO</span>
									</th>
									<th class="center" width="400">
										<span class="icon en"></span>
										<span class="title">TABLA</span>
									</th>
									<th class="center" width="250">
										<span class="icon en"></span>
										<span class="title">CAMPO RELACION</span>
									</th>
								</tr>
							</thead>
							<tbody>';
					for($i=0;$i<COUNT($fields);$i++) {
						if(!strpos(substr($fields[$i], 0,3),"x")) { //Con esto oculto todos los campos que son llave foranea que vienen de tablas de muchos a muchos
							$result=explode($fields[$i], $reporte['rep_consulta']);
							if(count($result)>1) {
								$result2=explode(" ", trim($result[1]));
								if(($result2[0]=="as" || $result2[0]=="AS") && ($result2[0]!=",")) {
									$colocar=' value="'.str_replace(',', '', str_replace('"', '', $result2[1])).'" readonly data-unico="'.str_replace(',', '', str_replace('"', '', $result2[1])).'"';
								}
								else
									$colocar=' data-unico="0" ';
							}
							else
								$colocar=' data-unico="0" ';

							echo '<tr>';
							$x=COUNT($tables[$i]);
							if($x>1) {
								if(COUNT($this->reporteria->verificar_registros('fil_filtro',array("fil_id_rep"=>$reporte['rep_id'],"fil_nombre_campo"=>$fields[$i])))==0) {
									echo "<td><strong style='color:blue;'>".$fields[$i]."</strong></td>";
									echo '<td><input type="text" name="alias_nc[]" id="tex_'.$fields[$i].'" maxlength="20" '.$colocar.'></td>';
									echo '<td><select name="filtros_revision_tabla[]" data-cava="'.$fields[$i].'" data-repo="'.$reporte['rep_id'].'">';
									echo 	'	<option value="0">[Seleccione...]</option>';
									for($j=0;$j<$x;$j++) {
										echo '<option value="'.$tables[$i][$j].'">'.$tables[$i][$j].'</option>';
									}		
									echo '</select></td>';
									echo '<td><select name="filtros_revision[]" id="'.$fields[$i].'">';
									echo '</select></td>';
								}
								else {
									$nombre_tabla=$this->reporteria->verificar_registro('fil_filtro',array("fil_id_rep"=>$reporte['rep_id'],"fil_nombre_campo"=>$fields[$i]));
									echo "<td><strong style='color:blue;'>".$fields[$i]."</strong></td><td>".$nombre_tabla['fil_alias_nombre_campo']."</td><td> <strong style='color:blue;'>".$nombre_tabla['fil_nombre_tabla']."</strong></td><td>".$nombre_tabla['fil_nombre_campo_relacion']."</td>";							}
							}
							else {
								if($x==1) {
									if(COUNT($this->reporteria->verificar_registros('fil_filtro',array("fil_id_rep"=>$reporte['rep_id'],"fil_nombre_tabla"=>$tables[$i][0],"fil_nombre_campo"=>$fields[$i])))==0) {
										$posible_alias=explode("_",substr($tables[$i][0],4));
										if($colocar==' data-unico="0" ')
											$colocar=' value="'.ucwords($posible_alias[0]." ".$posible_alias[1]).'" data-unico="0"';
										echo '<td><strong style="color:blue;">'.$fields[$i].'</strong></td>';
										echo '<td><input type="text" name="alias_nc[]" id="tex_'.$fields[$i].'" maxlength="20" '.$colocar.'></td>';
										echo '<td> <strong style="color:blue;">'.$tables[$i][0].'</strong></td>';
										echo '<td><select name="filtros_revision[]">';
										for($z=0;$z<count($campos_tabla[$i]);$z++)
											echo '<option value="'.$reporte['rep_id'].' '.$tables[$i][0].' '.$fields[$i].' '.$campos_tabla[$i][$z].'">'.$campos_tabla[$i][$z]."</option>";											
										echo '</select></td>';
									}
									else {
										$nombre_tabla=$this->reporteria->verificar_registro('fil_filtro',array("fil_id_rep"=>$reporte['rep_id'],"fil_nombre_campo"=>$fields[$i]));
										echo "<td><strong style='color:blue;'>".$fields[$i]."</strong></td><td>".$nombre_tabla['fil_alias_nombre_campo']."</td><td> <strong style='color:blue;'>".$tables[$i][0]."</strong></td><td>".$nombre_tabla['fil_nombre_campo_relacion']."</td>";
									}
								}
								else {
									echo "<td><strong style='color:blue;'>".$fields[$i]."</strong></td>";
									echo '<td><input type="text" name="alias_nc[]" id="tex_'.$fields[$i].'" maxlength="20" '.$colocar.'></td>';
									echo '<td><select name="filtros_revision_tabla[]" data-cava="'.$fields[$i].'" data-repo="'.$reporte['rep_id'].'">';
									echo 	'	<option value="0">[Seleccione...]</option>';
									$data_tabla=$this->db->query("SHOW TABLES FROM alcaldia");
									foreach($data_tabla->result() as $row){
										echo '<option value="'.$row->Tables_in_alcaldia.'">'.$row->Tables_in_alcaldia.'</option>';
									}		
									echo '</select></td>';
									echo '<td><select name="filtros_revision[]" id="'.$fields[$i].'">';
									echo '</select></td>';
								}
							}
							echo '</tr>';
						}
					}

					for($i=0;$i<COUNT($other_fields);$i++) {
						if(!strpos(substr($other_fields[$i], 0,3),"x")) { //Con esto oculto todos los campos que son llave foranea que vienen de tablas de muchos a muchos
							echo '<tr>';
							if(COUNT($this->reporteria->verificar_registros('fil_filtro',array("fil_id_rep"=>$reporte['rep_id'],"fil_nombre_campo"=>$other_fields[$i])))==0) {
							}
							else{
								$nombre_tabla=$this->reporteria->verificar_registro('fil_filtro',array("fil_id_rep"=>$reporte['rep_id'],"fil_nombre_campo"=>$other_fields[$i]));
								echo "<td><strong style='color:blue;'>".$other_fields[$i]."</strong></td><td>".$nombre_tabla['fil_alias_nombre_campo']."</td><td> <strong style='color:blue;'>".$nombre_tabla['fil_nombre_tabla']."</strong></td><td>".$nombre_tabla['fil_nombre_campo_relacion']."</td>";
							}
							echo '</tr>';
						}
					}
					echo '</tbody></table>';
				?>
			</div>
		</div>
	</div>
	<div class="col_12 alpha">
		<div class="box">
			<div class="head">
				<h2>
					<span class="icon ws">R</span>
					<span class="title">OTROS CAMPOS QUE PUEDEN RELACIONARSE</span>
				</h2>
			</div>
			<div class="content">
				<?php
				echo '<table class="static_table highlight">';
					echo '	<thead>
								<tr>
									<th class="center" width="175">
										<span class="icon en"></span>
										<span class="title">CAMPO</span>
									</th>
									<th class="center" width="200">
										<span class="icon en"></span>
										<span class="title">ALIAS DEL CAMPO</span>
									</th>
									<th class="center" width="400">
										<span class="icon en"></span>
										<span class="title">TABLA</span>
									</th>
									<th class="center" width="250">
										<span class="icon en"></span>
										<span class="title">CAMPO RELACION</span>
									</th>
								</tr>
							</thead>
							<tbody>';
				for($i=0;$i<COUNT($other_fields);$i++) {
					if(!strpos(substr($other_fields[$i], 0,3),"x")) { //Con esto oculto todos los campos que son llave foranea que vienen de tablas de muchos a muchos
						$result=explode($other_fields[$i], $reporte['rep_consulta']);
						if(count($result)>1) {
							$result2=explode(" ", trim($result[1]));
							if(($result2[0]=="as" || $result2[0]=="AS") && ($result2[0]!=",")) {
								$colocar=' readonly data-unico="'.str_replace(',', '', str_replace('"', '', $result2[1])).'"';
							}
							else
								$colocar=' data-unico="0" ';
						}
						else
							$colocar=' data-unico="0" ';

						echo '<tr>';
						if(COUNT($this->reporteria->verificar_registros('fil_filtro',array("fil_id_rep"=>$reporte['rep_id'],"fil_nombre_campo"=>$other_fields[$i])))==0) {
							echo "<td align='right'><strong style='color:blue;'>".$other_fields[$i]."</strong></td>";
							echo '<td><input type="text" name="alias_nc[]" id="tex_'.$other_fields[$i].'" maxlength="20" '.$colocar.' disabled></td>';
							echo '<td><select name="filtros_revision_tabla[]" data-cava="'.$other_fields[$i].'" data-repo="'.$reporte['rep_id'].'">';
							echo 	'	<option value="0">[Seleccione...]</option>';
							$data_tabla=$this->db->query("SHOW TABLES FROM alcaldia");
							foreach($data_tabla->result() as $row){
								echo '<option value="'.$row->Tables_in_alcaldia.'">'.$row->Tables_in_alcaldia.'</option>';
							}		
							echo '</select></td>';
							echo '<td><select name="filtros_revision[]" id="'.$other_fields[$i].'">';
							echo '</select></td>';
						}
						echo '</tr>';
					}
				}	
				echo '</tbody></table>';

				mysql_free_result($resultado);

				if($valor_cambio)
					header('Location: '.base_url('sistema/reportes/reporteria'));
				?>
			</div>
		</div>
	</div>	
	<div class="col_12 alpha">
		<div class="content">
			<button type="submit" class="button dodger" id="enviar"><span class="icon en">W</span>Guardar Filtros</button>			
		</div>
	</div>	
</form>	
<script src="<?php echo base_url('js/sistema/filtros.js');?>"></script>