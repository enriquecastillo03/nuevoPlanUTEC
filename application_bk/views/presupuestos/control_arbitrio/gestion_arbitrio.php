<style type="text/css">
    .mostrar_error
        {
        color: red;
        }
</style>
<script type="text/javascript">
$(document).ready(function() {
    $("#arb_id_tipo").select2();
});
</script>
<div class="col_6 alpha">
	<form name="formu_arbitrio" id="formu_arbitrio" method="post" action="<?php echo base_url('uatm/control_arbitrio/gestion_arbitrio');?>">
		<div class="box">
			<div class="head">
				<h2>
					<span class="icon ws">R</span>
					<span class="title">Arbitrio</span>
				</h2>
			</div>
			<div class="content">
				<p>
					<input id="arb_nombre" name="arb_nombre" type="text" placeholder="Digite el Nombre del Arbitrio">
					<div class="mostrar_error"><?php echo form_error('arb_nombre'); ?></div>
					<small>Nombre del arbitrio</small>
				</p>
				<p>
					<textarea id="arb_descripcion" name="arb_descripcion"  placeholder="Digite una Descripción"></textarea>
					<small>Descripción del arbitrio</small>
				</p>
				<p>
					<select name="arb_id_tipo" id="arb_id_tipo">
						<option value="0">[Seleccione...]</option>
						<?php
                            foreach ($tipo_arbitrio as $tar) {    
                            
                            ?>
                            <option value="<?php echo $tar['tar_id']?>"><?php echo $tar['tar_nombre'];?></option>
                        <?php } ?>
					</select>
					<div class="mostrar_error"><?php echo form_error('arb_id_tipo'); ?></div>
					<small>Tipo de arbitrio</small>
				</p>
			</div>
		</div>
	</div>
	<div class="col_6 alpha">
		<div class="box">
			<div class="head">
				<h2>
					<span class="icon ws">]</span>
					<span class="title">Tarifa</span>
				</h2>
			</div>
			<div class="content">
				<br>
				<p>
					<input id="trf_precio" name="trf_precio" type="text" placeholder="Digite el Precio de la Tarifa">
					<div class="mostrar_error"><?php echo form_error('trf_precio'); ?></div>
					<small>Precio de la tarifa</small>
				</p>
				<p>
					<textarea id="trf_descripcion" name="trf_descripcion"  placeholder="Digite una Descripción"></textarea>
					<div class="mostrar_error"><?php echo form_error('trf_descripcion'); ?></div>
					<small>Descripción de la tarifa</small>
				</p>
				<br>
				<br>
			</div>
		</div>
	</div>
	<div class="col_12 alpha">
		<div class="box">
		<button type="submit" class="button dodger" id="enviar"><span class="icon en">W</span>Guardar Arbitrio</button>
		</div>
	</div>
</form>