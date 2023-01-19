
<table id="table_2" class="data_table highlight dataTable" aria-describedby="table_2_info">
						<thead>
						

							<tr role="row"><th class="center sorting_asc" >
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

							</tr>
						</thead>
						
					<tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php foreach ($data as $key) {
                    	# code...
                    ?>
						<tr class="odd">
								<td class="center "><?php echo $key["fechainicial"]; ?></td>
								<td class="center "><?php echo $key["fechafinal"]; ?></td>
								
							</tr>
							<?php } ?>
						</tbody></table>