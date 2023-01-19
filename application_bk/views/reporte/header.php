<style type="text/css">
	table
		{			
		margin: 0 auto;
		}
	th
		{
		color: #FFFFFF;
		text-align: center;		
		background-color: #AAA;		 		
		}
	td
		{
		text-align: center;
		font-family: arial;				
		}
	.impar
		{
		background-color: #D7E5F7;	
		}
	.par
		{
		background-color: #B9D5F7;	
		}
</style>
<table align="center">
    <tr>
        <td align="left"><img src="<?php echo base_url();?>media/imagenes/reporte/logo_small.png" height="60"></td>
        <td>
            <span style="font-size: 14px; font-weight: bold;">ALCALDIA MUNICIPAL DE CHALATENANGO</span>
            <br />
            
            
            <br />
            <br />
            <span style="font-size: 11px; font-style: italic;"> Reporte de <?php echo $titulo; ?></span><br></td>
        <td align="right"><img src="<?php echo base_url();?>media/imagenes/reporte/reporte.png" height="60"></td>
    </tr>
    <tr><td colspan="3" style="text-align: right;">Chalatenango, <?php echo date('d/m/Y H:i:s') ?></td></tr>
</table>
<hr/>