
<table id="table_2" class="data_table highlight dataTable" aria-describedby="table_2_info">
						<thead>
						

							<tr role="row"><th class="center sorting_asc" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">CAT</span>
									</span>
								</th><th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title ">COD</span>
									</span>
								</th><th class="center sorting" >
									<span class="th ">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">ASIGNATURA</span>
									</span>
								</th>

								<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">SECCION</span>
									</span>
								</th>

									<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">DIAS-HORAS-DURACION	</span>
									</span>
								</th>
									<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">AULA</span>
									</span>
								</th>
									<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">INS.</span>
									</span>

								</th>
									<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">TIT</span>
									</span>
								</th>
									<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">DOCENTE</span>
									</span>
								</th>
									<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">CAT</span>
									</span>
								</th>
									<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">PAG</span>
									</span>
								</th>

											<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">PERF</span>
									</span>
								</th>
											<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">NOTA</span>
									</span>
								</th>
											<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">OBSERVACIONES</span>
									</span>
								</th>
											<th class="center sorting" >
									<span class="th">
										<span class="arrow"></span>
										<span class="icon en"></span>
										<span class="title">MAE</span>
									</span>
								</th>

							</tr>
						</thead>
						
					<tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php foreach ($info_presupuesto as $key) {
                    	# code...
                    ?>
						<tr class="odd">
								<td class="center "><?php echo $key["cat"]; ?></td>
								<td class="center "><?php echo $key["cod"]; ?></td>
								<td class="center "><?php echo $key["asignatura"]; ?></td>
								<td class="center "><?php echo $key["sec"]; ?></td>
								<td class="center "><?php echo $key["dhd"]; ?></td>
								<td class="center "><?php echo $key["aula"]; ?></td>
								<td class="center "><?php echo $key["ins"]; ?></td>
								<td class="center "><?php echo $key["tit"]; ?></td>
								<td class="center "><?php echo $key["docente"]; ?></td>
								<td class="center "><?php echo $key["cate"]; ?></td>
								<td class="center "><?php echo $key["pag"]; ?></td>
								<td class="center "><?php echo $key["perf"]; ?></td>
								<td class="center "><?php echo $key["nota"]; ?></td>
								<td class="center "><?php echo $key["observaciones"]; ?></td>
								<td class="center "><?php echo $key["mae"]; ?></td>

								
							</tr>
							<?php } ?>
						</tbody></table>