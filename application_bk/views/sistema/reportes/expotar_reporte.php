<?php
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename='Reporte de ".$titulo.".xls'");
header("Pragma: no-cache");
header("Expires: 0");
date_default_timezone_set('America/El_Salvador');
?>
<table>
    <tr>
        <td align="left" width="200"><img src="<?php echo base_url();?>media/imagenes/reporte/logo_small.png" height="100"></td>
        <td colspan="<?php echo ($col-2);?>" align="center">
            <span style="font-size: 30px; font-weight: bold; margin: 0 auto;">ALCALDIA MUNICIPAL DE CHALATENANGO</span>
            <br />
            <span style="font-size: 20px; font-style: italic; margin: 0 auto;"> Reporte de <?php echo utf8_decode($titulo); ?></span>
       	</td>
        <td width="150"><img style="position:relative; right:0px;" src="<?php echo base_url();?>media/imagenes/reporte/reporte.png" height="100" width="150"></td>
    </tr>
    <tr><td colspan="<?php echo $col;?>" style="text-align: right;">Chalatenango, <?php echo date('d/m/Y H:i:s') ?></td></tr>
	<tr><td colspan="<?php echo $col;?>"></td></tr>
<?php
echo $resultado;
?>