<form autocomplete="off" name="formu_reporte" id="formu_reporte" method="post" action="<?php echo base_url();?>sistema/reportes/generar_reporte_pantalla" target="_blank">
	<div class="col_12 alpha">
		<div class="box">
			<div class="head">
				<h2>
					<span class="icon ws">R</span>
					<span class="title">REPORTES</span>
				</h2>
			</div>
			<div class="content">

                <select name="rep_id" id="rep_id">
                    <option value='0' selected>[Selecione...]</option>		
                        <?php
                            foreach ($reportes as $rep) {  	
                            
                            ?>
                            <option value="<?php echo $rep['rep_id']?>"><?php echo $rep['rep_nombre'];?></option>
                        <?php } ?>
                </select>
                <small>Reporte</small>
			</div>
		</div>
	</div>
	<div class="col_12 alpha" id="parte1">
		<div class="box">
			<div class="head">
				<h2>
					<span class="icon ws">R</span>
					<span class="title">FILTROS DE CAMPOS DE TABLAS RELACIONADAS</span>
				</h2>
			</div>
			<div class="content">
                <table id="fil_content" style="margin: 0 auto;">
                </table>		
			</div>
		</div>
	</div>
	<div class="col_12 alpha" id="parte2">
		<div class="box">
			<div class="head">
				<h2>
					<span class="icon ws">R</span>
					<span class="title">FILTROS DEL REPORTE</span>
				</h2>
			</div>
			<div class="content">
                <table id="fil_content_2" style="margin: 0 auto;" class="data_table dataTable highlight">
                </table>
                <br />
                <table style="display:none;" id="tabla_3" class="data_table dataTable highlight">
                	<thead>
                    	<tr role="row">
                            <th class="center sorting_asc"  role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Column 1 : activate to sort column descending"><span class="th"><span class="arrow"></span><span class="icon en"></span><span class="title">CONDICION</span></span></th>
                            <th width="275" class="center sorting_asc"  role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Column 1 : activate to sort column descending"><span class="th"><span class="arrow"></span><span class="icon en"></span><span class="title">EXTRA</span></span></th>
                            <th width="112" class="center sorting_asc"  role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Column 1 : activate to sort column descending"><span class="th"><span class="arrow"></span><span class="icon en"></span><span class="title">ACCION</span></span></th>
                        </tr>
                    </thead>
                    <tbody id="fil_content_3">
                    
                    </tbody>
                </table>			
			</div>
		</div>
	</div>
	<br>
    <div class="col_12 alpha">
        <div class="content">
            <div align="center">
                <button type="button" class="button small dodger" id="enviar" data-bandera="1"><span class="icon en">o</span>Generar Reporte</button>
                <button type="submit" class="button small dodger" id="generar" data-bandera="0"><span class="icon en">/</span>Generar PDF</button>
                <button type="button" class="button small dodger" id="exportar" data-bandera="0"><span class="icon en">n</span>Exportar a Excel</button>
            </div>			
        </div>
	</div>
    <div class="col_12 alpha">
        <div class="content">
			<div class="content_full" id="resultado_reporte"></div>		
        </div>
	</div>
    <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
</form>
<script src="<?php echo base_url('js/sistema/generar_reporte.js');?>"></script>