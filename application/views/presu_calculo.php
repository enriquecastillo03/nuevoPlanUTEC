<?php 

function check_in_range($start_date, $end_date, $evaluame) {
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($evaluame);
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

function diaSemana($ano,$mes,$dia)
{
	// 0->domingo	 | 6->sabado
	$dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
		return $dia;
}




?>



<table id="table_2" class="data_table highlight dataTable" aria-describedby="table_2_info">
						<thead>
						

							<tr role="row">
                               <th class="center sorting_asc" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Asignatura</span>
									</span>
								</th>
								<th class="center sorting_asc" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title"> Dia, horario, duracion</span>
									</span>
								</th>
								     <th class="center sorting_asc" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Docente</span>
									</span>
								</th>
								<th class="center sorting_asc" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">Inicio de semana</span>
									</span>
								</th><th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title ">Fin de semana</span>
									</span>
								</th>

								<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title ">Feriados existentes en la semana a evaluar</span>
									</span>
								</th>

								<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title ">Numero de clases</span>
									</span>
								</th>

								<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title ">Factor de pago</span>
									</span>
								</th>

								<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title ">Maestria pago</span>
									</span>
								</th>


							</tr>
						</thead>
						
					<tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php $m = 1; foreach ($data as $key) {
                    	# code...
                    ?>
						<tr class="odd">
							<?php if($m == 1): ?>
							<td rowspan="<?php echo $total_iteraciones_rows; ?>" class="center "><?php echo $key["asignatura"]; ?></td>
							<?php endif; ?>
							<td class="center "><?php echo $key["DiasTexto"]."-".$key["HorarioTexto"]; ?></td>
							<td class="center "><?php echo $key["nombres"]."-".$key["Apellidos"]; ?></td>
								<td class="center "><?php echo $key["fechainicial"]; ?></td>
								<td class="center "><?php echo $key["fechafinal"]; ?></td>
								<td class="center "><?php
														$start_date = $key["fechainicial"];
														$end_date = $key["fechafinal"];
														
														$cantidadferiados = 0; 
														$feriados = $this->db->query("select * from feriados")->result_array();
                                                        foreach ($feriados as $key1) {
                                                        	# code...
                                                        
														if (check_in_range($start_date, $end_date, $key1["fecha_inicio"])) {
														    $cantidadferiados++;
														} 

                                                      }

                                                      echo $cantidadferiados; 
														?>


                                </td>
                               <td><?php
                                 

                                     $fecha1 = $start_date;
										$fecha2 = $end_date ;

										for($i=$fecha1;$i<=$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
										   
										    $fecha_buc = explode("-", $i);
										    $numero_dia = diaSemana($fecha_buc[0],$fecha_buc[1],$fecha_buc[2]);
										    	echo $i."dia:".$numero_dia."<br />"; 
										 //aca puedes comparar $i a una fecha en la bd y guardar el resultado en un arreglo

										}
                               ?></td>
                                <td class="center "><?php echo $key["factorpago"]; ?></td>
                                <td class="center "><?php echo $key["maestriapago"]; ?></td>
								
							</tr>
							<?php $m++; 
                              if($m == ($total_iteraciones_rows+1)):
                                $m = 1; 
                              	endif;
						} ?>
						</tbody></table>