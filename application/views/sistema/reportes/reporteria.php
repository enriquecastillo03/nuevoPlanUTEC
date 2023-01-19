<h3 align="center">CREACIÓN DE CONSULTA PARA REPORTE</h3>
<p>
<form name="formu_query" id="formu_query" method="post" action="<?php echo base_url();?>sistema/reportes/reporteria">
	<p>
		<input type="text" name="rep_nombre" id="rep_nombre" placeholder="Digite el Nombre del Reporte"/>
		<small>Nombre del reporte</small>
	</p>
	<p>
		<select name="rxr_id_rol" id="rxr_id_rol">
			<option value='0' selected>[Selecione...]</option>		
				<?php
		            foreach ($roles as $rol) {  			            
					?>
					<option value="<?php echo $rol['rol_id']?>"><?php echo $rol['rol_nombre'];?></option>
				<?php } ?>
		</select>
		<small>Rol</small>
	</p>
	<div class="content_full">
    	<div align="center">
			<button type="button" class="button small mint rounded" id="manual"><span class="icon en">&</span>Manual&nbsp;&nbsp;&nbsp;&nbsp;</button>
			<button type="button" class="button small mint rounded " id="probar"><span class="icon en">h</span>Comprobar Query</button>
			<button type="button" class="button small mint rounded" id="borrar"><span class="icon en">u</span>Borrar Query</button>
		</div>
		<table class="static_table highlight contenedor" id="datagried">		
			<thead>
				<tr>
					<th class="center" width="75">
						<span class="icon en"></span>
						<span class="title">TIPO</span>
					</th>
					<th class="center">
						<span class="icon en"></span>
						<span class="title">DESCRIPCIÓN</span>
					</th>
					<th class="center" width="500">
						<span class="icon en"></span>
						<span class="title">ACCIÓN</span>
					</th>
				</tr>
			</thead>
			<tbody id="contenedor" class="contenedor">
				<tr>
					<td height="100">
						<strong style='color:blue;'>SELECT</strong> <input type="checkbox" title="DISTINCT" name="distinct" id="distinct"/>
					</td>
					<td class="select_td_1" id="select_td_1">
						*
					</td>
					<td class="select_td_2" id="select_td_2">							
						<select name="funcion" id="funcion">
							<option value='0' selected>[Selecione...]</option>
							<option value='1'>SUM</option>
							<option value='2'>MAX</option>
							<option value='3'>MIN</option>
							<option value='4'>AVG</option>
							<option value='5'>COUNT</option>
						</select>	
						<small>Función</small>
						<div>							
							<div style="width:350px; float:left;">
								<select name="campos" id="campos">
									<option value='0' selected>[Selecione...]</option>
								</select>
								<small>Campos</small>
							</div>
							<div style="width:125px; float:right;">
								<input type="text" name="alias" id="alias" maxlength="15">
								<small>Alias</small>
							</div>
						</div>

						<button type="button" class="button green" id="introducir2"><span class="icon en">]</span>Introducir Campo</button>					
					</td>
				</tr>
				<tr>
					<td height="100">
						<strong style='color:blue;'>FROM</strong>
					</td>
					<td class="from_td_1" id="from_td_1">
					</td>
					<td class="from_td_2" id="from_td_2">						
						<select name="relacion" id="relacion">
							<option value='0' selected>[Selecione...]</option>
						</select>
						<small>Tipo de relación</small>
						<div>							
							<div style="width:350px; float:left;">
								<select name="tablas" id="tablas">
									<option value='0'selected>[Selecione...]</option>
								</select>
								<small>Tablas</small>
							</div>
							<div style="width:125px; float:right;">
								<input type="text" name="alias2" id="alias2" maxlength="15">
								<small>Alias</small>
							</div>
						</div>
						<select name="tabla1" id="tabla1">
							<option value='0'selected>[Selecione...]</option>
						</select>
						<small>Tabla 1 de relación</small>
						<select name="campo1" id="campo1">
							<option value='0'selected>[Selecione...]</option>
						</select>
						<small>Campo 1 de relación</small>
						<select name="tabla2" id="tabla2">
							<option value='0'selected>[Selecione...]</option>
						</select>
						<small>Tabla 2 de relación</small>
						<select name="campo2" id="campo2">
							<option value='0'selected>[Selecione...]</option>
						</select>
						<small>Campo 2 de relación</small>
						<button type="button" class="button green" id="introducir"><span class="icon en">]</span>Introducir Tabla</button>
					</td>
				</tr>
			</tbody>
		</table>
		<textarea name="query_manual" id="query_manual" style="display:none;"></textarea>
		<small>Query</small>	
		</p>
	    </br>
	    <span style="font-size:15px;"><p><span style="color:red;"><strong>IMPORTANTE</strong></span>: No se recomienda realizar una consulta con más de 10 campos (columnas) seleccionados por cuestión de visualización de los resultados.</p></span>
	    </br>
			<button type="button" class="button dodger" id="enviar"><span class="icon en">W</span>Guardar Query</button>
	</div>
</form>
</p>
<script src="<?php echo base_url('js/sistema/reporteria.js');?>"></script>